<style>
    .nav.side-menu li.active, .nav.side-menu li:hover {
        background-color: rgba(255,255,255,.06);
    }
</style>
@php
    $currentRoute = request()->route()->getName();
    $activeLinks = [
        'widgets'=>in_array($currentRoute,['admin.widget.index'])
    ];

@endphp

<div class="col-md-3 left_col" style="position: fixed;top:0px;">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('root') }}" class="site_title">{!! config('app.name') !!}</a>
        </div>

        <div class="profile">
            {{--<div class="profile_pic">--}}
                {{--<img src="images/img.jpg" alt="..." class="img-circle profile_img">--}}
            {{--</div>--}}
            {{--<div class="profile_info">--}}
                {{--<span>Welcome,</span>--}}
                {{--<h2>{{ Auth::check() ? Auth::user()->user->name : 'Username' }}</h2>--}}
            {{--</div>--}}
        </div>
        <div class="clearfix"></div>
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                {{--<h3>Main</h3>--}}
                <ul class="nav side-menu">
                    <li class="nav {{ $activeLinks['widgets'] ? 'active' : '' }}">
                        <a href="{{ route('admin.widget.index') }}">
                            <i class="fa fa-home"></i> Widgets
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="margin-top:57px"></div>