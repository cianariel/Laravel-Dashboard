{{-- Stored in /resources/views/admin/countries/state/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Add State')
@section('page-class', 'admin-countries-states-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Add State') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('State Information') }}</span></div>
                <div class="card-body pt-3 pb-3">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'POST', 'action' => route('admin.states.store', $country)])
                                @component('components.forms.input', ['type' => 'text', 'name' => 'code', 'label' => 'Code', 'value' => old('code'), 'placeholder' => 'CA'])
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'name', 'label' => 'Name', 'value' => old('name'), 'placeholder' => 'California'])
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'status', 'name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status') ])
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Create'])
                                            <a class="btn btn-secondary" href="{{ route('admin.states.index', $country) }}">{{ __('Cancel') }}</a>
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