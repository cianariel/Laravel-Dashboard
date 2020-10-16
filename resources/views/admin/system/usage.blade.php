{{-- Stored in /resources/views/admin/system/usage.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'System Usage')
@section('page-class', 'admin-system-usage')
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
                <span class="font-lg-size font-weight-bold">{{ __('System Usage') }}</span>
            </div>
            <div class="card-deck">
                <div class="card">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('CPU Usage') }}</span></div>
                    <div class="card-body p-0 pt-5 pb-5">
                        <div class="mt-3 row justify-content-center">
                            <div class="col-6">
                                <canvas id="md-cpu-usage-chart" width="250" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Memory Usage') }}</span></div>
                    <div class="card-body p-0 pt-5 pb-5">
                        <div class="mt-3 row justify-content-center">
                            <div class="col-6">
                                <canvas id="md-memory-usage-chart" width="250" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Server Details') }}</span></div>
                    <div class="card-body p-0 pt-5 pb-5">
                        <div class="mt-3 row justify-content-center">
                            <div class="col-6">
                                <p class="card-text">{{ __('PHP Version') }}: {{ phpversion() }}</p>
                                <p class="card-text">{{ __('Script Memory Usage:') }} {{ strtoupper(memory_convert(memory_get_usage(true))) }}</p>
                                <p class="card-text">{{ php_uname() }}</p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_end')
<script type="text/javascript">
<!--
    jQuery(document).ready(function($) {

        let data = [{{ ceil(server_cpu_usage()) }}, {{ 100 - ceil(server_cpu_usage()) }}];
        let wv_cpu_usage_chart = document.getElementById('md-cpu-usage-chart').getContext('2d');
        let wv_memory_usage_chart = document.getElementById('md-memory-usage-chart').getContext('2d');

        let cpuChart = new Chart(wv_cpu_usage_chart, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(40, 167, 69, 0.8)'
                    ],
                }],
                labels: [
                    '{{ __('% Load') }}',
                    '{{ __('% Available')}}'
                ]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'CPU LOAD'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });


        data = [{{ ceil(server_memory_usage()) }} , {{ 100 - ceil(server_memory_usage()) }}];
        let memoryChart = new Chart(wv_memory_usage_chart, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(40, 167, 69, 0.8)'
                    ],
                }],
                labels: [
                    '{{ __('% Used') }}',
                    '{{ __('% Free')}}'
                ]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'RAM'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
//-->
</script>
@endsection