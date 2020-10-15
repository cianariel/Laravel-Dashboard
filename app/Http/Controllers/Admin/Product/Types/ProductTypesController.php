<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product\Types;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\ProductType;
use App\Rules\SanitizeHtml;

/**
 * Admin Product Types Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Product
 */
class ProductTypesController extends AdminController
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
            'Types'                     => ['path' => route('admin.product_types.index'),             'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        $productTypes = $site->productTypes()->paginate($perPage);

        return view('admin.products.types.index', compact('breadcrumbs', 'productTypes', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = ProductType::STATUS_TYPES;

        $breadcrumbs = [
            'Dashboard'                 => ['path' => admin_url(),                              'active' => false],
            'Products'                  => ['path' => route('admin.products.index'),                  'active' => false],
            'Types'                     => ['path' => route('admin.product_types.index'),             'active' => false],
            'Add Type'                  => ['path' => route('admin.product_types.create'),            'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.products.types.create', compact('breadcrumbs', 'status_types'));
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
                'label'     => ['required', 'string', 'max:100', new SanitizeHtml],
                'status'    => ['required', 'string', Rule::in(ProductType::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $name = str::slug(strip_tags($request->input('label')));

            if ($site->productTypes()->where('name', $name)->exists()) {
                flash('Product type must be a unique label');
                return back()->withInput();
            }

            $productType = new ProductType;
            $productType->name = $name;
            $productType->label = strip_tags($request->input('label'));
            $productType->status = $request->input('status');
            $productType->saveOrFail();

            $site->assignProductType($productType);

            flash('Successfully created product type.');
            return redirect()->route('admin.product_types.edit', $productType);
        }

        flash('Invaild action.');
        return redirect()->route('admin.product_types.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.product_types.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (ProductType::where('id', $id)->exists()) {
            $site = site();
            $productType = $site->productTypes()->where('id', $id)->firstOrFail();

            $status_types = ProductType::STATUS_TYPES;

            $breadcrumbs = [
                'Dashboard'                 => ['path' => admin_url(),                                          'active' => false],
                'Products'                  => ['path' => route('admin.products.index'),                              'active' => false],
                'Types'                     => ['path' => route('admin.product_types.index'),                         'active' => false],
                'Edit Type'                 => ['path' => route('admin.product_types.edit', $productType),            'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);
            return view('admin.products.types.edit', compact('breadcrumbs', 'status_types', 'productType'));
        }

        flash('Product type does not exist.');
        return redirect()->route('admin.product_types.index');
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
        if ($request->isMethod('put') && ProductType::where('id', $id)->exists()) {
            $site = site();
            $productType = $site->productTypes()->where('id', $id)->firstOrFail();

            $rules = [
                'label'     => ['required', 'string', 'max:100', new SanitizeHtml],
                'status'    => ['required', 'string', Rule::in(ProductType::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $name = str::slug(strip_tags($request->input('label')));

            if ($name != $productAttribute->name) {
                if ($site->productTypes()->where('name', $name)->exists()) {
                    flash('Product type must be a unique label');
                    return back()->withInput();
                }
            }

            $productType->name = $name;
            $productType->label = strip_tags($request->input('label'));
            $productType->status = $request->input('status');
            $productType->saveOrFail();

            flash('Successfully updated product type.');
            return redirect()->route('admin.product_types.edit', $productType);
        }

        flash('Invaild action or product type does not exist.');
        return redirect()->route('admin.product_types.index');
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
        if ($request->isMethod('delete') && ProductType::where('id', $id)->exists()) {
            $site = site();
            $productType = $site->productTypes()->where('id', $id)->firstOrFail();

            // unassign and delete from site
            $site->unassignProductType($productType);
            $productType->delete();

            flash('Successfully deleted product type.');
            return redirect()->route('admin.product_types.index');
        }

        flash('Invaild action or product type does not exist.');
        return redirect()->route('admin.product_types.index');
    }
}
