{{-- Stored in resources/views/layouts/partials/dialog.blade.php --}}
{{--[modal]--}}
<div class="md-dialog-modal modal fade" id="dialog-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dialog-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="close-icon fas fa-times" aria-hidden></i>
        </button>
      </div>
      <div class="modal-body" id="dialog-content"></div>
      <div class="modal-footer">
        @yield('dialog-modal-buttons')
      </div>
    </div>
  </div>
</div>
{{--[/modal]--}}