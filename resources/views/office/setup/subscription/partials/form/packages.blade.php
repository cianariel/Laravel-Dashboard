{{-- Stored in /resources/views/setup/office/subscription/partials/form/packages.blade.php --}}
@if(isset($packages) && $packages->count() !== 0)
    @if($packages->count() > 1)<div id="md-packages-slider">@endif
    @foreach($packages as $index => $package)
        <div class="card card-package w-33 rounded bg-white h-100 border-top-0 border-bottom-0"
            data-mh="card-packages"
            data-price="{{ safe_integer($package->price) }}"
            data-selected="{{ (filled(old('packages.' . $package->name)))? 'true' : 'false'}}">
            <div class="card-header bg-white border-0">
                @if($image = $package->getMedia('images')->first())
                 <div class="package-image rounded-circle text-center ">
                    <div class="package-image" style="background:url('{{ $image->getUrl() }}') center no-repeat;background-size: cover;"></div>
                </div>
                @endif
                <div class="package-title text-center mt-2 mb-2">
                    <h3 class="fg-baby-blue">{{ __($package->label) }}</h3>
                    @if($package->trial_enabled == App\Models\System\Package::TRIAL_ENABLED)
                        <h6 class="fg-dark-grey">{{ __('15 day free trial') }}</h6>
                    @endif
                </div>
                 <h5 class="text-center fg-blue package-price mt-3 mb-3 font-lg-size">
                    {{ currency_format($package->price, 'usd') }}
                    <small class="d-block">
                        {{ ($package->interval == App\Models\System\Package::MONTHLY)? __('monthly') : __( $package->interval . 'ly') }}
                    </small>
                </h5>
            </div>
            <div class="card-body">
                @if($description = $package->description)
                    @if(filled(trim($description)))
                        @if($lines = explode(PHP_EOL, $description))
                            <ul class="ml-5">
                            @if(count($lines) > 0)
                                @foreach($lines as $line)
                                    <li>{{ __($line) }}</li>
                                @endforeach
                            @endif
                            </ul>
                        @endif
                    @endif
                @endif
                <div class="package-toggler text-center font-xl-size">
                    @component('components.forms.toggler', [
                        'id'        => 'package_' . $package->name,
                        'name'      => 'packages['.$index.']',
                        'value'     => old('packages.' . $index)?? $package->name,
                        'selected'  => ((filled(old('packages.' . $index)))? true : false),
                        'classes'   => ['fg-blue']])
                        @error('packages.' . $index)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                </div>
            </div>
        </div>
    @endforeach
    @if($packages->count() > 1)</div>@endif
    @if($packages->count() === 1)
        @include('office.setup.subscription.partials.form.billing')
    @endif
@else
    @component('components.bootstrap.card', [
        'classes' => ['border-0', 'rounded', 'bg-white']
    ])
        <div class="card-body">
            <p class="card-text">{{ __('Sorry, please try again later.') }}</p>
        </div>
    @endcomponent
@endif
@component('components.elements.script')
    jQuery(document).ready(function($){
        let slider = $('#md-packages-slider');

        slider.slick({
            autoplay:       false,
            arrows:         true,
            prevArrow:      '<a class="md-prev"><i class="fas fa-chevron-left fa-3x"></i></a>',
            nextArrow:      '<a class="md-next"><i class="fas fa-chevron-right fa-3x"></a>',
            centerMode:     false,
            dots:           false,
            draggable:      true,
            infinite:       false,
            rows:           1,
            slidesPerRow:   (MD.isMobile() || $(window).width() <= 760)? 1 : 2,
            slidesToShow:   1,
            slidesToScroll: 1,
            swipe:          1,
            touchMove:      1
        });
    });
@endcomponent