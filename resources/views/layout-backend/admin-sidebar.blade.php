<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <li>
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-globe"></i>
            </div>
            <div class="sidebar-brand-text mx-3">
                Webcraft
            </div>
        </a>
    </li>

    <li>
        <hr class="sidebar-divider my-0">
    </li>

    <li class="nav-item {{request()->getRequestUri() === '/dashboard' ? 'active':''}}">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/admin/users/') ? 'active':''}}">
        <a href="/admin/users/list" class="nav-link">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/form-data/') ? 'active':''}}">
        <a href="/form-data/list" class="nav-link"><i class="fas fa-fw fa-bar-chart"></i> <span>Form Data</span></a>
    </li>

    <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/admin/components/') ? 'active':''}}">
        <a href="/admin/components/list" class="nav-link"><i class="fas fa-fw fa-boxes"></i> <span>Components</span></a>
    </li>

    @if(\App\Models\Utils::setting(\App\Models\Settings::DEVELOPER_MODE) == 1)
        <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/admin/blocks/') ? 'active':''}}">
            <a href="/admin/blocks/list" class="nav-link"><i class="fas fa-fw fa-boxes"></i> <span>Blocks</span></a>
        </li>
    @endif

    @if(\App\Models\Utils::setting(\App\Models\Settings::DEVELOPER_MODE) == 1)
        <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/admin/plugins/') ? 'active':''}}">
            <a href="/admin/plugins/list" class="nav-link"><i class="fas fa-fw fa-boxes"></i> <span>Plugins</span></a>
        </li>
    @endif

    <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/pages/') ? 'active':''}}">
        <a href="/pages/list" class="nav-link"><i class="fas fa-fw fa-edit"></i> <span>Pages</span></a>
    </li>

    <li class="nav-item {{str_starts_with(request()->getRequestUri(), '/popup/') ? 'active':''}}">
        <a href="/popup/list" class="nav-link"><i class="fas fa-fw fa-maximize"></i> <span>Popup Builder</span></a>
    </li>

    <li class="nav-item {{request()->getRequestUri() === '/admin/modules/list' ? 'active':''}}">
        <a href="/admin/modules/list" class="nav-link"><i class="fas fa-fw fa-box-open"></i> <span>Modules</span></a>
    </li>

    @foreach(\App\Helpers\Menu::getModulesMenu() as $item)
        <li class="nav-item {{request()->getRequestUri() === $item['url'] ? 'active':''}}">
            <a href="{{isset($item['url']) ? $item['url']: ''}}" class="nav-link">
                @if(isset($item['feather_icon']))
                    <i data-feather="{{$item['feather_icon']}}"></i>
                @endif

                @if(isset($item['fa_icon']))
                    <i class="{{$item['fa_icon']}}"></i>
                @endif
                <span>{{isset($item['title']) ? $item['title']: ''}}</span>
            </a>
        </li>
    @endforeach

    <li class="nav-item">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="nav-link"><i
                class="fas fa-fw fa-sign-out"></i> <span>Logout</span></a>
    </li>
</ul>
