<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Product;
use App\Models\System\Package;
use App\Models\System\ProductType;
use App\Rules\SanitizeHtml;

/**
 * Packages Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package App\Http\Controllers\Admin\Package
 */
class PackagesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
            $packages = $site->packages()->withTrashed()->paginate($perPage);
        } else {
            $packages = $site->packages()->paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Packages'      => ['path' => route('admin.packages.index'),          'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.packages.index', compact('breadcrumbs', 'packages', 'withTrashed', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = site();
        $interval_plans = Package::INTERVAL_PLANS;
        $status_types = Package::STATUS_TYPES;
        $featured_types = Package::FEATURED_TYPES;
        $package_types = Package::PACKAGE_TYPES;
        $trial_types = Package::TRIAL_TYPES;
        $products = $site->products()->where('status', Product::ACTIVE)->select(['stripe_product', 'label'])->cursor();
        $list = [];

        if ($products && $products->count() !== 0) {
            foreach ($products as $product) {
                $list[$product->stripe_product] = $product->label;
            }
        }

        $products = $list;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Packages'      => ['path' => route('admin.packages.index'),          'active' => false],
            'Add Package'   => ['path' => route('admin.packages.create'),         'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.packages.create', compact('breadcrumbs', 'products', 'trial_types', 'interval_plans', 'featured_types', 'status_types', 'package_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'type'              => ['required', 'string', Rule::in(Package::PACKAGE_TYPES)],
                'label'             => ['required', 'string', 'max:150', new SanitizeHtml],
                'description'       => ['required', 'string'],
                'media'             => ['file', 'nullable','image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'price'             => ['required', 'numeric', 'min:0'],
                'trial_enabled'     => ['required', 'string', Rule::in(Package::TRIAL_TYPES)],
                'interval'          => ['required', 'string', Rule::in(Package::INTERVAL_PLANS)],
                'featured'          => ['string', 'required', Rule::in(Package::FEATURED_TYPES)],
                'status'            => ['string', 'required', Rule::in(Package::STATUS_TYPES)],
                'stripe_product'    => ['required_if:type,'.Package::LINKED_PRODUCT, 'string', 'exists:system.products,stripe_product'],
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $stripe_plan = false;
            $stripe_error = '';
            $slug = unique_slug('package', strip_tags($request->input('label')));
            $name = $slug;
            $type = $request->input('type');

            //------------------------------
            // Stripe Plan
            //------------------------------

            if ($type == Package::SUBSCRIPTION) {
                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_plan = \Stripe\Plan::create([
                        'currency'          => 'usd',
                        'interval'          => $request->input('interval'),
                        'interval_count'    => 1,
                        'amount'            => cents(safe_float($request->input('price'))),
                        'product'           => ['name' => strip_tags($request->input('label'))],
                        'trial_period_days' => ($request->input('trial_enabled') == Package::TRIAL_ENABLED)? 15 : 0,
                        'active'            => ($request->input('status') == Package::ACTIVE)? true : false
                    ]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;
                }

                if (filled($stripe_error)) {
                    flash($stripe_error);
                    return back()->withInput();
                }
            } elseif ($type == Package::LINKED_PRODUCT) {
                $product = $site->products()
                                ->where('stripe_product', $request->input('stripe_product'))
                                ->select(['id', 'stripe_product'])
                                ->firstOrFail();

                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_plan = \Stripe\Plan::create([
                        'currency'          => 'usd',
                        'interval'          => $request->input('interval'),
                        'interval_count'    => 1,
                        'amount'            => cents(safe_float($request->input('price'))),
                        'product'           => $product->stripe_product,
                        'trial_period_days' => ($request->input('trial_enabled') == Package::TRIAL_ENABLED)? 15 : 0,
                        'active'            => ($request->input('status') == Package::ACTIVE)? true : false
                    ]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;
                }

                if (filled($stripe_error)) {
                    flash($stripe_error);
                    return back()->withInput();
                }
            }

            $package = new Package;
            $package->uuid = Str::uuid();
            $package->type = $type;
            $package->name = $name;
            $package->label = strip_tags($request->input('label'));
            $package->slug = $slug;
            $package->description = $request->input('description');
            $package->price = cents(safe_float($request->input('price')));
            $package->trial_enabled = $request->input('trial_enabled');
            $package->interval = $request->input('interval');
            $package->featured = $request->input('featured');
            $package->status = $request->input('status');

            if (filled($stripe_plan) && is_object($stripe_plan)) {
                $package->stripe_plan = $stripe_plan->id;
            }


            // File Uploads
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $package->addMedia($file)
                        ->toMediaCollection('images');
            }

            // Save
            $package->saveOrFail();
            $site->assignPackage($package);

            if ($type == Package::LINKED_PRODUCT) {
                $package->assignProduct($product);
            }

            flash('Successfully added package.');
            return redirect()->route('admin.packages.edit', $package);
        }

        flash('Invaild action.');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.packages.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Package::where('id', $id)->exists()) {
            $site = site();
            $package = $site->packages()->where('id', $id)->firstOrFail();
            $products = $site->products()->where('status', Product::ACTIVE)->select(['stripe_product', 'label'])->cursor();
            $list = [];
            $interval_plans = Package::INTERVAL_PLANS;
            $status_types = Package::STATUS_TYPES;
            $featured_types = Package::FEATURED_TYPES;
            $package_types = Package::PACKAGE_TYPES;
            $trial_types = Package::TRIAL_TYPES;

            foreach ($products as $product) {
                $list[$product->stripe_product] = $product->label;
            }

            $products = $list;
            $stripe_product = false;

            if ($package->type == Package::LINKED_PRODUCT && $package->hasProducts()) {
                $product = $package->products()->select(['stripe_product'])->firstOrFail();
                $stripe_product = $product->stripe_product;
            }

            $breadcrumbs = [
                'Dashboard'      => ['path' => admin_url(),                          'active' => false],
                'Packages'       => ['path' => route('admin.packages.index'),              'active' => false],
                'Edit Package'   => ['path' => route('admin.packages.edit', $package),     'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.packages.edit', compact('breadcrumbs', 'products', 'stripe_product', 'package', 'trial_types', 'interval_plans', 'featured_types', 'status_types', 'package_types'));
        }

        flash('Package does not exist.');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('PUT') && Package::where('id', $id)->exists()) {
            $rules = [
                'type'              => ['required', 'string', Rule::in(Package::PACKAGE_TYPES)],
                'label'             => ['required', 'string', 'max:150', new SanitizeHtml],
                'description'       => ['required', 'string'],
                'media'             => ['file', 'nullable','image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
                'price'             => ['required', 'numeric', 'min:0'],
                'trial_enabled'     => ['required', 'string', Rule::in(Package::TRIAL_TYPES)],
                'interval'          => ['required', 'string', Rule::in(Package::INTERVAL_PLANS)],
                'featured'          => ['string', 'required', Rule::in(Package::FEATURED_TYPES)],
                'status'            => ['string', 'required', Rule::in(Package::STATUS_TYPES)],
                'stripe_product'    => ['required_if:type,'.Package::LINKED_PRODUCT, 'string', 'exists:system.products,stripe_product'],
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $package = $site->packages()->where('id', $id)->firstOrFail();
            $stripe_plan = false;
            $stripe_error = '';

            // Retrieve Stripe Plan
            $stripe_plan = $package->stripe_plan;
            $type = $request->input('type');

            //------------------------------
            // Stripe Plan
            //------------------------------
            if ($type == Package::SUBSCRIPTION) {
                // Update Stripe Plan
                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_plan = \Stripe\Plan::update($stripe_plan, [
                        'interval'          => $request->input('interval'),
                        'amount'            => cents(safe_float($request->input('price'))),
                        'product'           => ['name' => strip_tags($request->input('label'))],
                        'trial_period_days' => ($request->input('trial_enabled') == Package::TRIAL_ENABLED)? 15 : 0,
                        'active'            => ($request->input('status') == Package::ACTIVE)? true : false
                    ]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;
                }

                // Handle stripe errors
                if (filled($stripe_error)) {
                    flash($stripe_error);
                    return back()->withInput();
                }

                // Remove any linked products
                if ($package->hasProducts()) {
                    $_product = $package->products()->select(['id'])->firstOrFail();
                    $package->unassignProduct($_product); // remove old product
                }
            } elseif ($type == Package::LINKED_PRODUCT) {
                $product = $site->products()
                                ->where('stripe_product', $request->input('stripe_product'))
                                ->select(['id', 'stripe_product'])
                                ->firstOrFail();

                // Update stripe plan.
                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_plan = \Stripe\Plan::update($stripe_plan, [
                        'product'           => $product->stripe_product,
                        'active'            => ($request->input('status') == Package::ACTIVE)? true : false
                    ]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;
                }

                // Handle stripe errors
                if (filled($stripe_error)) {
                    flash($stripe_error);
                    return back()->withInput();
                }

                if ($package->hasProduct($product)) {
                    // unassign  previous product
                    $_product = $package->products()->select(['id'])->firstOrFail();
                    $package->unassignProduct($_product); // remove old product
                }

                $package->assignProduct($product->id); // assign new product
            }

            if ($stripe_plan && is_object($stripe_plan)) {
                // Update package
                $package->type = $type;
                $package->label = strip_tags($request->input('label'));
                $package->description = $request->input('description');
                $package->price = cents(safe_float($request->input('price')));
                $package->trial_enabled = $request->input('trial_enabled');
                $package->interval = $request->input('interval');
                $package->featured = $request->input('featured');
                $package->status = $request->input('status');

                // File Uploads
                if ($request->hasFile('media')) {
                    $file = $request->file('media');
                    $package->addMedia($file)
                            ->toMediaCollection('images');
                }

                // Save
                $package->saveOrFail();

                flash('Successfully updated package');
                return redirect()->route('admin.packages.edit', $package);
            } else {
                flash('Failed to update stripe plan.');
                return back()->withInput();
            }
        }

        flash('Invaild action or package does not exist.');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Delete the specified media image from package.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @param int $image
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaImage(Request $request, $id, $image)
    {
        if ($request->isMethod('get') && Package::where('id', $id)->exists()) {
            $site = site();
            $package = $site->packages()->where('id', $id)->firstOrFail();
            $images  = $package->getMedia('images');

            foreach ($images as $index => $_image) {
                if ($index == $image) {
                    $_image->delete();
                    flash('Successfully deleted media image.');
                    return redirect()->route('admin.packages.edit', $package);
                }
            }
        }

        flash('Package or media resource does not exist');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Package::where('id', $id)->exists()) {
            $site = site();
            $package = $site->packages()->where('id', $id)->firstOrFail();
            $stripe_error = '';
            $package->status = Package::INACTIVE;
            $stripe_plan = $package->stripe_plan;
            $package->saveOrFail();

            //------------------------------
            // Stripe Plan
            //------------------------------
            // Update stripe plan.
            if (filled($stripe_plan)) {
                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_plan = \Stripe\Plan::update($stripe_plan, [
                        'active'            => false
                    ]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;
                }

                // Handle stripe errors
                if (filled($stripe_error)) {
                    flash($stripe_error);
                    return back()->withInput();
                }
            }

            if ($package->delete()) {
                flash('Successfully deleted package.');
                return redirect()->route('admin.packages.index');
            }
        }

        flash('Invaild action or packages does not exist.');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && Package::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $package = $site->packages()->withTrashed()->where('id', $id)->firstOrFail();

            if ($package->restore()) {
                flash('Successfully restored package from trash.');
                return redirect()->route('admin.products.edit', $package);
            }
        }

        flash('Invaild action or package does not exist.');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Delete forever the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && Package::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $package = $site->packages()->withTrashed()->where('id', $id)->firstOrFail();
            $products  = $package->products()->cursor();
            $stripe_id = $package->stripe_plan;

            // Unassign any products
            if ($products && $products->count() !== 0) {
                foreach ($products as $product) {
                    $package->unassignProduct($product);
                }
            }

            //-------------------------//
            // Stripe Product
            //-------------------------//
            if (filled($stripe_id)) {
                try {
                    \Stripe\Stripe::setApiKey(config('cashier.secret')); // stripe key

                    $stripe_plan = \Stripe\Plan::retrieve($stripe_id);
                    $stripe_plan->delete();
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $json = json_decode(json_encode($e->getJsonBody()), false);
                    $error = $json->error;
                    $stripe_error = $error->message;

                    if (filled($stripe_error)) {
                        flash($stripe_error);
                        return redirect()->route('admin.packages.index');
                    }
                }
            }

            // Delete Images
            $images = $package->getMedia('images');

            if ($images && count($images) !== 0) {
                foreach ($images as $index => $_image) {
                    $_image->delete();
                }
            }

            // Remove from site
            $site->unassignPackage($package);
            $package->forceDelete();

            flash('Successfully deleted package forever.');
            return redirect()->route('admin.packages.index');
        }

        flash('Invaild action or package does not exist.');
        return redirect()->route('admin.packagess.index');
    }
}
