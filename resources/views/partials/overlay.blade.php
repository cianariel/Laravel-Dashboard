{{-- Stored in /resources/views/partials/overlay.blade.php --}}
<div id="md-overlay" class="md-overlay hidden">
    @component('components.bootstrap.flex', [
        'justify'       => 'center',
        'align_items'   => 'center',
        'classes'       => ['h-100', 'w-100']
    ])
        <div class="md-overlay-inner">
           @hasSection('page-overlay')
                 @yield('page-overlay')
           @else
                <div class="text-center icon">
                    <i class="fas fa-cog fa-spin fa-3x"></i>
                </div>
                <h2 class="text-center p-4">{{ __('Please wait...') }}</h2>
           @endif
        </div>
    @endcomponent
</div>