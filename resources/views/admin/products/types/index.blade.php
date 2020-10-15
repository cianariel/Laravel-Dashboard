{{-- Stored in /resources/views/admin/products/attributes/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Product Types')
@section('page-class', 'admin-product-types-index')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Product Types') }}</span> <a class="font-lg-size" href="{{ route('admin.product_types.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Product Types') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($productTypes) && count($productTypes) !== 0)
                        @component('components.elements.table_data', ['headers' => ['label', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $productTypes, 'query' => $query?? []])
                            @foreach($productTypes as $productType)
                                <tr data-redirect-url="{{ route('admin.product_types.show', $productType) }}">
                                    <td>{{ ucwords($productType->label)}}</td>
                                    <td>
                                        @if($productType->status == App\Models\System\ProductType::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\ProductType::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\ProductType::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.product_types.destroy', $productType), 'confirmed' => true, 'dialog_message' => 'Continue to delete product type?'])
                                            <a class="fg-blue" href="{{ route('admin.product_types.edit', $productType) }}"><i class="fas fa-edit"></i></a>
                                            <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No product types found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection