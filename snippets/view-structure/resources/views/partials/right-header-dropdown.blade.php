@if(Auth::check())
    <li class="">
        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
           aria-expanded="false">
            My username
            <span class=" fa fa-angle-down"></span>
        </a>

        <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="{{ route('admin.user.account') }}"><i class="fa fa-user pull-right"></i>Profile</a>
            </li>
            <li><a href="{{ route('admin.user.logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
        </ul>
    </li>
@endif