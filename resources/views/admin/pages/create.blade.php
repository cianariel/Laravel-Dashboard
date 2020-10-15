{{-- Stored in /resources/views/admin/pages/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Add Page')
@section('page-class', 'admin-pages-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Add Page') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Page Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'POST', 'action' => route('admin.pages.store'), 'enctype' => 'multipart/form-data'])
                                @component('components.forms.input', ['id' => 'title', 'type' => 'text', 'name' => 'title', 'label' => 'Title', 'value' => old('title'), 'placeholder' => 'Page Title'])
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.editor', ['label' => '','name' => 'content', 'value' => old('content')])
                                    @error('content')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['label' => 'Excerpt', 'name' => 'excerpt', 'value' => old('excerpt')])
                                    @error('excerpt')
                                        <span class="invalid-feedback" role="alert">
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
                                @component('components.forms.input', ['id' => 'seo_title', 'label' => 'SEO Title', 'name' => 'seo_title', 'value' => old('seo_title')])
                                    @error('seo_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'meta_keywords', 'label' => 'Meta Keywords', 'name' => 'meta_keywords', 'value' => old('meta_keywords')])
                                    @error('meta_keywords')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'meta_description', 'label' => 'Meta Description', 'name' => 'meta_description', 'value' => old('meta_description')])
                                    @error('meta_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.multiselect', ['id' => 'meta_robots', 'name' => 'meta_robots', 'label' => 'Meta Robots', 'options' => $meta_robots, 'value' => old('meta_robots')])
                                    @error('meta_robots')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'template', 'name' => 'template', 'label' => 'Template', 'options' => $templates, 'value' => old('template')])
                                     @error('template')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'user', 'name' => 'user', 'label' => 'Author', 'options' => $users, 'value' => old('user'), 'withIndex' => true])
                                    @error('user')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'visible','name' => 'visible', 'label' => 'Visible', 'options' => $visible_types, 'value' => old('visible')])
                                     @error('visible')
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
                                            <a class="btn btn-secondary" href="{{ route('admin.pages.index') }}">{{ __('Cancel') }}</a>
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