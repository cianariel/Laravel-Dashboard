{{-- Stored in /resources/views/user/profile/edit.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Edit Profile')
@section('page-class', 'user-edit')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false
    ])
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                @component('components.bootstrap.card', [
                    'id' => 'user-edit-profile-card'
                ])
                    <div class="card-header">
                        {{ __('User Profile') }}
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-9">
                                @component('components.forms.form',[
                                    'id'        => 'user-edit-profile-form',
                                    'action'    => '#',
                                    'method'    => 'POST',
                                    'confirmed' => true
                                ])
                                    <div class="row">
                                        <div class="col-12 col-md-9 text-center">
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