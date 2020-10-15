{{-- Stored in /resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Manage Settings')
@section('page-class', 'admin-settings-manage-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Manage Settings') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('All Settings') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($settings) && count($settings) !== 0)
                        @component('components.elements.table_data', ['headers' => ['Name', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $settings, 'query' => $query?? []])
                            @foreach($settings as $setting)
                                @php
                                    $label = $setting->key;
                                    $label = str_replace(env('APP_DOMAIN'), '', $label);
                                    $label = str_replace('_', ' ', $label);
                                    $label = str_replace('site', '', $label);
                                    $label = trim(ucwords($label));
                                @endphp
                                <tr data-redirect-url="{{ route('admin.settings.show', $setting) }}">
                                    <td>{{ $label }}</td>
                                    <td class="text-right">
                                        @if($setting->status == App\Models\System\Setting::LOCKED)
                                            <span class="fg-blue"><i class="fas fa-lock"></i></span>
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.settings.destroy', $setting), 'confirmed' => true, 'dialog_message' => 'Continue to delete setting?'])
                                                <a class="fg-blue" href="{{ route('admin.settings.edit', $setting) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No settings found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection