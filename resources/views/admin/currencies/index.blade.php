{{-- Stored in /resources/views/admin/currencies/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Currencies')
@section('page-class', 'admin-currencies-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Currencies') }}</span> <a class="font-lg-size" href="{{ route('admin.currencies.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Currencies') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($currencies) && count($currencies) != 0)
                        @component('components.elements.table_data', ['headers' => ['name', 'code', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $currencies, 'query' => $query?? []])
                            @foreach($currencies as $currency)
                                <tr data-redirect-url="{{ route('admin.currencies.show', $currency) }}">
                                    <td>{{ $currency->name }}</td>
                                    <td>{{ $currency->code }}</td>
                                    <td>
                                        @if($currency->status == App\Models\System\Currency::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Currency::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Currency::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($currency->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'],'method' => 'PUT','action' => route('admin.currencies.restore', ['id' => $currency]), 'confirmed' => true, 'dialog_message' => 'Continue to restore currency?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                            @endif
                                            @component('components.forms.form', ['classes' => ['d-inline'],'method' => 'DELETE','action' => route('admin.currencies.delete.trashed', ['id' => $currency]), 'confirmed' => true, 'dialog_message' => 'Continue to delete currency forever?'])
                                                <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE','action' => route('admin.currencies.destroy', $currency), 'confirmed' => true, 'dialog_message' => 'Continue to delete currency?'])
                                                <a class="fg-blue" href="{{ route('admin.currencies.edit', $currency) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection