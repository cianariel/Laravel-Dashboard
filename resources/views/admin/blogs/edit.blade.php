{{-- Stored in /resources/views/admin/blogs/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Blog')
@section('page-class', 'admin-blogs-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Blog') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Blog Information') }}</span> <a target="_blank" class="fg-blue pull-right" href="{{ site_url('blog/' . $blog->slug) }}">{{ __('View Blog') }} <i class="fas fa-eye"></i></a></div>
                <div class="card-body p-0 pt-3 pb-3">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.blogs.update', $blog), 'confirmed' => true])
                                @component('components.forms.input', ['id' => 'title', 'type' => 'text', 'name' => 'title', 'label' => 'Title', 'value' => old('title')?? $blog->title, 'placeholder' => 'Blog Name'])
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'visible','name' => 'visible', 'label' => 'Visible', 'options' => $visible_types, 'value' => old('visible')?? $blog->visible])
                                     @error('visible')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status','name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status')?? $blog->status])
                                     @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.blogs.index') }}">{{ __('Cancel') }}</a>
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