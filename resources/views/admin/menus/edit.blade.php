{{-- Stored in /resource/views/admin/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Menu')
@section('page-class', 'admin-menu-edit')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row h-100 justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Edit Menu') }}</span></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Menu Information') }}</span></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.menus.update', $menu)])
                                @component('components.forms.select', ['name' => 'type', 'label' => 'Type', 'value' => old('type')?? $menu->type, 'options' => $menu_types])
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'label', 'label' => 'Label', 'value' => old('label')?? $menu->label, 'placeholder' => 'Primary Menu'])
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['name' => 'location', 'label' => 'Location', 'value' => old('location')?? $menu->location, 'options' => $location_types])
                                    @error('location')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['name' => 'css_classes', 'label' => 'CSS Classes', 'value' => old('css_classes')?? $menu->css_classes, 'help_text' => 'Comma delimited without dot(.) in front of class name. Example: custom-menu,front-right-menu'])
                                    @error('css_classes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['name' => 'status', 'label' => 'Status', 'value' => old('status')?? $menu->status, 'options' => $status_types])
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.menus.index') }}">{{ __('Cancel') }}</a>
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