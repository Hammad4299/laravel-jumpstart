<div>
    <div class="nav_menu">
        <nav>
            <ul class="nav navbar-nav navbar-left">
                <li class="">
                    <a href="{{ route('') }}"><h4>{{ config('app.name') }}</h4></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @include('partials.right-header-dropdown')
            </ul>
        </nav>
    </div>
</div>
