{{-- Stored in /resources/views/admin/users/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Users')
@section('page-class', 'admin-users-index')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Users') }}</span> <a class="font-lg-size" href="{{ route('admin.users.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Users') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($users) && count($users) !== 0)
                        @component('components.elements.table_data', ['headers' => ['email', 'role' , 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $users, 'query' => $query?? []])
                            @foreach($users as $user)
                                <tr data-redirect-url="{{ route('admin.users.show', $user) }}">
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge badge-secondary">{{ ucwords($user->roles()->first()->label) }}</span></td>
                                    <td>
                                        @if($user->status == App\Models\System\User::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\User::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\User::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($user->hasRole(App\Models\System\Role::SUPER_ADMIN))
                                            <a class="fg-blue" href="{{ route('admin.users.edit', $user) }}"><i class="fas fa-edit"></i></a>
                                            <span class="fg-blue" title="Locked"><i class="fas fa-lock"></i></span>
                                        @else
                                            @if($withTrashed === true)
                                                @if(filled($user->deleted_at))
                                                    @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.users.restore', ['id' => $user]), 'confirmed' => true, 'dialog_message' => 'Continue to restore user?'])
                                                        <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                    @endcomponent
                                                    @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.users.delete.trashed', ['id' => $user]), 'confirmed' => true, 'dialog_message' => 'Continue to delete user forever?'])
                                                        <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                    @endcomponent
                                                @endif
                                            @else
                                                @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.users.destroy', $user), 'confirmed' => true, 'dialog_message' => 'Continue to delete user?'])
                                                    <a class="fg-blue" href="{{ route('admin.users.edit', $user) }}"><i class="fas fa-edit"></i></a>
                                                    <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No users found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection