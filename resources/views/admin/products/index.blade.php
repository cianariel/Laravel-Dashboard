{{-- Stored in /resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Products')
@section('page-class', 'admin-products-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Products') }}</span> <a class="font-lg-size" href="{{ route('admin.products.create') }}"><i class="fas fa-plus-circle"></i></a>
                <div class="d-inline-block pull-right">
                    <span>{{ __('Manage') }}:</span>
                    <a href="{{ route('admin.product_types.index') }}">{{ __('Types') }}</a>
                    <span>|</span>
                    <a href="{{ route('admin.product_attributes.index') }}">{{ __('Attributes') }}</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Products') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($products) && count($products) !== 0)
                        @component('components.elements.table_data', ['headers' => ['label', 'type', 'featured', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $products, 'query' => $query?? []])
                            @foreach($products as $product)
                                <tr data-redirect-url="{{ route('admin.products.show', $product) }}">
                                    <td>{{ ucwords($product->label) }}</td>
                                    <td>{{ ucwords($product->type) }}</td>
                                    <td>
                                        @if($product->featured == App\Models\System\Product::FEATURED)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Product::FEATURED)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Product::NOT_FEATURED)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->status == App\Models\System\Product::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Product::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Product::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($product->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.products.restore', ['id' => $product]), 'confirmed' => true, 'dialog_message' => 'Continue to restore product?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.products.delete.trashed', ['id' => $product]), 'confirmed' => true, 'dialog_message' => 'Continue to delete product forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.products.destroy', $product), 'confirmed' => true, 'dialog_message' => 'Continue to delete product?'])
                                                <a class="fg-blue" href="{{ route('admin.products.edit', $product) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No products found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection