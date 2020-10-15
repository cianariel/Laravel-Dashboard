{{-- Stored in /resources/views/admin/packages/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Packages')
@section('page-class', 'admin-packages-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Packages') }}</span> <a class="font-lg-size" href="{{ route('admin.packages.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Packages') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($packages) && count($packages) !== 0)
                        @component('components.elements.table_data', ['headers' => ['label', 'price', 'featured', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $packages, 'query' => $query?? []])
                            @foreach($packages as $package)
                                <tr data-redirect-url="{{ route('admin.packages.show', $package) }}">
                                    <td>{{ ucwords($package->label) }}</td>
                                    <td>${{ number_format(dollars($package->price), 2) }}</td>
                                    <td>
                                        @if($package->featured == App\Models\System\Package::FEATURED)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Package::FEATURED)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Package::NOT_FEATURED)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($package->status == App\Models\System\Package::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Package::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Package::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($package->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.packages.restore', ['id' => $package]), 'confirmed' => true, 'dialog_message' => 'Continue to restore package?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.packages.delete.trashed', ['id' => $package]), 'confirmed' => true, 'dialog_message' => 'Continue to delete package forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.packages.destroy', $package), 'confirmed' => true, 'dialog_message' => 'Continue to delete package?'])
                                                <a class="fg-blue" href="{{ route('admin.packages.edit', $package) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No packages found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
