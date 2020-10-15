{{-- Stored in /resources/views/admin/currencies/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Currency')
@section('page-class', 'admin-currencies-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Currency') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Currency Details') }}</span></div>
                <div class="card-body pt-3 pb-3">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.currencies.update', $currency), 'confirmed' => true])
                                @component('components.forms.input', ['type' => 'text', 'name' => 'code', 'label' => 'Code', 'value' => old('code') ?? $currency->code, 'placeholder' => '', 'readonly' => true])
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'name', 'label' => 'Name', 'value' => old('name') ?? $currency->name , 'placeholder' => ''])
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'name_plural', 'label' => 'Name Plural', 'value' => old('name_plural') ?? $currency->name_plural, 'placeholder' => ''])
                                    @error('name_plural')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'symbol', 'label' => 'Symbol', 'value' => old('symbol') ?? $currency->symbol , 'placeholder' => ''])
                                    @error('symbol')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'symbol_native', 'label' => 'Symbol Native', 'value' => old('symbol_native') ?? $currency->symbol_native, 'placeholder' => ''])
                                    @error('symbol_native')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'number', 'name' => 'decimal_digits', 'label' => 'Decimal Digits', 'value' => old('decimal_digits') ?? $currency->decimal_digits, 'placeholder' => ''])
                                    @error('decimal_digits')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status') ?? $currency->status])
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.currencies.index') }}">{{ __('Cancel') }}</a>
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