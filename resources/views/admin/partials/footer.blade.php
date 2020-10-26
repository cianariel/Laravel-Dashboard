 {{-- Stored in /resources/views/admin/partials/footer.blade.php --}}
<div id="md-admin-footer">
    <div class="container-fluid">
        <div class="row mb-2 mt-2">
            <div class="col-md-8 text-center text-md-left">
                <ul class="links d-inline list-unstyled ml-1">
                    <li><a href="{{ route('pages.show', ['support']) }}">{{ __('Support') }}</a></li>
                    <li><a href="{{ route('pages.show', ['privacy']) }}">{{ __('Privacy') }}</a></li>
                    <li><a href="{{ route('pages.show', ['terms']) }}">{{ __('Terms of Service') }}</a></li>
                    <li><a href="{{ route('pages.show', ['contact']) }}">{{ __('Contact') }}</a></li>
                </ul>
            </div>
            <div class="col-md-4 text-center text-md-right">
                <span class="d-inline-block mr-1">&copy; {{ current_year() }} {{ config('app.name') }}</span>
                {{--<span class="d-inline-block md-build-version">{{ __('Build:') }} {{ Version::compact() }}</span>--}}
            </div>
        </div>
    </div>
</div>