{{-- Stored in /resources/views/admin/countries/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Countries')
@section('page-class', 'admin-countries-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Countries') }}</span> <a class="font-lg-size" href="{{ route('admin.countries.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Countries') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($countries) && count($countries) !== 0)
                        @component('components.elements.table_data', ['headers' => ['name', 'code', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $countries, 'query' => $query?? []])
                            @foreach($countries as $country)
                                <tr data-redirect-url="{{ route('admin.states.index', $country) }}">
                                    <td>{{ $country->name }}</td>
                                    <td>{{ $country->code }}</td>
                                    <td>
                                        @if($country->status == App\Models\System\Country::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Country::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Country::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($country->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.countries.restore', ['id' => $country]), 'confirmed' => true, 'dialog_message' => 'Continue to restore country?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.countries.delete.trashed', ['id' => $country]), 'confirmed' => true, 'dialog_message' => 'Continue to delete country forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.countries.destroy', $country), 'confirmed' => true, 'dialog_message' => 'Continue to delete country?'])
                                                <a class="fg-blue" href="{{ route('admin.states.index', $country) }}" title="{{ __('Edit States') }}"><i class="fas fa-cog"></i></a>
                                                <a class="fg-blue" href="{{ route('admin.countries.edit', $country) }}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Trash') }}"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No countries found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection