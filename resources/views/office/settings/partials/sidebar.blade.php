{{-- Stored in resources/views/office/settings/partials/sidebar.blade.php }} --}}
@component('components.bootstrap.card', [
    'id'    => 'office-settings-sidebar-card'
])
    <div class="card-body p-0 m-0 border-0">
        <ul class="list-group">
            <li class="list-group-item">
                @component('components.elements.link', [
                    'href'  => '#'
                ])
                    {{ __('Office') }}
                @endcomponent
            </li>
            <li class="list-group-item">
                @component('components.elements.link', [
                    'href'  => '#'
                ])
                    {{ __('Calendar') }}
                @endcomponent
            </li>
            <li class="list-group-item">
                @component('components.elements.link', [
                    'href'  => '#'
                ])
                    {{ __('Subscriptions') }}
                @endcomponent
            </li>
        </ul>
    </div>
@endcomponent