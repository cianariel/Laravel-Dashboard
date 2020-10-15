{{-- Stored in resources/views/admin/partials/sidebar.blade.php --}}
<nav id="gb-admin-sidebar" class="card gb-sidebar border-0 mt-0 mb-0 mr-0" data-hidden="false">
    <div class="card-body p-0">
        <div class="row h-100">
            {{--[menu]--}}
            <div class="sidebar-menu col-12 h-100 pb-3">
                <div class="logo-block text-center">
                    <a class="site-link" href="{{ admin_url() }}"><img src="{{ asset('images/logo.png') }}"></a>
                </div>
                <div class="pt-2 pb-2 avator-sidebar-wrapper">
                    <div class="profile-image text-center rounded-circle">
                        <img src="{{ avator(auth()->user()->select(['id'])->firstOrFail(), 'thumb') }}">
                        <div class="overlay position-absolute align-items-center text-center justify-content-center">
                            <a class="fg-white" href="{{ route('admin.profile.edit') }}">{{ __('Edit') }} <i class="far fa-edit"></i></a>
                        </div>
                    </div>
                    <h5 class="role-name mt-2 mb-0 ml-0 mr-0 p-0 text-center font-sm-size font-weight-normal">{{ __('Administrator') }}</h5>
                </div>
                <ul class="sidebar-menu-list list-unstyled pl-4">
                    {{--[dashboard]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-dashboard" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-tachometer-alt"></i> <span class="align-middle">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    {{--[/dashboard]--}}
                    {{--[analytics]
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-analytics" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-chart-bar"></i> <span class="align-middle">{{ __('Analytics') }}</span>
                        </a>
                        <ul id="admin-analytics" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ secure_url('#') }}">{{ __('Reports') }}</a></li>
                        </ul>
                    </li>
                    [/analytics]--}}
                    {{--[users]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-users" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-users"></i> <span class="align-middle">{{ __('Users') }}</span>
                        </a>
                        <ul id="admin-users" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.users.create') }}">{{ __('Add User') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.users.index') }}">{{ __('All Users') }}</a></li>
                        </ul>
                    </li>
                    {{--[/users]--}}
                    {{--[roles]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-roles" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-shield-alt"></i> <span class="align-middle">{{ __('Roles') }}</span>
                        </a>
                        <ul id="admin-roles" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.roles.create') }}">{{ __('Add Role') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.roles.index') }}">{{ __('All Roles') }}</a></li>
                        </ul>
                    </li>
                    {{--[/roles]--}}
                    {{--[folders]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-folders" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-folder-open"></i> <span class="align-middle">{{ __('Folders') }}</span>
                        </a>
                        <ul id="admin-folders" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.folders.create') }}">{{ __('Add Folder') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.folders.index') }}">{{ __('All Folders') }}</a></li>
                        </ul>
                    </li>
                    {{--[/folders]--}}
                    {{--[groups]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-groups" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-layer-group"></i> <span class="align-middle">{{ __('Groups') }}</span>
                        </a>
                        <ul id="admin-groups" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.groups.create') }}">{{ __('Add Group') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.groups.index') }}">{{ __('All Groups') }}</a></li>
                        </ul>
                    </li>
                    {{--[/groups]--}}
                    {{--[menus]--}}
                    <li class="siderbar-item sidebar-item-parent">
                        <a href="#admin-menus" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-bars"></i> <span class="align-middle">{{ __('Menus') }}</span>
                        </a>
                        <ul id="admin-menus" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.menus.create') }}">{{ __('Add Menu') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.menus.index') }}">{{ __('All Menus') }}</a></li>
                        </ul>
                    </li>
                    {{--[/menus]--}}
                    {{--[pages]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-pages" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-file"></i> <span class="align-middle">{{ __('Pages') }}</span>
                        </a>
                        <ul id="admin-pages" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.pages.create') }}">{{ __('Add Page') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.pages.index') }}">{{ __('All Pages') }}</a></li>
                        </ul>
                    </li>
                    {{--[/pages]--}}
                    {{--[blogs]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-blog" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-blog"></i> <span class="align-middle">{{ __('Blog') }}</span>
                        </a>
                        <ul id="admin-blog" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.blogs.create') }}">{{ __('Add Blog') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.blogs.index') }}">{{ __('All Blogs') }}</a></li>
                        </ul>
                    </li>
                    {{--[/blogs]--}}
                    {{--[packages]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-packages" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-box-open"></i> <span class="align-middle">{{ __('Packages') }}</span>
                        </a>
                        <ul id="admin-packages" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.packages.create') }}">{{ __('Add Package') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.packages.index') }}">{{ __('All Packages') }}</a></li>
                        </ul>
                    </li>
                    {{--[/packages]--}}
                    {{--[products]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-products" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-shopping-cart"></i> <span class="align-middle">{{ __('Products') }}</span>
                        </a>
                        <ul id="admin-products" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.products.create') }}">{{ __('Add Product') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.products.index') }}">{{ __('All Products') }}</a></li>
                        </ul>
                    </li>
                    {{--[/products]--}}
                    {{--[orders]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-orders" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-file-invoice-dollar"></i> <span class="align-middle">{{ __('Orders') }}</span>
                        </a>
                        <ul id="admin-orders" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ secure_url('#') }}">{{ __('All Orders') }}</a></li>
                        </ul>
                    </li>
                    {{--[/orders]--}}
                    {{--[payments]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-payments" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-money-bill"></i> <span class="align-middle">{{ __('Payments') }}</span>
                        </a>
                        <ul id="admin-payments" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ secure_url('#') }}">{{ __('All Payments') }}</a></li>
                        </ul>
                    </li>
                    {{--[/payments]--}}
                    {{--[subscriptions]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-subscriptions" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-sync"></i> <span class="align-middle">{{ __('Subscriptions') }}</span>
                        </a>
                        <ul id="admin-subscriptions" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.subscriptions.index') }}">{{ __('All Subscriptions') }}</a></li>
                        </ul>
                    </li>
                    {{--[/subscriptions]--}}
                    {{--[transactions]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-transactions" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-cash-register"></i> <span class="align-middle">{{ __('Transactions') }}</span>
                        </a>
                        <ul id="admin-transactions" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ secure_url('#') }}">{{ __('All Transactions') }}</a></li>
                        </ul>
                    </li>
                    {{--[/transaction]--}}
                    {{--[support-tickets]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-support-tickets" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-ticket-alt"></i> <span class="align-middle">{{ __('Support Tickets') }}</span>
                        </a>
                        <ul id="admin-support-tickets" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ secure_url('#') }}">{{ __('All Support Tickets') }}</a></li>
                        </ul>
                    </li>
                    {{--[/support-tickets]--}}
                    {{--[redirects]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-redirects" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-directions"></i> <span class="align-middle">{{ __('Redirects') }}</span>
                        </a>
                        <ul id="admin-redirects" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.redirects.create') }}">{{ __('Add Redirect') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.redirects.index') }}">{{ __('All Redirects') }}</a></li>
                        </ul>
                    </li>
                    {{--[/redirects]--}}
                    {{--[settings]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-settings" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-cog"></i> <span class="align-middle">{{ __('Settings') }}</span>
                        </a>
                        <ul id="admin-settings" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.settings.group', ['group' => 'site']) }}">{{ __('Site') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.settings.group', ['group' => 'theme']) }}">{{ __('Theme') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.settings.index') }}">{{ __('All Settings') }}</a></li>
                        </ul>
                    </li>
                    {{--[/settings]--}}
                    {{--[countries]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-countries" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-globe-americas"></i> <span class="align-middle">{{ __('Countries') }}</span>
                        </a>
                        <ul id="admin-countries" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.countries.create') }}">{{ __('Add Country') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.countries.index') }}">{{ __('All Countries') }}</a></li>
                        </ul>
                    </li>
                    {{--[/countries]--}}
                    {{--[currencies]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-currencies" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-coins"></i> <span class="align-middle">{{ __('Currencies') }}</span>
                        </a>
                        <ul id="admin-currencies" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.currencies.create') }}">{{ __('Add Currency') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.currencies.index') }}">{{ __('All Currencies') }}</a></li>
                        </ul>
                    </li>
                    {{--[/currencies]--}}
                    {{--[timezones]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-timezones" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-clock"></i> <span class="align-middle">{{ __('Timezones') }}</span>
                        </a>
                        <ul id="admin-timezones" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.timezones.create') }}">{{ __('Create Timezone') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.timezones.index') }}">{{ __('All Timezones') }}</a></li>
                        </ul>
                    </li>
                    {{--[/timezones]--}}
                    {{--[system]--}}
                    <li class="sidebar-item sidebar-item-parent">
                        <a href="#admin-system" data-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="align-middle mr-2 fas fa-server"></i> <span class="align-middle">{{ __('System') }}</span>
                        </a>
                        <ul id="admin-system" class="dropdown list-unstyled collapse pl-4 pt-2">
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.system.usage') }}">{{ __('Server Usage') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.system.cache') }}">{{ __('System Cache') }}</a></li>
                            <li class="sidebar-item pl-1"><a href="{{ route('admin.system.logs') }}">{{ __('Logs') }}</a></li>
                        </ul>
                    </li>
                    {{--[/system]--}}
                </ul>
            </div>
            {{--[/menu]--}}
        </div>
    </div>
</nav>