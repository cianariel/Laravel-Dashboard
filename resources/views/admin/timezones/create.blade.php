{{-- Stored in /resources/views/admin/timezones/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Timezone')
@section('page-class', 'admin-timezones-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Create Timezone') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Zone Details') }}</span></div>
                <div class="card-body pt-3 pb-3">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'POST', 'action' => route('admin.timezones.store'), 'confirmed' => false])
                                @component('components.forms.input', ['type' => 'text', 'name' => 'zone', 'label' => 'Zone', 'value' => old('zone'), 'placeholder' => 'America/Los_Angeles', 'help_text'=>'List of Supported Timezones: <a target="_blank" class="fg-blue" href="https://www.php.net/manual/en/timezones.php">https://www.php.net/manual/en/timezones.php</a>'])
                                    @error('zone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status', 'name' => 'status', 'label' => 'Status', 'value' => old('status'), 'options' => $status_types])
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Create'])
                                            <a class="btn btn-secondary" href="{{ route('admin.timezones.index') }}">{{ __('Cancel') }}</a>
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