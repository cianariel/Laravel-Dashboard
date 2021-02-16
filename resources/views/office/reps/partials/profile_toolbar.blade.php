{{-- Stored in /resources/views/office/reps/partials/profile_toolbar.blade.php --}}
<div id="office-reps-profile-toolbar" class="office-reps-profile-toolbar">
    <div class="row">
        <div class="col-6">
            <ul class="list-unstyled buttons">
                <li>
                    @component('components.elements.link', [
                        'href'      => '#',
                        'classes'   => [
                            'border',
                            'border-dark',
                            'rounded-pill',
                            'd-inline-block',
                            'pt-2',
                            'pb-2',
                            'pr-4',
                            'pl-4',
                            'text-decoration-none',
                            'fg-black'
                        ]
                    ])
                        {{ __('Send Message') }}
                    @endcomponent
                </li>
            </ul>
        </div>
        <div class="col-6">
            <ul class="list-unstyled pull-right mt-3">
                <li class="d-inline-block mr-3">
                    @component('components.forms.form', [
                        'id'        => 'approve-user-form-' . $repUser->id,
                        'action'    => route('office.ajax.reps.toggle.approved'),
                        'method'    => 'POST',
                        'classes'   => [
                            'no-form-update-handler',
                            'form'
                        ]
                    ])
                        @component('components.forms.hidden',[
                            'name'  => 'user',
                            'value' => $repUser->id
                        ])@endcomponent
                        @component('components.forms.button', [
                            'type'      => 'submit',
                            'name'      => 'submit-btn',
                            'label'     => '<i class="icon '.(office_user_blocked($office, $repUser)? 'fg-green' : '').' far fa-check-square"></i> ' . __('Approve'),
                            'classes'   => [
                                'btn-unstyled',
                                'fg-grey'
                            ]
                        ])@endcomponent
                    @endcomponent
                </li>
                <li class="d-inline-block mr-3">
                    @component('components.forms.form', [
                        'id'        => 'approve-user-form-' . $repUser->id,
                        'action'    => route('office.ajax.reps.toggle.favorite'),
                        'method'    => 'POST',
                        'classes'   => [
                            'no-form-update-handler',
                            'form'
                        ]
                    ])
                        @component('components.forms.hidden',[
                            'name'  => 'user',
                            'value' => $repUser->id
                        ])@endcomponent
                        @component('components.forms.button', [
                            'type'      => 'submit',
                            'name'      => 'submit-btn',
                            'label'     => '<i class="icon '.(office_user_favorite($office, $repUser)? 'fg-red' : '').' far fa-heart"></i> ' . __('Favorite'),
                            'classes'   => [
                                'btn-unstyled',
                                'fg-grey',
                            ]
                        ])@endcomponent
                    @endcomponent
                </li>
                <li class="d-inline-block mr-3">
                    @component('components.forms.form', [
                        'id'        => 'approve-user-form-' . $repUser->id,
                        'action'    => route('office.ajax.reps.toggle.blocked'),
                        'method'    => 'POST',
                        'classes'   => [
                            'no-form-update-handler',
                            'form'
                        ]
                    ])
                        @component('components.forms.hidden',[
                            'name'  => 'user',
                            'value' => $repUser->id
                        ])@endcomponent
                        @component('components.forms.button', [
                            'type'      => 'submit',
                            'name'      => 'submit-btn',
                            'label'     => '<i class="icon '.(office_user_blocked($office, $repUser)? 'fg-red' : '').' fas fa-ban"></i> ' . __('Block'),
                            'classes'   => [
                                'btn-unstyled',
                                'fg-grey'
                            ]
                        ])@endcomponent
                    @endcomponent
                </li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
<!--
    jQuery(document).ready(function($) {
        let forms = $('#office-reps-profile-toolbar .form');

        forms.submit(function(e){
            e.preventDefault();

            let form = $(this);
            let btn = form.find('button');
            let icon = btn.find('.icon');
            let url = form.attr('action');
            let status = '';

            let data = {
                'user': form.find('input[name="user"]').val()
            };

            MD.post(url, data, 'json',
                function(response){
                    let data = response.data;

                    if(data) {
                        if(data.message == 'success') {
                            status = data.status;

                            if(status == 'on') {

                                if(btn.text().trim() == 'Approve') {
                                    icon.addClass('fg-green');
                                }

                                if(btn.text().trim() == 'Block') {
                                    icon.addClass('fg-red');
                                }

                                if(btn.text().trim() == 'Favorite') {
                                    icon.addClass('fg-red');
                                }
                            }

                            if(status == 'off') {
                                if(btn.text().trim() == 'Approve') {
                                    icon.removeClass('fg-green');
                                }

                                if(btn.text().trim() == 'Block') {
                                    icon.removeClass('fg-red');
                                }

                                if(btn.text().trim() == 'Favorite') {
                                    icon.removeClass('fg-red');
                                }
                            }
                        }
                    }
                },
                function(error){
                    MD.dialog('Notice', 'Error occured. please try again later.');
                },
                function(){
                    // Do nothing
                }
            , 0);
        });
    });
//-->
</script>