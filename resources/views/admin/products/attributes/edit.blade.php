{{-- Stored in /resources/views/admin/products/attributes/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Attribute')
@section('page-class', 'admin-product-attributes-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Attribute') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Attribute Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.product_attributes.update', $productAttribute)])
                                @component('components.forms.input', ['id' => 'label', 'type' => 'text', 'name' => 'label', 'label' => 'Label', 'value' => old('label')?? $productAttribute->label, 'placeholder' => 'Attribute'])
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'value', 'name' => 'value', 'label' => 'Value', 'value' => old('value')?? filled($productAttribute->value)? implode(PHP_EOL, unserialize($productAttribute->value)) : '', 'help_text' => 'Each attribute value must be seperated by a new line'])
                                    @error('value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status','name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status')?? $productAttribute->status])
                                     @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.product_attributes.index') }}">{{ __('Cancel') }}</a>
                                        @endcomponent
                                    </div>
                                </div>
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection