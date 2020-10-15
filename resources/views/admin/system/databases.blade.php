{{-- Stored in /resources/views/admin/system/logs.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'System Databases')
@section('page-class', 'admin-system-databases-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Databases') }}</span>
            </div>
            @component('components.bootstrap.card', ['id' => 'admin-system-databases-card'])
                <div class="card-body">
                    @if($user->hasAnyRole([\App\Models\System\Role::SUPER_ADMIN, \App\Models\System\Role::ADMIN]))
                        @if(count($databases) !== 0)
                            @component('components.elements.table', ['headers' => ['schemas', ''], 'classes' => ['table-striped']])
                                @foreach($databases as $schema)
                                    <tr>
                                        <td>
                                            {{ $schema->Database }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endcomponent
                        @endif
                    @else
                        <p class="card-text text-center">{{ __('Unauthorized access. Please contact administrator for access.') }}</p>
                    @endif
                </div>
            @endcomponent
        </div>
    </div>
@endsection