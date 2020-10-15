{{-- Stored in /resources/views/admin/products/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Add Product')
@section('page-class', 'admin-products-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Add Product') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Product Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'POST', 'action' => route('admin.products.store'), 'enctype' => 'multipart/form-data'])
                                @component('components.forms.select', ['id' => 'type','name' => 'type', 'label' => 'Type', 'options' => $types, 'value' => old('type'), 'withIndex' => true])
                                     @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['id' => 'label', 'type' => 'text', 'name' => 'label', 'label' => 'Label', 'value' => old('label'), 'placeholder' => 'CRM Single User'])
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.editor', ['label' => '','name' => 'description', 'value' => old('description')])
                                    @error('description')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.file', ['label' => 'Media', 'name' => 'media'])
                                    @error('media')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input_group', ['prepend' => '$', 'type' => 'text', 'name' => 'price', 'label' => 'Price', 'value' => old('price'), 'placeholder' => '1.00'])
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'tags', 'label' => 'Tags', 'name' => 'tags', 'value' => old('tags'), 'help_text' => 'Each tag must be seperated by a new line'])
                                    @error('tags')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'featured','name' => 'featured', 'label' => 'Featured', 'options' => $featured_types, 'value' => old('featured')])
                                     @error('featured')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status','name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status')])
                                     @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Create'])
                                            <a class="btn btn-secondary" href="{{ route('admin.products.index') }}">{{ __('Cancel') }}</a>
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