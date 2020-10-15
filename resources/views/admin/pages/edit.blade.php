{{-- Stored in /resources/views/admin/pages/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Page')
@section('page-class', 'admin-pages-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Page') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Page Information') }}</span> <a class="fg-blue pull-right" target="_blank" href="{{ site_url('page/' . $page->slug) }}">{{ __('Preview') }} <i class="fas fa-eye"></i></a></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['id' => 'form-pages-edit', 'method' => 'PUT', 'action' => route('admin.pages.update', $page), 'confirmed' => true, 'enctype' => 'multipart/form-data'])
                                @component('components.forms.input', ['id' => 'title', 'type' => 'text', 'name' => 'title', 'label' => 'Title', 'value' => old('title')?? $page->title, 'placeholder' => 'Page Title'])
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.editor', ['label' => '','name' => 'content', 'value' => old('content')?? $page->content])
                                    @error('content')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['label' => 'Excerpt', 'name' => 'excerpt', 'value' => old('excerpt')?? $page->excerpt])
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
                                @if($images = $page->getMedia('images'))
                                    @component('components.forms.media.slider', ['id'=> 'media-slider', 'name' => '', 'label' => 'Images', 'images' => $images, 'route' => 'admin.pages.media.delete', 'route_params' => ['page' => $page]])
                                    @endcomponent
                                @endif
                                @component('components.forms.input', ['id' => 'seo_title', 'label' => 'SEO Title', 'name' => 'seo_title', 'value' => old('seo_title')?? $page->seo_title])
                                    @error('seo_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'meta_keywords', 'label' => 'Meta Keywords', 'name' => 'meta_keywords', 'value' => old('meta_keywords')?? $page->meta_keywords])
                                    @error('meta_keywords')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'meta_description', 'label' => 'Meta Description', 'name' => 'meta_description', 'value' => old('meta_description')?? $page->meta_description])
                                    @error('meta_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.multiselect', ['id' => 'meta_robots', 'name' => 'meta_robots', 'label' => 'Meta Robots', 'options' => $meta_robots, 'value' => old('meta_robots')?? $page->meta_robots])
                                    @error('meta_robots')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'template', 'name' => 'template', 'label' => 'Template', 'options' => $templates, 'value' => old('template')?? $page->template])
                                     @error('template')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'user', 'name' => 'user', 'label' => 'Author', 'options' => $users, 'value' => old('user')?? $page->user_id, 'withIndex' => true])
                                    @error('user')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'visible','name' => 'visible', 'label' => 'Visible', 'options' => $visible_types, 'value' => old('visible')?? $page->visible])
                                     @error('visible')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status','name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status')?? $page->status])
                                     @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
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