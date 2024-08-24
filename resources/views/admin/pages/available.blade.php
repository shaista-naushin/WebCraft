@foreach($pages as $page)
<div class="col-3">
    <div class="card">
        <div class="card-body tx-center">
            <p class="tx-12 tx-uppercase tx-semibold tx-spacing-1 tx-color-02">{{$page->name}}</p>
          <img width="100%" src="{{$page->preview_image}}"/>
        </div>
        <div class="card-footer bd-t-0 pd-t-0">
            <a role="button" target="_blank" href="/pages/view/{{$page->id}}" class="btn btn-sm btn-block btn-outline-primary btn-uppercase tx-spacing-1">View</a>
            <button data-id="{{$page->id}}" type="button" class="page_select btn btn-sm btn-block btn-outline-primary btn-uppercase tx-spacing-1">Select</button>
            <button data-id="{{$page->id}}" type="button" class="d-none page_unselect btn btn-sm btn-block btn-primary btn-uppercase tx-spacing-1">Remove</button>
        </div>
    </div>
</div>
@endforeach
