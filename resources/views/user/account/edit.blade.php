{{-- Stored in /resources/views/user/account/edit.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Account')
@section('page-class', 'user-account')
@section('page_script')
    @component('components.elements.script', ['src' => mix('js/selectize.js')])@endcomponent
@endsection

{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => true
    ])
        <div class="row justify-content-center">
            <div class="col-12">
                @component('components.bootstrap.card', [
                    'id' => 'user-edit-profile-card'
                ])
                    <div class="card-header">
                        {{ __('My Account') }}
                    </div>
                    <div class="">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="left-panel">
                                    <div class="row">
                                        <div class="col-12">
                                            <img class="pull-left profile-image" src="{{$profile_img_url}}" />
                                            <div class="pull-left ml-4">
                                                <h4>{{$user->first_name}} {{$user->last_name}}</h4>
                                                <div>{{$user->company}} @if($user->getMetaField('drugs')) for @endif {{ is_array($user->getMetaField('drugs')) ? implode(", ", $user->getMetaField('drugs')) : $user->getMetaField('drugs') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <p><i class="far fa-envelope"></i> &nbsp; {{$user->email}}</p>
                                            <p><i class="fa fa-phone"></i> &nbsp; {{format_phone($user->phone)}}</p>
                                            <p><i class="fa fa-mobile-alt"></i> &nbsp; {{format_phone($user->mobile_phone)}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="right-panel">
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="change-password-btn" data-page="{{ old('is_password_page', false) ? 'password' : 'main' }}" class="btn btn-link pull-right" type="button">CHANGE PASSWORD</button>
                                        </div>
                                        <div class="col-12">
                                            @component('components.forms.form',[
                                                'id'        => 'user-change-status-form',
                                                'action'    => route('user.setup.account.status'),
                                                'method'    => 'POST',
                                                'dialog_message' => 'Are you sure you want to deactivate your account?',
                                                'confirmed' => true
                                            ])

                                                @component('components.forms.button', [
                                                    'id'        => 'change-status-btn',
                                                    'type'      => 'submit',
                                                    'name'      => 'submit-btn',
                                                    'label'     => 'DEACTIVATE MY ACCOUNT',
                                                    'classes'   => [
                                                        'btn',
                                                        'btn-link',
                                                        'pull-right'
                                                    ]
                                                ])
                                                @endcomponent

                                            @endcomponent
                                        </div>
                                    </div>

                                    @component('components.forms.form',[
                                        'id'        => 'user-edit-profile-form',
                                        'action'    => route('user.setup.account.profile.store'),
                                        'method'    => 'POST',
                                        'confirmed' => true,
                                        'classes'   =>  old('is_password_page', false) ? 'd-none' : ''
                                    ])
                                        <div class="row">
                                            <div class="col-md-6">
                                                @component('components.forms.input', [
                                                    'type'          => 'text',
                                                    'name'          => 'first_name',
                                                    'label'         => __('First Name'),
                                                    'value'         => old('first_name') ?? $user->first_name,
                                                    'vertical'      => true,
                                                    'attrs'         => ['required'=>'required']
                                                ])
                                                    @error('first_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                @component('components.forms.input', [
                                                    'type'          => 'text',
                                                    'name'          => 'last_name',
                                                    'label'         => __('Last Name'),
                                                    'value'         => old('last_name')?? $user->last_name,
                                                    'vertical'      => true,
                                                    'attrs'         => ['required'=>'required']
                                                ])
                                                    @error('last_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                @component('components.forms.input', [
                                                    'type'          => 'text',
                                                    'name'          => 'email',
                                                    'label'         => __('Email'),
                                                    'value'         => old('email')?? $user->email,
                                                    'vertical'      => true,
                                                    'attrs'         => ['type' => 'email', 'required'=>'required']
                                                ])
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                @component('components.forms.input', [
                                                    'type'          => 'text',
                                                    'name'          => 'company',
                                                    'label'         => __('Company'),
                                                    'value'         => old('company')?? $user->company,
                                                    'vertical'      => true
                                                ])
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent
                                            </div>
                                            <div class="col-6">
                                                @component('components.forms.input', [
                                                    'type'          => 'text',
                                                    'name'          => 'specialists',
                                                    'label'         => __('Specialists'),
                                                    'value'         => old('specialists')?? $user->getMetaField("specialists"),
                                                    'vertical'      => true
                                                ])

                                                    @error('specialists')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                @component('components.forms.input', [
                                                    'type'          => 'text',
                                                    'name'          => 'mobile_phone',
                                                    'label'         => __('Mobile Phone') . '<br><small>' . __('(Used for Text Notifications)') . '</small>',
                                                    'value'         => old('mobile_phone')?? $user->mobile_phone,
                                                    'vertical'      => true
                                                ])
                                                    @error('mobile_phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                @component('components.forms.select', [
                                                    'id'        => 'drugs',
                                                    'name'      => 'drugs[]',
                                                    'error_name'=> 'drugs',
                                                    'label'     => __('Products'),
                                                    'options'   => old('drugs') ?? $user->getMetaField('drugs'),
                                                    'value'     => old('drugs') ?? $user->getMetaField('drugs'),
                                                    'withIndex' => false,
                                                    'classes'   => ['class' => 'selectize-multiple'],
                                                    'attrs'     => ['multiple' => ''],
                                                    'vertical'  => true
                                                ])
                                                    @error('drugs')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <p>Please select which products you represent so that medical offfices can easily request samples</p>
                                                @endcomponent
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-md-8 offset-md-4">
                                                @component('components.forms.button', [
                                                    'id'        => 'submit-btn',
                                                    'type'      => 'submit',
                                                    'name'      => 'submit-btn',
                                                    'label'     => 'Update',
                                                    'classes'   => [
                                                        'btn',
                                                        'btn-primary'
                                                    ]
                                                ])
                                                    @component('components.elements.link', [
                                                        'href'  => route('user.dashboard'),
                                                        'classes'   => [
                                                            'btn',
                                                            'btn-secondary'
                                                        ]
                                                    ])
                                                        {{ __('Cancel') }}
                                                    @endcomponent
                                                @endcomponent
                                            </div>
                                        </div>
                                    @endcomponent

                                    @component('components.forms.form',[
                                        'id'        => 'user-change-password-form',
                                        'action'    => route('user.setup.account.password.change'),
                                        'method'    => 'POST',
                                        'classes'   => old('is_password_page', false) ? '' : 'd-none'
                                    ])
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3">
                                                @component('components.forms.hidden', [
                                                    'name'          => 'is_password_page',
                                                    'value'         => '1',
                                                ])
                                                @endcomponent

                                                @component('components.forms.password', [
                                                    'id'            => 'current-password',
                                                    'name'          => 'current_password',
                                                    'label'         => __('Current Password'),
                                                    'value'         => '',
                                                    'vertical'      => true,
                                                    'attrs'         => ['required'=>'required'],
                                                    'not_confirmed' => true
                                                ])
                                                    @error('current_password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                @component('components.forms.password', [
                                                    'id'            => 'password',
                                                    'name'          => 'password',
                                                    'label'         => __('Password'),
                                                    'value'         => '',
                                                    'vertical'      => true,
                                                    'attrs'         => ['required'=>'required'],
                                                ])
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                @endcomponent

                                                <div class="text-center">
                                                    @component('components.forms.button', [
                                                        'id'        => 'submit-btn',
                                                        'type'      => 'submit',
                                                        'name'      => 'submit-btn',
                                                        'label'     => 'Update',
                                                        'classes'   => [
                                                            'btn',
                                                            'btn-primary'
                                                        ]
                                                    ])
                                                        @component('components.elements.link', [
                                                            'href'  => '#',
                                                            'id'    => 'cancel-change-password',
                                                            'classes'   => [
                                                                'btn',
                                                                'btn-secondary'
                                                            ]
                                                        ])
                                                            {{ __('Cancel') }}
                                                        @endcomponent
                                                    @endcomponent
                                                </div>

                                            </div>
                                        </div>

                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent

    @section('scripts_end')
        <script>
            $(document).ready(function(){
                $("#drugs").selectize({
                    plugins: ['remove_button', 'restore_on_backspace'],
                    delimiter: ',',
                    persist: false,
                    create: function (input) {
                        return {value: input, text: input};
                    }
                });

                $("#change-password-btn").click(function(){
                    // Shows change password if this is main form
                    if($(this).data('page') == 'main'){
                        $("#user-edit-profile-form").removeClass('d-none');
                        $("#user-change-password-form").removeClass('d-none');

                        $("#user-edit-profile-form").hide();
                        $("#user-change-password-form").show();
                        $(this).data('page', 'password');
                    }
                })

                $("#cancel-change-password").click(function(){
                    $("#user-edit-profile-form").removeClass('d-none');
                    $("#user-change-password-form").removeClass('d-none');

                    $("#user-change-password-form").hide();
                    $("#user-edit-profile-form").show();
                    $("#change-password-btn").data('page', 'main');
                })
            })
        </script>
    @endsection
@endsection