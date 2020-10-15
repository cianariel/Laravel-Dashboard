{{-- Stored in /resources/views/admin/blogs/settings/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Blog Settings')
@section('page-class', 'admin-blogs-settings-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Blog Settings') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('') }}</span></div>
                <div class="card-body pt-3 pb-3">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.blogs.settings.update', $blog), 'confirmed' => true])
                                @if(isset($settings) && count($settings) !== 0)
                                    @foreach($settings as $setting)
                                        @php
                                            $label = $setting->key;
                                            $label = str_replace(env('APP_DOMAIN'), '', $label);
                                            $label = str_replace($blog->name, '', $label);
                                            $label = str_replace('_', ' ', $label);
                                            $label = str_replace('blog', '', $label);
                                            $label = trim(ucwords($label));
                                        @endphp
                                        @switch($setting->type)
                                            @case(App\Models\System\Setting::INPUT_FILE)
                                                @component('components.forms.file', ['id' => 'setting-' . $setting->id, 'label' => $label , 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key)])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    @if(filled($setting->value))
                                                        @component('components.forms.media.single_image', ['id' => $setting->id, 'path' => $setting->value, 'route' => route('admin.blogs.settings.clear', ['blog' => $blog, 'setting' => $setting->key])])
                                                        @endcomponent
                                                    @endif
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_EMAIL)
                                                @component('components.forms.input', ['id' => 'setting-' . $setting->id, 'label' => $label , 'type' => 'email', 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key), 'value' => $setting->value])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_NUMBER)
                                                @component('components.forms.input', ['id' => 'setting-' . $setting->id, 'label' => $label , 'type' => 'number', 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key), 'value' => $setting->value])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break;
                                            @case(App\Models\System\Setting::INPUT_TEXT)
                                                @component('components.forms.input', ['id' => 'setting-' . $setting->id, 'label' => $label , 'type' => 'text', 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key), 'value' => $setting->value])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break;
                                            @case(App\Models\System\Setting::INPUT_TEXTAREA)
                                                @component('components.forms.textarea', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key), 'value' => $setting->value])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_SELECT)
                                                @component('components.forms.select', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key), 'value' => $setting->value, 'options' => unserialize($setting->options)])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_MULTISELECT)
                                                @component('components.forms.multiselect', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => str_replace(env('APP_DOMAIN').'_', '', $setting->key), 'value' => $setting->value, 'options' => unserialize($setting->options)])
                                                    @error($setting->key)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                        @endswitch
                                    @endforeach
                                @endif
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