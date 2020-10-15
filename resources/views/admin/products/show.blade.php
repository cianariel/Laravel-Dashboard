{{-- Stored in /resources/views/admin/products/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', $product->label)
@section('page-class', 'admin-products-show')
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
                <span class="font-lg-size font-weight-bold">{{ __('Product') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Product Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="row justify-content-center">
                                <div class="col-6">

                                </div>
                                <div class="col-6">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 p-0 mt-5">
                            @if($productAttributes = $product->productAttributes()->select(['id', 'label'])->cursor())
                                @if($productAttributes->count() !== 0)
                                    @component('components.elements.table', ['headers' => ['label', 'actions'], 'classes' => ['table-striped', 'table-hover']])
                                        @foreach($productAttributes as $productAttribute)
                                            <tr>
                                                <td>{{ __($productAttribute->label) }}</td>
                                                <td class="text-right">
                                                    @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.products.attributeDestroy', ['product'=>$product, 'attribute' => $productAttribute]), 'confirmed' => true, 'dialog_message' => 'Continue to remove attribute?'])
                                                        <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                                    @endcomponent
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endcomponent
                                @else
                                    <p class="card-text text-center">{{ __('No assigned attributes.') }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection