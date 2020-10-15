{{-- Stored in /resources/views/admin/pages/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', $group->label . ' Settings')
@section('page-class', 'admin-settings-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit Settings') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __($group->label) }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.settings.group.update', ['group' => $group->name]), 'confirmed' => true, 'enctype' => 'multipart/form-data'])
                                @if(isset($settings) && count($settings) !== 0)
                                    @foreach($settings as $setting)
                                        @php
                                            $label = $setting->key;
                                            $label = str_replace(env('APP_DOMAIN'), '', $label);
                                            $label = str_replace($group->name, '', $label);
                                            $label = str_replace('_', ' ', $label);
                                            $label = str_replace('site', '', $label);
                                            $label = trim(ucwords($label));
                                            $name = str_replace(env('APP_DOMAIN').'_', '', $setting->key);
                                        @endphp
                                        @switch($setting->type)
                                            @case(App\Models\System\Setting::INPUT_FILE)
                                                @component('components.forms.file', ['id' => 'setting-' . $setting->id, 'label' => $label , 'name' => $name])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @if(filled($setting->value))
                                                    @component('components.forms.media.single_image', ['id' => $setting->id, 'path' => $setting->getMedia('files')->first()->getFullUrl(), 'route' => route('admin.settings.group.setting.clear', ['group' => $group->name, 'setting' => $setting->key])])
                                                    @endcomponent
                                                @endif
                                                @break
                                            @case(App\Models\System\Setting::INPUT_EMAIL)
                                                @component('components.forms.input', ['id' => 'setting-' . $setting->id, 'label' => $label , 'type' => 'email', 'name' => $name, 'value' => old('value')?? $setting->value])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_NUMBER)
                                                @component('components.forms.input', ['id' => 'setting-' . $setting->id, 'label' => $label , 'type' => 'number', 'name' => $name, 'value' => old('value')?? $setting->value])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break;
                                            @case(App\Models\System\Setting::INPUT_TEXT)
                                                @component('components.forms.input', ['id' => 'setting-' . $setting->id, 'label' => $label , 'type' => 'text', 'name' => $name, 'value' => old('value')?? $setting->value])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break;
                                            @case(App\Models\System\Setting::INPUT_TEXTAREA)
                                                @component('components.forms.textarea', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => $name, 'value' => old('value')?? $setting->value])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_SELECT)
                                                @component('components.forms.select', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => $name, 'value' => old('value')?? $setting->value, 'options' => unserialize($setting->options), 'withIndex' => (filled(unserialize($setting->options)))? (array_keys_exist(unserialize($setting->options), explode(',', $setting->value)) ? true : false) : false])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\System\Setting::INPUT_MULTISELECT)
                                                @component('components.forms.multiselect', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => $name, 'value' => old('value')?? $setting->value, 'options' => unserialize($setting->options), 'withIndex' => (filled(unserialize($setting->options)))? (array_keys_exist(unserialize($setting->options), explode(',', $setting->value)) ? true : false) : false])
                                                    @error($name)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                                @break
                                            @case(App\Models\Setting::INPUT_RESOURCE_SELECT)
                                                @php
                                                    $option = $setting->options;
                                                    $options = [];

                                                    if(filled($option)) {
                                                        $pieces = explode(',', $option);
                                                        $schemaTable = $pieces[0];
                                                        $column = $pieces[1];

                                                        if(strpos($column, ':') !== false) {

                                                            $pieces = explode(':', $column);
                                                            $index = $pieces[0];
                                                            $column = $pieces[1];

                                                            $results = DB::table($schemaTable)->select($index, $column)->cursor();

                                                            if($results->count() !== 0) {
                                                                foreach($results as $row) {
                                                                    $options[$row->$index] = $row->$column;
                                                                }
                                                            }
                                                        }


                                                    }
                                                @endphp
                                                @component('components.forms.select', ['id' => 'setting-' . $setting->id, 'label' => $label, 'name' => $name, 'value' => old('value')?? $setting->value, 'options' => $options, 'withIndex' => true])
                                                    @error($name)
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
                                            <a class="btn btn-secondary" href="{{ route('admin.settings.index') }}">{{ __('Cancel') }}</a>
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