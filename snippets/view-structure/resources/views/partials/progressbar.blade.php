<div class="col-sm-12 padding-0 js-progressbar-container" style="display: none;margin-top: 10px">
    <div class="col-sm-12 padding-0 text-center" >
        <div class="progress" >
            <div class="progress-bar progress-bar-success js-progressbar" role="progressbar"
                 aria-valuemin="0" aria-valuemax="100" style="width:10%">
            </div>
        </div>
        <img src="{{ URL::asset('/images/LoaderIcon.gif') }}" width="25" height="25" />
        <div class="js-ajax-inprogress-message"></div>
    </div>
</div>