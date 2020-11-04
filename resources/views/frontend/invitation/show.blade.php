{{-- Stored in /resources/views/frontend/invitation/accept.blade.php --}}
@extends('frontend.layouts.master')
@section('html-title', 'Invitation')
@section('page-class', 'staff-invitation-show')
@section('meta-robots', 'NOINDEX, NOFOLLOW')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false
    ])
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                @component('components.bootstrap.card',[
                    'id'    => 'staff-invitation-card'
                ])
                    <div class="card-head">
                        <h5>{{ __('Invited to join') }}  {{ $offce->label }}</h5>
                    </div>
                    <div class="card-body">
                        @component('components.forms.form', [
                            'id'            => 'staff-invitation-form',
                            'method'        => 'POST',
                            'confirmed'     => true,
                        ])
                            {{--[first_name]--}}
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'first_name',
                                'name'          => 'first_name',
                                'label'         => 'First Name',
                                'value'         => old('first_name')?? $user->first_name,
                                'placeholder'   => ''
                            ])
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[/first_name]--}}
                            {{--[last_name]--}}
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'last_name',
                                'name'          => 'last_name',
                                'label'         => 'Last Name',
                                'value'         => old('last_name')?? $user->last_name,
                                'placeholder'   => ''
                            ])
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[/last_name]--}}
                            {{--[address]--}}
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'address',
                                'name'          => 'address',
                                'label'         => 'Address',
                                'value'         => old('address')?? $user->address,
                                'placeholder'   => ''
                            ])
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[/address]--}}
                            {{--[address_2]--}}
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'address_2',
                                'name'          => 'address_2',
                                'label'         => 'Unit #',
                                'value'         => old('address_2')?? $user->address_2,
                                'placeholder'   => ''
                            ])
                                @error('address_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[/address]--}}
                            {{--[city]--}}
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'city',
                                'name'          => 'city',
                                'label'         => 'City',
                                'value'         => old('city')?? $user->city,
                                'placeholder'   => ''
                            ])
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[/city]--}}
                            {{--[zipcode]--}}
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'name'          => 'zipcode',
                                'label'         => 'Zipcode',
                                'value'         => old('zipcode')?? $user->zipcode,
                                'placeholder'   => '91234'
                            ])
                                @error('zipcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[/zipcode]--}}
                            @component('components.forms.states', [
                                'label'         => __('State'),
                                'name'          => 'state',
                                'value'         => old('state')?? $user->state ?? 'CA'
                            ])
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.countries', [
                                'id'            => 'country',
                                'name'          => 'country',
                                'label'         => __('Country'),
                                'options'       => $countries,
                                'value'         => old('country')?? $user->country ?? 'US',
                                'withIndex' => true
                            ])
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.input_group', [
                                'type'          => 'tel',
                                'name'          => 'phone',
                                'prepend'       => '+1',
                                'label'         => __('Phone'),
                                'value'         => old('phone')?? $user->phone
                            ])
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.input_group', [
                                'type'          => 'tel',
                                'name'          => 'mobile_phone',
                                'prepend'       => '+1',
                                'label'         => __('Mobile Phone'),
                                'value'         => old('mobile_phone')?? $user->mobile_phone
                            ])
                                @error('mobile_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            {{--[submit-btn]--}}
                            <div class="form-group row">
                                <div class="col-12 text-right">
                                    @component('components.forms.button',[
                                        'id'        => 'save-btn',
                                        'type'      => 'submit',
                                        'name'      => 'save-btn',
                                        'label'     => __('Save'),
                                        'classes'   => ['btn', 'btn-primary']
                                    ])@endcomponent
                                </div>
                            </div>
                        @endcomponent
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection