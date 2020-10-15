<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product\Attributes;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\ProductAttribute;
use App\Rules\SanitizeHtml;

/**
 * Admin Product Attributes Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package App\Http\Controllers\Admin\Product\Attributes
 */
class ProductAttributesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site();
        $query = $request->query();
        $perPage = 10;

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
        }

        $breadcrumbs = [
            'Dashboard'                 => ['path' => admin_url(),                              'active' => false],
            'Products'                  => ['path' => route('admin.products.index'),                  'active' => false],
            'Attributes'                => ['path' => route('admin.product_attributes.index'),        'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        // Attributes
        $productAttributes = $site->productAttributes()->paginate($perPage);

        return view('admin.products.attributes.index', compact('breadcrumbs', 'productAttributes', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = ProductAttribute::STATUS_TYPES;

        $breadcrumbs = [
            'Dashboard'                 => ['path' => admin_url(),                              'active' => false],
            'Products'                  => ['path' => route('admin.products.index'),                  'active' => false],
            'Attributes'                => ['path' => route('admin.product_attributes.index'),        'active' => false],
            'Add Attribute'             => ['path' => route('admin.product_attributes.create'),       'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.products.attributes.create', compact('status_types', 'breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('POST')) {
            $site = site();

            $rules = [
                'label'  => ['required', 'string', 'max:150', new SanitizeHtml],
                'value'  => ['required', 'string', new SanitizeHtml],
                'status' => ['required', 'string', Rule::in(ProductAttribute::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $name = str::slug(strip_tags($request->input('label')));

            if ($site->productAttributes()->where('name', $name)->exists()) {
                flash('Product attribute must be a unique label');
                return back()->withInput();
            }

            $productAttribute = new ProductAttribute;
            $productAttribute->name = $name;
            $productAttribute->label = strip_tags($request->input('label'));
            $productAttribute->value = serialize(explode(PHP_EOL, strip_tags($request->input('value'))));
            $productAttribute->status = $request->input('status');
            $productAttribute->saveOrFail();

            $site->assignProductAttribute($productAttribute);

            flash('Successfully added product attribute.');
            return redirect()->route('admin.product_attributes.edit', $productAttribute);
        }

        flash('Invaild Action.');
        return redirect()->route('admin.product_attributes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.product_attributes.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (ProductAttribute::where('id', $id)->exists()) {
            $site = site();
            $status_types = ProductAttribute::STATUS_TYPES;
            $productAttribute = $site->productAttributes()->where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'                 => ['path' => admin_url(),                                              'active' => false],
                'Products'                  => ['path' => route('admin.products.index'),                                  'active' => false],
                'Attributes'                => ['path' => route('admin.product_attributes.index'),                        'active' => false],
                'Edit Attribute'            => ['path' => route('admin.product_attributes.edit', $productAttribute),      'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.products.attributes.edit', compact('status_types', 'breadcrumbs', 'productAttribute'));
        }

        flash('Product attribute does not exist.');
        return redirect()->route('admin.product_attributes.index');
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
        if ($request->isMethod('put') && ProductAttribute::where('id', $id)->exists()) {
            $site = site();
            $productAttribute = $site->productAttributes()->where('id', $id)->firstOrFail();

            $rules = [
                'label'  => ['required', 'string', 'max:150', new SanitizeHtml],
                'value'  => ['required', 'string', new SanitizeHtml],
                'status' => ['required', 'string', Rule::in(ProductAttribute::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $name = str::slug(strip_tags($request->input('label')));

            if ($name != $productAttribute->name) {
                if ($site->productAttributes()->where('name', $name)->exists()) {
                    flash('Product attribute must be a unique label');
                    return back()->withInput();
                }
            }

            $productAttribute->name = $name;
            $productAttribute->label = strip_tags($request->input('label'));
            $productAttribute->value = serialize(explode(PHP_EOL, strip_tags($request->input('value'))));
            $productAttribute->status = $request->input('status');
            $productAttribute->saveOrFail();

            flash('Successfully updated product attribute');
            return redirect('product_attributes.edit', $productAttribute);
        }

        flash('Invaild action or product attribute does not exist.');
        return redirect()->route('admin.product_attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && ProductAttribute::where('id', $id)->exists()) {
            $site = site();
            $productAttribute = $site->productAttributes()->where('id', $id)->firstOrFail();

            // unassign and delete from site
            $site->unassignProductAttribute($productAttribute);
            $productAttribute->delete();

            flash('Successfully deleted product attribute.');
            return redirect()->route('admin.product_attributes.index');
        }

        flash('Invaild action or product attribute does not exist.');
        return redirect()->route('admin.product_attributes.index');
    }
}
