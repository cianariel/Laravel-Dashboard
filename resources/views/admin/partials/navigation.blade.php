{{-- Stored in /resources/views/admin/partials/navigation.blade.php --}}
<ul class="links list-unstyled">
    <li><a href="{{ site_url() }}" title="{{ __('View Site')}}"><i class="fas fa-home"></i></li>
    <li><a href="{{ secure_url('#') }}" title="{{ __('Messages') }}"><i class="fas fa-envelope-open"></i></a></li>
    <li><a href="{{ secure_url('#') }}" title="{{ __('Notifications') }}"><i class="fas fa-bell"></i></a></li>
    <li><a href="{{ route('admin.profile.update') }}" title="{{ __('Profile') }}"><i class="fas fa-cog"></i></a></li>
    <li>
        @component('components.forms.form', ['method' => 'POST', 'action' => route('logout') ])
            <button type="submit" class="btn-unstyled" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
        @endcomponent
    </li>
</ul>