{{-- Stored in /resources/views/admin/partails/header.blade.php --}}
{{--[header]--}}
<div id="md-admin-header">
    <div class="row">
        <div class="col-2">
            <div class="d-block">
                <a class="md-sidebar-toggler fg-white d-inline-block" href="#"><i class="icon fas fa-bars"></i></a>
            </div>
        </div>
        <div class="col-10">
            <div class="d-block text-right pr-3">
                @include('admin.partials.navigation')
            </div>
        </div>
    </div>
</div>
{{--[/header]--}}