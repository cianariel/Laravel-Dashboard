{{-- Stored in /resources/views/admin/pages/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Redirect')
@section('page-class', 'admin-redirects-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Redirect') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Redirect Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.redirects.update', $redirect), 'confirmed' => true])
                                @component('components.forms.input', ['id' => 'name', 'type' => 'text', 'name' => 'name', 'label' => 'Name', 'value' => old('name')?? $redirect->name, 'readonly' => true])
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['id' => 'path', 'type' => 'text', 'name' => 'path', 'label' => 'Path', 'placeholder' => env('APP_URL') . '/path' , 'value' => old('path')?? $redirect->path])
                                    @error('path')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['id' => 'redirect_path', 'type' => 'text', 'name' => 'redirect_path', 'label' => 'Redirect Path', 'placeholder' => '/redirect_path', 'value' => old('redirect_path')?? $redirect->redirect_path])
                                    @error('redirect_path')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'code', 'name' => 'code', 'label' => 'Code' ,'options' => $redirect_codes, 'value' => old('code')?? $redirect->code, 'withIndex' => true])
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.redirects.index') }}">{{ __('Cancel') }}</a>
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