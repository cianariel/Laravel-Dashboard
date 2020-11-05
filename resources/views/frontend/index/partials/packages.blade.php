{{-- Stored in /resources/views/frontend/index/partials/packages.blade.php --}}
@if(isset($site))
    @if($packages = $site->packages()->where('status', App\Models\System\Package::ACTIVE)->cursor())
        @if($packages->count() !== 0)
            @component('components.bootstrap.container',[
                'fluid' => false
            ])
                <div class="row mt-3 justify-content-center align-items-center">
                    <div class="col-12 col-md-9">
                        @component('components.bootstrap.card',[
                            'id'        => 'index-packages-card-deck',
                            'layout'    => 'card-deck'
                        ])
                            @foreach($packages as $package)
                                @component('components.bootstrap.card', [
                                    'id'    => 'index-package-card-' . $package->name
                                ])
                                    <div class="card-header">
                                        <h5 class="card-text fg-black text-center">{{ $package->label }}</h5>
                                    </div>
                                    <div class="card-body">
                                        @if(filled($package->description))
                                            @if($lines = explode(PHP_EOL, $package->description))
                                                @if(count($lines) !== 0)
                                                    <ul>
                                                        @foreach($lines as $line)
                                                            <li class="mb-1 fg-black">{{ $line }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    <div class="card-footer text-center">
                                        @component('components.elements.link', [
                                            'href'      => route('register'),
                                            'classes'   => [
                                                'btn',
                                                'btn-primary'
                                            ]
                                        ])
                                            {{ __('Sign up') }}
                                        @endcomponent
                                    </div>
                                @endcomponent
                            @endforeach
                        @endcomponent
                    </div>
                </div>
            @endcomponent
        @endif
    @endif
@endif