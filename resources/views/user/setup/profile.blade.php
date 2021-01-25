{{-- Stored in /resources/views/office/dashboard/index.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Setup Account')
@section('page-class', 'user-setup')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false
    ])
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                @component('components.bootstrap.card', [
                    'id' => 'user-setup-card'
                ])
                    <div class="card-header">
                        {{ __('User Setup') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-10">
                                @component('components.forms.form',[
                                    'id'        => 'user-edit-profile-form',
                                    'action'    => route('user.setup.account.profile.store'),
                                    'method'    => 'POST',
                                    'confirmed' => true,
                                    'enctype'   => 'multipart/form-data'
                                ])
                                    @component('components.forms.file', [
                                        'id'        => 'profile_image',
                                        'name'      => 'profile_image',
                                        'label'     => __('Profile Image'),
                                        'accepts'   => [
                                            '.gif',
                                            '.png',
                                            '.jpg',
                                            '.jpeg'
                                        ]
                                    ])
                                        @error('profile_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    <div class="row">
                                        <div class="col-12">
                                            @if($profile_image = $user->getMedia('profile_image')->first())
                                                @component('components.forms.media.single_image', [
                                                    'path'  => $profile_image->getFullUrl('thumb'),
                                                    'route' => route('user.profile.media.delete',['image' => $profile_image->id])
                                                ])@endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    @component('components.forms.input', [
                                        'type'          => 'text',
                                        'name'          => 'company',
                                        'label'         => __('Company'),
                                        'value'         => old('company') ?? $user->company,
                                        'placeholder'   => 'MDRepTime, LLC'
                                    ])
                                        @error('company')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'          => 'text',
                                        'name'          => 'first_name',
                                        'label'         => 'First Name',
                                        'value'         => old('first_name')?? $user->first_name,
                                        'placeholder'   => __('John')
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
                                        'placeholder'   => 'Doe'
                                    ])
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'          => 'text',
                                        'name'          => 'address',
                                        'label'         => __('Address'),
                                        'value'         => old('address')?? $user->address,
                                        'placeholder'   => __('Address')
                                    ])
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'          => 'text',
                                        'name'          => 'address_2',
                                        'label'         => __('Apt/Unit'),
                                        'value'         => old('address_2')?? $user->address_2,
                                        'placeholder'   => __('Apt/Unit')
                                    ])
                                        @error('address_2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'          => 'text',
                                        'name'          => 'city',
                                        'label'         => __('City/Town'),
                                        'value'         => old('city')?? $user->city,
                                        'placeholder'   => __('City/Town')
                                    ])
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'          => 'text',
                                        'name'          => 'zipcode',
                                        'label'         => __('Zipcode'),
                                        'value'         => old('zipcode')?? $user->zipcode,
                                        'placeholder'   => __('Zipcode')
                                    ])
                                        @error('zipcode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.states', [
                                        'label' => 'State/Province',
                                        'name'  => 'state',
                                        'value' => old('state')?? $user->state
                                    ])
                                        @error('state')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.select', [
                                        'id'        => 'country',
                                        'name'      => 'country',
                                        'label'     => __('Country'),
                                        'options'   => $countries,
                                        'value'     => old('country') ?? $user->country,
                                        'withIndex' => true
                                    ])
                                        @error('country')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'      => 'tel',
                                        'name'      => 'phone',
                                        'inputmask' => "'mask': '+9(999)-999-9999'",
                                        'label'     => __('Phone'),
                                        'value'     => old('phone')?? $user->phone
                                    ])
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
                                    @component('components.forms.input', [
                                        'type'      => 'tel',
                                        'name'      => 'mobile_phone',
                                        'inputmask' => "'mask': '+9(999)-999-9999'",
                                        'label'     => __('Mobile Phone'),
                                        'value'     => old('mobile_phone')?? $user->mobile_phone
                                    ])
                                        @error('mobile_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endcomponent
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
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
{{--[/content]--}}