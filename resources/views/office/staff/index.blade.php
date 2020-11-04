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
                    <div class="card-header">
                        <h5 class="card-text">{{ __('Staff Members') }}</h5>
                    </div>
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
                                @foreach($users as $_user)
                                    <tr data-redirect="{{ route('office.staff.edit', $_user) }}">
                                        <td>{{ $_user->email }}</td>
                                        <td>{{ $_user->first_name }}</td>
                                        <td>{{ $_user->last_name }}</td>
                                        <td><span class="badge-{{ $_user->status }}">{{ $_user->status }}</span></td>
                                        <td class="text-right">
                                            @if($user->hasRole(App\Models\System\Role::OWNER))
                                                @component('components.forms.form', [
                                                    'method'            => 'DELETE',
                                                    'action'            => route('office.staff.destroy', $_user),
                                                    'confirmed'         => true,
                                                    'dialog_message'    => 'Continue to delete staff member?'
                                                ])
                                                    <a class="fg-blue" href="{{ route('office.staff.edit', $_user) }}"><i class="fas fa-edit"></i></a>
                                                    <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endcomponent
                        @else
                            <p class="card-text text-center">
                                <span class="d-block mb-2">{{ __('No staff members') }}</span>
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