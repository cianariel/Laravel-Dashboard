{{-- Stored in /resources/views/admin/countries/states/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'States')
@section('page-class', 'admin-countries-states-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('States') }}</span> <a class="font-lg-size" href="{{ route('admin.states.create', $country) }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of States') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($states) && count($states) !== 0)
                        @component('components.elements.table_data', ['headers' => ['name', 'code', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $states, 'query' => $query?? []])
                            @foreach($states as $state)
                                <tr data-redirect-url="{{ route('admin.states.show', ['country' => $country, 'state' => $state]) }}">
                                    <td>{{ $state->name }}</td>
                                    <td>{{ $state->code }}</td>
                                    <td>
                                        @if($state->status == App\Models\System\State::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\State::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\State::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($state->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.states.restore', ['id' => $state, 'country' => $country]), 'confirmed' => true, 'dialog_message' => 'Continue to restore state to country?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.states.delete.trashed', ['id' => $state, 'country' => $country]), 'confirmed' => true, 'dialog_message' => 'Continue to delete state from country forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.states.destroy', ['state' => $state, 'country' => $country]), 'confirmed' => true, 'dialog_message' => 'Continue to delete state from country?'])
                                                <a class="fg-blue" href="{{ route('admin.states.edit', ['state' => $state, 'country' => $country]) }}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Trash') }}"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No states found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection