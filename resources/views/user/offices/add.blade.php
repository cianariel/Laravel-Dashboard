{{-- Stored in /resources/views/user/account/edit.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Add Offices')
@section('page-class', 'user-offices-add')

{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false,
        'classes' => ['bg-white', 'pb-2']
    ])
        <div class="row pb-2 d-none">
            <div class="col-md-6">
                <div class="text-center p-2">
                    <button class="btn btn-primary">{{__('Registered Offices')}}</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center p-2">
                    <button class="btn btn-secondary">{{__('Non-Registered Offices')}}</button>
                </div>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12">
                @include('user.partials.search', [
                    'description' => __('Add an office to your list'),
                    'placeholder' => __('Enter office name, address or provider'),
                    'search_id' => 'search-office',
                    'classes'   => ['bb-2 pt-1 pb-1']
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="offices-search-container">
                @if($offices->count())
                    @foreach($offices as $office)
                        <div class="search-result-holder pl-3 pr-3 pt-4 pb-2" data-id="{{$office->uuid}}">
                            <h5>{{ $office->name }}</h5>
                            @php
                                $location = $office->getMetaField('location', '');
                            @endphp
                            @if($location)
                                <div>{{$location['address']. ", ". $location['city']. ", ". $location['state']. " " . $location['zipcode']}}</div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class=" pl-3 pr-3 pt-4 pb-2"><i>No offices found</i></div>
                @endif
            </div>
        </div>
    @endcomponent

    @section('scripts_end')
        <script>
            $(document).ready(function(){
                $("#search-office").keydown(function(e){
                    if(e.keyCode != 13)
                        return;
                    $.ajax({
                        type: "GET",
                        url: '{{ route("user.offices.ajax.search-non-mine") }}',
                        data: {
                            keyword: $(this).val()
                        },
                        success: function(reps){
                            let offices = reps.data.offices;
                            $("#offices-search-container").empty()
                            let itemHtml = "";
                            if(offices.length){
                                for(let office of offices){
                                    itemHtml += '<div class="search-result-holder pl-3 pr-3 pt-4 pb-2" data-id="'+office.uuid+'">'
                                        + '<h5>'+ office.name +'</h5>'
                                        + (office.meta_fields && office.meta_fields.location ? ('<div>' + office.meta_fields.location.address + ', ' + office.meta_fields.location.city + ", " + office.meta_fields.location.state + ' ' + office.meta_fields.location.zipcode + '</div>') : '')
                                        + '</div>'
                                    ;
                                    
                                }
                            }else{
                                itemHtml = '<div class=" pl-3 pr-3 pt-4 pb-2"> <i>No offices found</i> </div>';
                            }
                            $("#offices-search-container").append(itemHtml);
                        }
                    });
                });

                $('#offices-search-container').on('click', '.search-result-holder', function(){
                    // Coming soon..
                })
            })
        </script>
    @endsection

@endsection