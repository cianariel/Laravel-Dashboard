{{-- Stored in /resources/views/admin/groups/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Create Group')
@section('page-class', 'admin-groups-create')
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
                <span class="font-lg-size font-weight-bold">{{ __('Add Group') }}</span></a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-10">
                            <form class="form" action="{{ route('admin.groups.store') }}" method="POST">
                                @csrf
                                @component('components.forms.input', ['type' => 'text', 'name' => 'label', 'label' => 'Label', 'value' => old('label'), 'placeholder' => 'Group Name'])
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['name' => 'visible', 'label' => 'Visible', 'options' => $visible_types, 'value' => old('visible')])
                                    @error('visible')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Create'])
                                            <a class="btn btn-secondary" href="{{ route('admin.groups.index') }}">{{ __('Cancel') }}</a>
                                        @endcomponent
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection