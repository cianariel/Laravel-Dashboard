{{-- Stored in resources/views/frontend/layouts/partials/footer.blade.php --}}
{{--[footer]--}}
<footer id="md-footer" class="align-self-end w-100 h-100 bg-green fg-white">
    <div class="container">
        <div class="row">
            <div class="d-block links-block fg-light-grey-alt text-center pt-3 pb-1">
                 @component('components.elements.menu_list', ['menu' =>  menu('footer-bottom'), 'heading' => false])@endcomponent
            </div>
            <div class="col-12 text-center">
                <span class="fg-white font-xs-size">&copy; {{ current_year() }} {{ __('MD Rep Time, LLC.') }} {{ __('All Rights Reserved.') }}</span>
            </div>
        </div>
    </div>
</footer>
{{--[/footer]--}}