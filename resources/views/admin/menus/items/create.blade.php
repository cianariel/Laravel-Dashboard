{{-- Stored in /resource/views/admin/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Add Menu Item')
@section('page-class', 'admin-menu-item-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Add Menu Item') }}</span></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Menu Item Information') }}</span></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'POST', 'action' => route('admin.menus.menu_items.store', $menu)])
                                @component('components.forms.select', ['name' => 'type', 'label' => 'Type', 'value' => old('type'), 'options' => $menu_item_types])
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['name' => 'parent_id', 'label' => 'Parent', 'value' => old('parent_id'), 'options' => $parents, 'withIndex' => true])
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'label', 'label' => 'Label', 'value' => old('label'), 'placeholder' => 'Home'])
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'title', 'label' => 'Title', 'value' => old('title'), 'placeholder' => 'Title'])
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'url', 'label' => 'URL', 'value' => old('url'), 'placeholder' => '/path', 'help_text' => 'Example: Full URL or relative path.'])
                                    @error('url')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['name' => 'target', 'label' => 'Target', 'value' => old('target'), 'options' => $target_types, 'help_text' => 'Target blank type opens in a new window or tab.'])
                                    @error('target')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'css_classes', 'label' => 'CSS Classes', 'value' => old('css_classes'), 'placeholder' => 'Optional', 'help_text' => 'Comma delimited without dot(.) in front of class name. Example: custom-menu-item,front-right-menu-item'])
                                    @error('css_classes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'number', 'name' => 'position', 'label' => 'Position', 'value' => old('position'), 'placeholder' => '0'])
                                    @error('position')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Create'])
                                            <a class="btn btn-secondary" href="{{ route('admin.menus.menu_items.index', $menu) }}">{{ __('Cancel') }}</a>
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