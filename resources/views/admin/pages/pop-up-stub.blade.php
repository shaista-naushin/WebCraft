<div id="modal{{$popup->id}}" class="modal animate__animated {{$popup->animation}}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$popup->title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <style>
                    {!! $popup->css !!}
                </style>
                {!! $popup->html !!}
            </div>
        </div>
    </div>
</div>


