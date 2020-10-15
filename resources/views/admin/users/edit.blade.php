{{-- Stored in /resources/views/admin/users/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit User')
@section('page-class', 'admin-users-edit')
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
                <span class="font-lg-size font-weight-bold">{{ __('Edit User') }}</a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('User Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.users.update', $user), 'confirmed' => true])
                                @component('components.forms.input', ['type' => 'email', 'name' => 'email', 'label' => 'Email', 'value' => old('email')?? $user->email, 'placeholder' => 'user@geekbidz.com', 'readonly' => true])
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.password', ['type' => 'password', 'name' => 'password', 'label' => 'Password', 'value' => '', 'placeholder' => 'Enter a password' ])
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'company', 'label' => 'Company', 'value' => old('company') ?? $user->company, 'placeholder' => 'GeekBidz, LLC'])
                                    @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'first_name', 'label' => 'First Name', 'value' => $user->first_name, 'placeholder' => 'John'])
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'last_name', 'label' => 'Last Name', 'value' => $user->last_name, 'placeholder' => 'Doe'])
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'tel', 'name' => 'phone', 'inputmask' => "'mask': '+9(999)-999-9999'", 'label' => 'Phone', 'value' => $user->phone])
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'tel', 'name' => 'mobile_phone', 'inputmask' => "'mask': '+9(999)-999-9999'", 'label' => 'Mobile Phone', 'value' => $user->mobile_phone])
                                    @error('mobile_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @if(!$user->hasRole(App\Models\System\Role::SUPER_ADMIN))
                                    @component('components.forms.select', ['name' => 'role', 'label' => 'Role', 'options' => $roles, 'value' => $user->roles()->first()->name])
                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                @endif
                                @if(!$user->hasRole(App\Models\System\Role::SUPER_ADMIN))
                                    @component('components.forms.select', ['name' => 'status', 'label' => 'Status', 'options' => $status_types, 'value' => old('status')])
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                @endif
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary'], 'label' => 'Update'])
                                            <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">{{ __('Cancel') }}</a>
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