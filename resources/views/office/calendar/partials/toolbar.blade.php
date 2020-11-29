{{-- Stored in /resources/views/office/calendar/partials/toolbar.blade.php --}}
@component('components.forms.form', [
    'id'        => 'office-calendar-toolbar-form',
    'action'    => '#',
    'method'    => 'GET'
])
    <div class="row">
        <div class="col-6 col-md-2">
            @component('components.forms.select', [
                'id'            => 'month_year',
                'name'          => 'month_year',
                'value'         => old('month_year')?? request()->input('month_year')?? '',
                'options'       => [],
                'placeholder'   => '',
            ])
                @error('month_year')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            @endcomponent
        </div>
        <div class="desktop-only col-8"></div>
        <div class="col-6 col-md-2">
            <div class="row">
                <div class="col-9">
                    @component('components.forms.select', [
                        'id'            => 'list_type',
                        'name'          => 'list_type',
                        'value'         => old('list_type')?? request()->input('list_type')?? 'list',
                        'options'       => [
                            'list'      => 'List',
                            'calendar'  => 'Calendar '
                        ],
                        'withIndex'     => true,
                    ])
                        @error('list_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-3">
                    @component('components.elements.link', [
                        'href'  => '#',
                        'attrs' => [
                            'title' => 'Print'
                        ],
                        'classes' => [
                            'fg-blue'
                        ]
                    ])
                        <i class="fas fa-print"></i>
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endcomponent