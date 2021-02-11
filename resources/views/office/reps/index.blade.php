{{-- Stored in /resources/views/office/reps/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Reps Database')
@section('page-class', 'office-reps-database-index')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'  => true
    ])
        @component('components.bootstrap.card', [
            'id'        => 'office-reps-database-card-deck',
            'layout'    => 'card-group'
        ])
            @include('office.reps.partials.sidebar')
            @component('components.bootstrap.card', [
                'id'    => 'office-reps-database-listing-card'
            ])
                <div class="card-body">
                    <div class="d-block text-right mb-2 mb-2">
                        @component('components.elements.link', [
                            'href'      => '#',
                            'classes'   => [
                                'fg-link'
                            ]
                        ])
                            {{ __('Requested approval') }} <span class="badge badge-primary">0</span>
                        @endcomponent
                    </div>
                    @if($reps->count() !== 0)
                        <ul class="list-group">
                            @foreach($reps as $rep)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-12 col-md-1">
                                            <div class="user-avator image">
                                                @component('components.elements.link', [
                                                    'href'      => route('office.reps.show', $rep),
                                                    'classes'   => [
                                                        'border-0'
                                                    ]
                                                ])
                                                    <img src="{{ avator($rep) }}">
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            @component('components.elements.link', [
                                                    'href'      => route('office.reps.show', $rep),
                                                    'classes'   => [
                                                        'border-0',
                                                        'text-decoration-none',
                                                        'fg-black'
                                                    ]
                                                ])
                                                <h5>{{ $rep->first_name }} {{ $rep->last_name }}</h5>
                                            @endcomponent
                                            <h6 class="font-weight-normal">{{ __('Place holder for RX Data') }}</h6>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <ul class="list-unstyled">
                                                <li class="d-inline-block mr-3">
                                                    @component('components.forms.form', [
                                                        'id'        => 'approve-user-form-' . $rep->id,
                                                        'action'    => route('office.ajax.reps.toggle.approved'),
                                                        'method'    => 'POST',
                                                        'classes'   => [
                                                            'no-form-update-handler',
                                                            'form'
                                                        ]
                                                    ])
                                                        @component('components.forms.hidden',[
                                                            'name'  => 'user',
                                                            'value' => $rep->id
                                                        ])@endcomponent
                                                        @component('components.forms.button', [
                                                            'type'      => 'submit',
                                                            'name'      => 'submit-btn',
                                                            'label'     => '<i class="icon '.(office_user_blocked($office, $rep)? 'fg-green' : '').' far fa-check-square"></i> ' . __('Approve'),
                                                            'classes'   => [
                                                                'btn-unstyled',
                                                                'fg-grey'
                                                            ]
                                                        ])@endcomponent
                                                    @endcomponent
                                                </li>
                                                <li class="d-inline-block mr-3">
                                                    @component('components.forms.form', [
                                                        'id'        => 'approve-user-form-' . $rep->id,
                                                        'action'    => route('office.ajax.reps.toggle.favorite'),
                                                        'method'    => 'POST',
                                                        'classes'   => [
                                                            'no-form-update-handler',
                                                            'form'
                                                        ]
                                                    ])
                                                        @component('components.forms.hidden',[
                                                            'name'  => 'user',
                                                            'value' => $rep->id
                                                        ])@endcomponent
                                                        @component('components.forms.button', [
                                                            'type'      => 'submit',
                                                            'name'      => 'submit-btn',
                                                            'label'     => '<i class="icon '.(office_user_favorite($office, $rep)? 'fg-red' : '').' far fa-heart"></i> ' . __('Favorite'),
                                                            'classes'   => [
                                                                'btn-unstyled',
                                                                'fg-grey',
                                                            ]
                                                        ])@endcomponent
                                                    @endcomponent
                                                </li>
                                                <li class="d-inline-block mr-3">
                                                    @component('components.forms.form', [
                                                        'id'        => 'approve-user-form-' . $rep->id,
                                                        'action'    => route('office.ajax.reps.toggle.blocked'),
                                                        'method'    => 'POST',
                                                        'classes'   => [
                                                            'no-form-update-handler',
                                                            'form'
                                                        ]
                                                    ])
                                                        @component('components.forms.hidden',[
                                                            'name'  => 'user',
                                                            'value' => $rep->id
                                                        ])@endcomponent
                                                        @component('components.forms.button', [
                                                            'type'      => 'submit',
                                                            'name'      => 'submit-btn',
                                                            'label'     => '<i class="icon '.(office_user_blocked($office, $rep)? 'fg-red' : '').' fas fa-ban"></i> ' . __('Block'),
                                                            'classes'   => [
                                                                'btn-unstyled',
                                                                'fg-grey'
                                                            ]
                                                        ])@endcomponent
                                                    @endcomponent
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="card-text text-center">{{ __('No reps available.') }}</p>
                    @endif
                </div>
            @endcomponent
        @endcomponent
    @endcomponent
@endsection
@section('scripts_end')
<script type="text/javascript">
<!--
    jQuery(document).ready(function($) {
        let forms = $('#office-reps-database-listing-card .form');

        forms.submit(function(e){
            e.preventDefault();

            let form = $(this);
            let btn = form.find('button');
            let icon = btn.find('.icon');
            let url = form.attr('action');
            let status = '';

            let data = {
                'user': form.find('input[name="user"]').val()
            };

            MD.post(url, data, 'json',
                function(response){
                    let data = response.data;

                    if(data) {
                        if(data.message == 'success') {
                            status = data.status;

                            if(status == 'on') {

                                if(btn.text().trim() == 'Approve') {
                                    icon.addClass('fg-green');
                                }

                                if(btn.text().trim() == 'Block') {
                                    icon.addClass('fg-red');
                                }

                                if(btn.text().trim() == 'Favorite') {
                                    icon.addClass('fg-red');
                                }
                            }

                            if(status == 'off') {
                                if(btn.text().trim() == 'Approve') {
                                    icon.removeClass('fg-green');
                                }

                                if(btn.text().trim() == 'Block') {
                                    icon.removeClass('fg-red');
                                }

                                if(btn.text().trim() == 'Favorite') {
                                    icon.removeClass('fg-red');
                                }
                            }
                        }
                    }
                },
                function(error){
                    MD.dialog('Notice', 'Error occured. please try again later.');
                },
                function(){
                    // Do nothing
                }
            , 0);
        });
    });
//-->
</script>
@endsection