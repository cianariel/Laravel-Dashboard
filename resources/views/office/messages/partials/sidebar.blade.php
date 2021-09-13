{{-- Stored in /resources/views/office/messages/index.blade.php --}}
@component('components.bootstrap.card', [
    'id'        => 'office-messges-sidebar-card',
])
    <div class="card-header bg-white">
        <div class="row">
            <div class="col-6">
                @component('components.elements.link', [
                    'href'  => route('office.messages.index', ['sort' => 'recieved'])
                ])
                    <span><i class="fas fa-ellipsis-v"></i> {{ __('Recieved') }}</span>
                @endcomponent
            </div>
            <div class="col-6 text-right">
                @component('components.elements.link', [
                    'href'      => '#office-message-create-modal',
                    'classes'   => [
                        'text-uppercase'
                    ],
                    'attrs'     => [
                        'data-toggle'   => 'modal',
                        'data-target'   => '#office-message-create-modal'
                    ]
                ])
                    <i class="far fa-edit"></i> {{ __('New') }}
                @endcomponent
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($messages->count() !== 0)
            <div class="mb-3">
                <div class="list-group">
                    @foreach($messages as $message)
                        @component('components.elements.link', [
                            'href'      => route('office.messages.show', $message),
                            'classes'   => [
                                'list-group-item',
                                'list-group-item-action'
                            ]
                        ])
                            <div class="row">
                                @if($fromUser = $message->users()->whereNot('id', $user->id)->first())
                                    <div class="col-4">
                                        <div class="avator mb-2">
                                            <img src="{{ avator($fromUser) }}" />
                                        </div>
                                    </div>
                                @endif
                                <div class="col-8">
                                     <h5>
                                        {{ $fromUser->first_name }} {{ $fromUser->last_name }}
                                        <span class="pull-right fg-grey">
                                            {{ Carbon\Carbon::parse($message->created_at)->format('M j') }}
                                        </span>
                                    </h5>
                                    <p class="card-text">{{ __('RE') }}: {{ $message->subject }}</p>
                                </div>
                            </div>
                        @endcomponent
                    @endforeach
                </div>
            </div>
        @else
            <p class="card-text text-center">{{ __('No messages found') }}</p>
        @endif
    </div>
@endcomponent