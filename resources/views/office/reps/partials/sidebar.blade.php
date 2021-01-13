{{-- Stored in /resources/views/office/reps/partials/sidebar.blade.php --}}
@component('components.bootstrap.card', [
    'id'    => 'office-reps-database-sidebar-card',

])
    <div class="card-header">{{ __('Filters') }}</div>
    <div class="card-body">
        @component('components.forms.form', [
            'id'        => 'office-reps-database-sidebar',
            'method'    => 'GET',
            'action'    => '#',
        ])
            {{--[approved]--}}
            {{--[/approved]--}}
            {{--[company]--}}
            <h6 class="text-uppercase mb-3">{{ __('Company') }}</h6>
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
            <h6 class="text-uppercase mb-3">{{ __('Type') }}</h6>
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
        @endcomponent
    </div>
@endcomponent