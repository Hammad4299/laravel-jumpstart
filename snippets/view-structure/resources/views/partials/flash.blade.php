@if(Session::has(''))
    <div class="alert alert-info no-mb" style="width:100%;margin-top: -39px;margin-bottom: 39px;">
        <b>{{ Session::get('') }}</b>
        @php
            Session::remove('')
        @endphp
    </div>
@endif