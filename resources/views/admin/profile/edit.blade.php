{{-- Stored in /resources/views/admin/profile/edit.php --}}
{{-- Stored in /resources/views/admin/pages/create.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Profile')
@section('page-class', 'admin-profile-edit')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center position-relative">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Edit Profile') }}</a></span>
            </div>
            {{--[form-card-deck]--}}
            @component('components.forms.form', ['method' => 'PUT', 'action' => route('admin.profile.update'), 'confirmed' => true, 'enctype' => 'multipart/form-data'])
                {{--[General Information]--}}
                <div class="card mb-2">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('General') }}</span></div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                @component('components.forms.file', ['id' => 'profile_image', 'name' => 'profile_image', 'label' => 'Profile Image'])
                                    @error('profile_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @if($profile_image = $user->getMedia('profile_image')->first())
                                    @component('components.forms.media.single_image', ['path' => $profile_image->getFullUrl('thumb'), 'route' => route('admin.profile.media.delete', ['image' => $profile_image->id])])
                                    @endcomponent
                                @endif
                                @component('components.forms.textarea', ['id' => 'profile','label' => 'About Me' , 'name' => 'profile', 'value' => old('profile')?? $user->profile, 'help_text' => 'Plain text only. No HTML'])
                                    @error('profile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'username', 'label' => 'Username', 'value' => old('username') ?? $user->username, 'readonly'=>true, 'help_text' => 'Username can not be changed.'])
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'email', 'name' => 'email', 'label' => 'Email', 'value' => old('email')?? $user->email, 'placeholder' => 'user@geekbidz.com'])
                                    @error('email')
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
                                @component('components.forms.input', ['type' => 'text', 'name' => 'first_name', 'label' => 'First Name', 'value' => old('first_name')?? $user->first_name, 'placeholder' => 'John'])
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'last_name', 'label' => 'Last Name', 'value' => old('last_name')?? $user->last_name, 'placeholder' => 'Doe'])
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'address', 'label' => 'Address', 'value' => old('address')?? $user->address, 'placeholder' => 'Address'])
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'address_2', 'label' => 'Apt/Unit', 'value' => old('address_2')?? $user->address_2, 'placeholder' => 'Apt/Unit'])
                                    @error('address_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'city', 'label' => 'City/Town', 'value' => old('city')?? $user->city, 'placeholder' => 'City/Town'])
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'zipcode', 'label' => 'Zipcode', 'value' => old('zipcode')?? $user->zipcode, 'placeholder' => 'City/Town'])
                                    @error('zipcode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.states', ['label' => 'State/Province', 'name' => 'state', 'value' => old('state')?? $user->state])
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.select', ['id' => 'country', 'name' => 'country',  'label' => 'Country', 'options' => $countries, 'value' => old('country') ?? $user->country, 'withIndex' => true])
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'tel', 'name' => 'phone', 'inputmask' => "'mask': '+9(999)-999-9999'", 'label' => 'Phone', 'value' => old('phone')?? $user->phone])
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'tel', 'name' => 'mobile_phone', 'inputmask' => "'mask': '+9(999)-999-9999'", 'label' => 'Mobile Phone', 'value' => old('mobile_phone')?? $user->mobile_phone])
                                    @error('mobile_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
                {{--[/general-information]--}}
                {{--[social-media]--}}
                <div class="card mb-2">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Social Media') }}</span></div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                @component('components.forms.input', ['type' => 'text', 'name' => 'skype', 'label' => 'Skype', 'value' => old('skype') ?? $user->getMetaField('skype')?? '', 'placeholder' => '@workvision'])
                                    @error('skype')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'linkedin', 'label' => 'Linkedin', 'value' => old('linkedin') ?? $user->getMetaField('linkedin')?? '', 'placeholder' => 'Linkedin URL'])
                                    @error('linkedin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'facebook', 'label' => 'Facebook', 'value' => old('facebook') ?? $user->getMetaField('facebook')?? '', 'placeholder' => 'Facebook URL'])
                                    @error('facebook')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', ['type' => 'text', 'name' => 'twitter', 'label' => 'Twitter', 'value' => old('twitter') ?? $user->getMetaField('twitter')?? '', 'placeholder' => '@workvision'])
                                    @error('twitter')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
                {{--[/social-media]--}}
                {{--[other-details]--}}
                <div class="card mb-2">
                    <div class="card-header border-0 bg-white">
                        <span class="font-weight-bold">{{ __('Other Information') }}</span></div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                @component('components.forms.select', ['name' => 'marketing', 'label' => 'Marketing Emails', 'options' =>  $marketing_types, 'value' => old('marketing')?? $user->marketing])
                                    @error('marketing')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.multiselect', ['id' => 'notifications', 'name' => 'notifications', 'label' => 'Notifications', 'options' =>  $notification_types, 'value' => old('notifications')?? $user->notifications])
                                    @error('notifications')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @if(!$user->hasRole(App\Models\System\Role::SUPER_ADMIN))
                                    @component('components.forms.select', ['name' => 'role', 'label' => 'Role', 'options' => $roles, 'value' => old('marketing') ?? $user->roles()->first()->name])
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
                                @component('components.forms.select', ['name' => 'terms', 'label' => 'Accept Terms', 'options' =>  $terms_types, 'value' => old('terms')?? $user->terms, 'help_text' => '<a target="_blank" class="fg-blue" href="#">Terms &amp; Conditions <i class="fas fa-external-link-alt"></i></a>'])
                                    @error('terms')
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
                            </div>
                        </div>
                    </div>
                </div>
                {{--[/other-details]--}}
                <div class="gb-btn-fixed-bar pt-1 pb-1">
                    @component('components.forms.button', ['name' => 'submit', 'type' => 'submit', 'classes' => ['btn-primary', 'fg-white'], 'label' => 'Save <i class="fas fa-save"></i>'])
                        <a class="btn btn-secondary" href="{{ admin_url() }}">{{ __('Cancel') }}</a>
                    @endcomponent
                </div>
            @endcomponent
            {{--[/form-card-deck]--}}
        </div>
    </div>
@endsection