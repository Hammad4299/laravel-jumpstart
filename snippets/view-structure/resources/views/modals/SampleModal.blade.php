<div class="modal fade" id="widget-manage-modal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary2 color-text-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="js-title modal-title"></h4>
            </div>
            <div class="modal-body js-message">
                <form class="form-horizontal form-label-left js-ajax-form" action="{!! route('') !!}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" name="id" />
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Name" />
                        <div class="form-error" data-error="name"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" readonly class="form-control" name="widget_token" placeholder="Widget Token" />
                        <div class="form-error" data-error="widget_token"></div>
                    </div>
                    <div class="form-group">
                        <select name="widget_type" class="form-control">
                            @foreach($widget_types as $type)
                                <option value="{{ $type['identifier'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="widget_type" />
                        <div class="form-error" data-error="widget_type"></div>
                    </div>
                </form>
                <div class="js-setting-container"></div>
            </div><!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success js-submit-form">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->