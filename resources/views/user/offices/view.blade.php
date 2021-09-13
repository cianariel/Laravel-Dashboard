@extends('user.layouts.master')
@section('html-title', $office->label)
@section('page-class', 'user-offices-view')

{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false,
        'classes' => ['bg-white', 'pb-2']
    ])
        <div class="row pt-5"">
            <div class="col-md-7">
                <h5>{{ $office->label }}</h5>
                @php
                    $location = $office->getMetaField('location', '');
                @endphp
                @if($location)
                    <div>{{$location['address']. ", ". $location['city']. ", ". $location['state']. " " . $location['zipcode']}}</div>
                @endif
                <div>{{$office->getMetaField('mobile_phone', '')}}</div>
            </div>
            <div class="col-md-5 text-center">
                @component('components.forms.form',[
                    'action'    => route('user.offices.add.details.add', ['uuid' => $office->uuid]),
                    'method'    => 'POST',
                    'confirmed' => false
                ])

                    @component('components.forms.button', [
                        'id'        => 'change-status-btn',
                        'type'      => 'submit',
                        'name'      => 'submit-btn',
                        'label'     => __('ADD TO MY OFFICES'),
                        'classes'   => [
                            'btn',
                            'btn-primary',
                        ]
                    ])
                    @endcomponent

                @endcomponent

            </div>
        </div>
    @endcomponent

    <style>

    </style>

    @section('scripts_end')
        <script>
            $(document).ready(function(){
                
            })
        </script>
    @endsection

@endsection