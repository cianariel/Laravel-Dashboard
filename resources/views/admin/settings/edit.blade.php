{{-- Stored in /resources/views/admin/settings/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Add Setting')
@section('page-class', 'admin-settings-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Setting') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Setting Information') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.settings.update', $setting), 'confirmed' => true])
                                @component('components.forms.input', ['id' => 'name', 'name' => 'name', 'type' => 'text', 'label' => 'Name', 'value' => old('name')?? ucwords(str_replace('_', ' ', (str_replace(env('APP_DOMAIN') . '_site_', '', $setting->key)))), 'help_text' => 'Alphabetic characters only.'])
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'type', 'name' => 'type', 'label' => 'Type', 'value' => $setting->type , 'options' => $input_types])
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.textarea', ['id' => 'options', 'name' => 'options', 'label' => 'Options', 'value' => (filled($setting->options)? implode(PHP_EOL, unserialize($setting->options)) : old('value')), 'help_text' => 'Each option must be seperated by a new line'])
                                    @error('options')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'group', 'name' => 'group', 'label' => 'Group', 'value' => old('group')?? $group, 'options' => $groups])
                                    @error('group')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'required', 'name' => 'required', 'label' => 'Required', 'value' => old('required')?? $setting->required, 'options' => $required_types])
                                    @error('required')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status', 'name' => 'status', 'label' => 'Status', 'value' => old('status')?? $setting->status, 'options' => $lock_types, 'help_text' => 'If locked can not be unlocked or deleted.'])
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.settings.manage') }}">{{ __('Cancel') }}</a>
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