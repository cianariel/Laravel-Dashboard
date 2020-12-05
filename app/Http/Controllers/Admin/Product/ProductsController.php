<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\System\Product;
use App\Models\System\ProductAttribute;
use App\Models\System\ProductType;
use App\Rules\SanitizeHtml;

/**
 * Admin Products Resource Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Product
 */
class ProductsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site();
        $query = $request->query();
        $perPage = 10;
        $withTrashed = false;

        if (filled($query)) {
            if ($request->has('per_page')) {
                $perPage = strip_tags(trim($query['per_page']));

                if (is_numeric($perPage)) {
                    $perPage = safe_integer($perPage);
                } else {
                    $perPage = 10;
                    $query['per_page'] = $per_page;
                }
            }

            if ($request->has('with_trashed')) {
                $with_trashed  = strip_tags($query['with_trashed']);

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $products = $site->products()->withTrashed()->paginate($perPage);
        } else {
            $products = $site->products()->paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Products'      => ['path' => route('admin.products.index'),          'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.products.index', compact('breadcrumbs', 'products', 'query', 'withTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = Product::STATUS_TYPES;
        $featured_types = Product::FEATURED_TYPES;
        $columns = [
            'name',
            'label'
        ];
        $product_types = ProductType::where('status', ProductType::ACTIVE)->select($columns)->cursor();
        $types = [];
        $attributes = [];

        if ($product_types->count() !== 0) {
            foreach ($product_types as $type) {
                $types[$type->name] = $type->label;
            }
        } else {
            flash('Warning: No Product types exists.');
        }

        // Breadcrumbs
        $breadcrumbs = [
            'Dashboard'      => ['path' => admin_url(),                         'active' => false],
            'Products'       => ['path' => route('admin.products.index'),             'active' => false],
            'Add Product'    => ['path' => route('admin.products.create'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.products.create', compact('breadcrumbs', 'featured_types', 'product_types', 'status_types', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'type'          => ['string', 'required', 'exists:system.product_types,name'],
                'label'         => ['string', 'required', 'max:150', new SanitizeHtml()],
                'description'   => ['string', 'required'],
                'media'         => ['file', 'nullable','image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'price'         => ['numeric', 'required', 'min:1'],
                'tags'          => ['string', 'nullable', new SanitizeHtml()],
                'featured'      => ['string', 'required', Rule::in(Product::FEATURED_TYPES)],
                'status'        => ['string', 'required', Rule::in(Product::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $stripe_product = false;
            $stripe_error = '';
            $slug = unique_slug('product', strip_tags($request->input('label')));
            $name = $slug;

            //-------------------------//
            // Stripe Product
            //-------------------------//

            try {
                \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                $stripe_product = \Stripe\Product::create(
                    [
                    'name'      => $name,
                    'active'    => ($request->input('status') == Product::ACTIVE) ? true : false
                    ]
                );
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $json = json_decode(json_encode($e->getJsonBody()), false);
                $error = $json->error;
                $stripe_error = $error->message;
            }

            if (filled($stripe_error)) {
                flash($stripe_error);
                return back()->withInput();
            }

            // Product
            if ($stripe_product && is_object($stripe_product)) {
                $product = new Product();
                $product->uuid = Str::uuid();
                $product->name = $name;
                $product->label = strip_tags($request->input('label'));
                $product->slug = $slug;
                $product->type = $request->input('type');
                $product->description = $request->input('description');
                $product->price = cents(safe_float($request->input('price')));
                $product->tags =  serialize(explode(PHP_EOL, strip_tags($request->input('tags'))));
                $product->featured = $request->input('featured');
                $product->status = $request->input('status');
                $product->stripe_product = $stripe_product->id;

                // File Uploads
                if ($request->hasFile('media')) {
                    $file = $request->file('media');

                    $product->addMedia($file)
                        ->toMediaCollection('images');
                }

                // Save
                $product->saveOrFail();

                // Assign to site
                $site->assignProduct($product);

                flash('Successfully added product.');
                return redirect()->route('admin.products.edit', $product);
            } else {
                flash('Failed to create stripe product.');
                return back()->withInput();
            }
        }

        flash('Invaild action.');
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (Product::where('id', $id)->exists()) {
            $site = site();
            $product = $site->products()->where('id', $id)->firstOrFail();

            // Breadcrumbs
            $breadcrumbs = [
                'Dashboard'      => ['path' => admin_url(),                         'active' => false],
                'Products'       => ['path' => route('admin.products.index'),             'active' => false],
                $product->label  => ['path' => route('admin.products.show', $product),    'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.products.show', compact('breadcrumbs', 'product'));
        }

        flash('Product does not exist.');
        return redirect()->route('admin.products.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Product::where('id', $id)->exists()) {
            $site = site();
            $product = $site->products()->where('id', $id)->firstOrFail();
            $status_types = Product::STATUS_TYPES;
            $featured_types = Product::FEATURED_TYPES;
            $columns = [
                'name',
                'label'
            ];
            $product_types = ProductType::where('status', ProductType::ACTIVE)->select($columns)->cursor();
            $types = [];
            $attributes = [];

            if ($product_types->count() !== 0) {
                foreach ($product_types as $type) {
                    $types[$type->name] = $type->label;
                }
            } else {
                flash('Warning: No Product types exists.');
            }

            // Breadcrumbs
            $breadcrumbs = [
                'Dashboard'      => ['path' => admin_url(),                         'active' => false],
                'Products'       => ['path' => route('admin.products.index'),             'active' => false],
                'Edit Product'   => ['path' => route('admin.products.edit', $product),            'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.products.edit', compact('breadcrumbs', 'featured_types', 'status_types', 'types', 'product'));
        }

        flash('Product does not exist.');
        return redirect()->route('admin.products.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && Product::where('id', $id)->exists()) {
            $rules = [
                'type'          => ['string', 'required', 'exists:system.product_types,name'],
                'label'         => ['string', 'required', 'max:150', new SanitizeHtml()],
                'description'   => ['string', 'required'],
                'media'         => ['file', 'nullable','image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'price'         => ['numeric', 'required', 'min:1'],
                'tags'          => ['string', 'nullable', new SanitizeHtml()],
                'featured'      => ['string', 'required', Rule::in(Product::FEATURED_TYPES)],
                'status'        => ['string', 'required', Rule::in(Product::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $stripe_product = false;
            $stripe_error = '';

            $product = $site->products()->where('id', $id)->firstOrFail();
            $stripe_id = $product->stripe_product;

            if ($product->label != $request->input('label')) {
                $slug = unique_slug('product', strip_tags($request->input('label')));
                $name = $slug;

                $product->name = $name;
                $product->slug = $slug;
                $product->label = strip_tags($request->input('label'));

                //-------------------------//
                // Stripe Product
                //-------------------------//

                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_product = \Stripe\Product::update(
                        $stripe_id,
                        [
                        'name'      => $name,
                        'active'    => ($request->input('status') == Product::ACTIVE) ? true : false
                        ]
                    );
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;

                    if (filled($stripe_error)) {
                        flash($stripe_error);
                        return back()->withInput();
                    }
                }
            }

            $product->type = $request->input('type');
            $product->description = $request->input('description');
            $product->price = cents(safe_float($request->input('price')));
            $product->tags =  serialize(explode(PHP_EOL, strip_tags($request->input('tags'))));
            $product->featured = $request->input('featured');
            $product->status = $request->input('status');

            // File Uploads
            if ($request->hasFile('media')) {
                $file = $request->file('media');

                $product->addMedia($file)
                    ->toMediaCollection('images');
            }

            // Save
            $product->saveOrFail();

            flash('Successfully updated product.');
            return redirect()->route('admin.products.edit', $product);
        }

        flash('Invaild action or product does not exist.');
        return redirect()->route('admin.products.index');
    }

    /**
     * Delete the specified media image from product.
     *
     * @param  int $id
     * @param  int $image
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaImage(Request $request, $id, $image)
    {
        if ($request->isMethod('get') && Product::where('id', $id)->exists()) {
            $site = site();
            $product = $site->products()->where('id', $id)->firstOrFail();
            $images = $product->getMedia('images');

            foreach ($images as $index => $_image) {
                if ($index == $image) {
                    $_image->delete();
                    flash('Successfully deleted media image.');
                    return redirect()->route('admin.products.edit', $product);
                }
            }
        }

        flash('Product or media resource does not exist');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Product::where('id', $id)->exists()) {
            $site = site();
            $product = $site->products()->where('id', $id)->firstOrFail();

            if ($product->delete()) {
                flash('Successfully deleted product.');
                return redirect()->route('admin.products.index');
            }
        }

        flash('Invaild action or product does not exist.');
        return redirect()->route('admin.products.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && Product::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $product = $site->products()->withTrashed()->where('id', $id)->firstOrFail();

            if ($product->restore()) {
                flash('Successfully restored product from trash.');
                return redirect()->route('admin.products.edit', $product);
            }
        }

        flash('Invaild action or product does not exist.');
        return redirect()->route('admin.products.index');
    }

    /**
     * Delete forever the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && Product::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $product = $site->products()->withTrashed()->where('id', $id)->firstOrFail();
            $stripe_id = $product->stripe_product;

            //-------------------------//
            // Stripe Product
            //-------------------------//
            if (filled($stripe_id)) {
                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_product = \Stripe\Product::retrieve($stripe_id);
                    $stripe_product->delete();
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;

                    if (filled($stripe_error)) {
                        flash($stripe_error);
                        return redirect()->route('admin.products.index');
                    }
                }
            }

            // Delete Images
            $images = $product->getMedia('images');

            if ($images && count($images) !== 0) {
                foreach ($images as $index => $_image) {
                    $_image->delete();
                }
            }
            // Unasssign and delete from site
            $site->unassignProduct($product);
            $product->forceDelete();

            flash('Successfully deleted product forever.');
            return redirect()->route('admin.products.index');
        }

        flash('Invaild action or product does not exist.');
        return redirect()->route('admin.products.index');
    }
}
