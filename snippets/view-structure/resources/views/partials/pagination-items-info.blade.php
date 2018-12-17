@if($paginator->total()>0)
    Showing {!! $paginator->firstItem() !!} to {!! $paginator->lastItem() !!} of {!! $paginator->total() !!} entries
@else
    Showing 0 to 0 of 0 entries
@endif