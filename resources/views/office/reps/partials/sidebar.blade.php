{{-- Stored in /resources/views/office/reps/partials/sidebar.blade.php --}}
@component('components.bootstrap.card', [
    'id'    => 'office-reps-database-sidebar-card',

])
    <div class="card-header">{{ __('Filters') }}</div>
    <div class="card-body">
        @component('components.forms.form', [
            'id'            => 'office-reps-database-sidebar-form',
            'method'        => 'GET',
            'action'        => route('office.reps.index'),
        ])
            {{--[approved]--}}
            <h6 class="text-uppercase mb-3 font-xs-size font-weight-bold">{{ __('Approved?') }}</h6>
            @if(filled($approvedTypes))
                @component('components.forms.select', [
                    'id'            => 'approve_type',
                    'name'          => 'approve_type',
                    'value'         => old('approve_type'),
                    'options'       => $approvedTypes,
                    'placeholder'   => '',
                    'withIndex'     => true,
                ])
                    @error('approve_type')
                    @enderror
                @endcomponent
            @endif
            {{--[/approved]--}}
            {{--[company]--}}
            <h6 class="text-uppercase mb-3 font-xs-size font-weight-bold">{{ __('Company') }}</h6>
            @component('components.forms.input', [
                'id'            => 'company',
                'name'          => 'company',
                'value'         => old('company'),
                'placeholder'   => __('COMPANY NAME'),
                'classes'       => [
                    'text-uppercase'
                ]
            ])
                @error('company')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            @endcomponent
            {{--[/company]--}}
            {{--[types]--}}
            <h6 class="text-uppercase mb-3 font-xs-size font-weight-bold">{{ __('Type') }}</h6>
            <ul class="list-unstyled">
                @if(filled($specialities))
                    @foreach($specialities as $index => $specialty)
                        <li class="font-xs-size">
                            @component('components.forms.checkbox', [
                                'id'        => 'specialty-' . Str::snake($specialty),
                                'name'      => 'specialities['.Str::snake($specialty).']',
                                'label'     => $specialty,
                                'value'     => old('specialities.'.Str::snake($specialty)),
                                'checked'   => (filled(old('specialities.'.Str::snake($specialty))))? true : false,
                            ])
                                @error('specialities.'.Str::snake($specialty))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </li>
                    @endforeach
                @endif
            </ul>
            {{--[/types]--}}
            <div class="row">
                <div class="col-12">
                    @component('components.forms.button', [
                        'id'        => 'submit-btn',
                        'type'      => 'submit',
                        'name'      => 'submit-btn',
                        'label'     => __('Search'),
                        'classes'   => [
                            'btn',
                            'btn-primary'
                        ]
                    ])
                        @component('components.elements.link', [
                            'href'      => route('office.reps.index'),
                            'classes'   => [
                                'btn',
                                'btn-secondary'
                            ]
                        ])
                            {{ __('Reset') }}
                        @endcomponent
                    @endcomponent
                </div>
            </div>
        @endcomponent
    </div>
@endcomponent
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){
        let approvedTypes = $('input[name="approved_type"]');
    });
//-->
</script>