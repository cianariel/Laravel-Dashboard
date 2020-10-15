{{-- Stored in /resources/views/admin/packages/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Add Package')
@section('page-class', 'admin-packages-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Add Package') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Package Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'POST', 'action' => route('admin.packages.store'), 'enctype' => 'multipart/form-data'])
                                @component('components.forms.select', ['id' => 'type','name' => 'type', 'label' => 'Type', 'options' => $package_types, 'value' => old('type')])
                                     @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'stripe_product','name' => 'stripe_product', 'label' => 'Product', 'options' => $products, 'value' => old('stripe_product'), 'required_if' => 'type', 'required_if_value' => App\Models\System\Package::LINKED_PRODUCT, 'withIndex' => true])
                                     @error('stripe_product')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['id' => 'label', 'type' => 'text', 'name' => 'label', 'label' => 'Label', 'value' => old('label'), 'placeholder' => 'Subscription Name'])
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
                                @component('components.forms.select', ['id' => 'trial_enabled', 'name' => 'trial_enabled', 'label' => 'Allow 15 Day Trial', 'options' => $trial_types, 'value' => old('trial_enabled')])
                                     @error('trial_enabled')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'interval','name' => 'interval', 'label' => 'Interval', 'options' => $interval_plans, 'value' => old('interval')])
                                     @error('interval')
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
                                            <a class="btn btn-secondary" href="{{ route('admin.packages.index') }}">{{ __('Cancel') }}</a>
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