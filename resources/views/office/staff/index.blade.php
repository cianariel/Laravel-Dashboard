@extends('office.layouts.master')
@section('html-title', __('Staff Members'))
@section('page-class', 'office-staff-members')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => true
    ])
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <div class="text-right">
                </div>
                @component('components.bootstrap.card', [
                    'id'        => 'office-staff-members-card',
                    'classes'   => [
                        'mt-3'
                    ]
                ])
                    <div class="card-body">
                        @if($users->count() !==0)
                            @component('components.elements.table_data', [
                                'id'        => 'office-staff-members-table',
                                'headers'   => [
                                    'email',
                                    'first_name',
                                    'last_name',
                                    'status',
                                    ''
                                ],
                                'paged'     => $users,
                                'query'     => $query,
                                'classes'   => ['table-striped', 'table-hover']
                            ])
                            @endcomponent
                        @else
                            <p class="card-text text-center">
                                <span class="d-block mb-2">{{ __('No staff memembers') }}</span>
                                @component('components.elements.link', [
                                    'href'      => route('office.staff.create'),
                                    'classes'   => ['btn', 'btn-primary']
                                ])
                                    {{ __('Invite Staff') }} <i class="fas fa-plus"></i>
                                @endcomponent
                            </p>
                        @endif
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection