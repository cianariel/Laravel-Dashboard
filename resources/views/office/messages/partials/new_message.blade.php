{{-- Stored in /resources/views/office/messages/partials/new_message.blade.php --}}
@component('components.bootstrap.modal', [
    'id'            =>  'office-message-create-modal',
    'title'         => __('New Message'),
    'size'          => 'modal-lg',
    'buttons'       => '<button type="button" class="close btn-unstyled font-sm-size no-hover" data-dismiss="modal" aria-label="Close">'.__('Cancel').'</button>',
    'options'       => [
        'backdrop'  => true,
        'keyboard'  => true,
        'focus'     => true,
        'show'      => (request()->has('error'))? true : false
    ]
])
    <div class="p-3">
        <div class="row">
            <div class="col-12 col-md-12">
                @component('components.forms.form', [
                    'id'        => 'office-message-create-form',
                    'action'    => route('office.messages.store'),
                    'method'    => 'POST',
                    'enctype'   => 'multipart/form-data'
                ])
                    {{--[recipient]--}}
                    <h5>{{ __('To') }}</h5>
                    <div class="d-block">
                        @component('components.forms.input', [
                            'id'            => 'recipient',
                            'name'          => 'recipient',
                            'value'         => old('recipient'),
                            'placeholder'   => '',

                        ])
                            <div id="office-ajax-recipients-results" class="office-ajax-recipients-results d-none">
                                <ul class="list-unstyled"></ul>
                            </div>
                            @error('recipient')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        @endcomponent
                    </div>
                    {{--[/recipient]--}}
                    {{--[subject]--}}
                    <h5>{{ __('Subject') }}</h5>
                    @component('components.forms.input', [
                        'id'            => 'subject',
                        'name'          => 'subject',
                        'value'         => old('subject'),
                        'placeholder'   => 'Subject',
                    ])
                        @error('subject')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/subject]--}}
                    <h5>{{ __('Message') }}</h5>
                    {{--[message]--}}
                    @component('components.forms.textarea', [
                        'id'            => 'message',
                        'name'          => 'message',
                        'value'         => old('message'),
                        'placeholder'   => 'HTML not allowed. Limit 1500 Characters',
                    ])
                        @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/message]--}}
                    {{--[attachment]--}}
                    <h5>{{ __('Attachment') }}</h5>
                    @component('components.forms.file', [
                        'id'        => 'attachment',
                        'name'      => 'attachment',
                        'value'     => '',
                        'help_text' => 'File Types: (PDF,CSV,Excel,Power Point,PNG,JPG,GIF). 10MB File size limit.'
                    ])
                        @error('attachment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/attachment]--}}
                    {{--[buttons]--}}
                    <div class="form-group row">
                        <div class="col-12 text-right">
                            @component('components.forms.button',[
                                'id'        => 'send-message-btn',
                                'type'      => 'submit',
                                'name'      => 'send-message-btn',
                                'label'     => __('Send'),
                                'classes'   => ['btn', 'btn-primary']
                            ])@endcomponent
                        </div>
                    </div>
                    {{--[/buttons]--}}
                @endcomponent
            </div>
        </div>
    </div>
@endcomponent
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){
        let form = $('#office-message-create-form');
        let recipientInput = form.find('#recipient');
        let recipientsAjaxResultsBlock = form.find('#office-ajax-recipients-results');

        recipientInput.keyup(function(e){

            let input = $(this);
            let value = input.val();
            let output = '';

            if(value.length > 3) {

                let ajaxRequest = MD.post('{{ route('office.ajax.message.query.reps') }}', {q: value}, 'json',
                    function(response) {

                        recipientsAjaxResultsBlock.find('ul').html('');
                        recipientsAjaxResultsBlock.addClass('d-none');

                        let data = response.data;

                        if(data.status == 200) {

                            let recipients = data.recipients;

                            for(var i = 0; i < recipients.length; i++) {

                                let recipient = recipients[i];
                                output += '<li class="list-item" data-username="' + recipient.username + '"><div class="row">';
                                output += '<div class="col-2 col-md-1">' + '<img class="avator" src="' + recipient.avator + '">' + '</div>';
                                output += '<div class="col-10 col-md-11"><h5>' + recipient.first_name + ' ' + recipient.last_name + '</h5>';
                                if(recipient.company) {
                                    output += '<h6>' + recipient.company + '</h6>';
                                }
                                output += '</div></div></li>';
                            }

                            if(output.length !== 0) {
                                recipientsAjaxResultsBlock.removeClass('d-none');
                                $(output).appendTo(recipientsAjaxResultsBlock.find('ul'));
                            }
                        }
                    }, function(error){
                        recipientsAjaxResultsBlock.addClass('d-none');
                        dialog('{{ __('Notice') }}', '{{ __('Error occured, please try again later.') }}');
                    }, function(){

                        let items = recipientsAjaxResultsBlock.find('ul .list-item');

                        items.each(function(e){
                           let item = $(this);
                           let username = item.data('username');

                           item.on('click touchend', function(e){
                                e.preventDefault();

                                recipientInput.val(username);

                                recipientsAjaxResultsBlock.addClass('d-none');
                           });
                        });

                    }, 0);
            }
        })
    });
//-->
</script>