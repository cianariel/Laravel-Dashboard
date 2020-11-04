@extends('office.layouts.master')
@section('html-title', __('Send a new staff invite.'))
@section('page-class', 'office-staff-create')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => true
    ])
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="text-right">
                </div>
                @component('components.bootstrap.card', [
                    'id'        => 'office-staff-members-card',
                    'classes'   => [
                        'mt-3'
                    ]
                ])
                    <div class="card-header">
                        <h5 class="card-title">{{ __('Send new staff invitiation ') }}</h5>
                    </div>
                    <div class="card-body">
                        @component('components.forms.form', [
                            'id'                => 'office-staff-members-invite-form',
                            'action'            => route('office.staff.store'),
                            'method'            => 'POST',
                            'confirmed'         => true,
                            'dialog_message'    => __('Are you sure you want to send invite?')
                        ])
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'name'          => 'first_name',
                                'label'         => __('First Name'),
                                'value'         => old('first_name'),
                                'placeholder'   => __('Enter first name')
                            ])
                                @error('first_name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'name'          => 'last_name',
                                'label'         => __('Last Name'),
                                'value'         => old('last_name'),
                                'placeholder'   => __('Enter last name')
                            ])
                                @error('last_name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'email',
                                'name'          => 'email',
                                'label'         => __('Email'),
                                'value'         => old('email'),
                                'placeholder'   => __('Enter email address')
                            ])
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            <div class="col-12 offset-md-4">
                                @component('components.forms.button',[
                                    'id'        => 'submit-btn',
                                    'type'      => 'submit',
                                    'name'      => 'submit-btn',
                                    'label'     => __('Send Invite'),
                                    'classes'   => [
                                        'btn',
                                        'btn-primary'
                                    ]
                                ])
                                @endcomponent
                            </div>
                        @endcomponent
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection