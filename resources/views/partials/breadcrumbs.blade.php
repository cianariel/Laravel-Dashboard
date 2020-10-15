{{-- Stored in /resources/views/admin/partials/breadcrumbs.blade.php--}}
@hasSection('breadcrumbs')
<nav class="gb-breadcrumbs clearfix" aria-label="breadcrumb">
  <ol class="breadcrumb">
    @yield('breadcrumbs')
  </ol>
</nav>
@endif