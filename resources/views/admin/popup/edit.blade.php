@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your popups</h5>
@stop

@section('styles')
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"
    />
@endsection

@section('content')
    <div id="testPopup" class="modal" tabindex="-1" role="dialog">
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

    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Popup Builder</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <a role="button" href="/popup/list"
                       class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i class="fas fa-arrow-left"></i>
                        Back to list
                    </a>
                </div>

                @include('layout-backend.notify')

                <form action="/popup/edit/{{$popup->id}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="d-block">Name</label>
                        <input type="text" name="name" id="name" value="{{old('name', $popup->name)}}" required class="form-control"
                               placeholder="Enter name">
                    </div>

                    <div class="form-group">
                        <label for="title" class="d-block">Title</label>
                        <input type="text" name="title" id="title" value="{{old('title', $popup->title)}}" required
                               class="form-control"
                               placeholder="Enter title">
                    </div>

                    <div class="form-group">
                        <label for="type" class="d-block">Type</label>
                        <select class="form-control" required name="type" id="type">
                            @foreach(\App\Models\Popup::TYPES as $type)
                                <option {{old('type', $popup->type) === $type['id'] ? 'selected':''}} value="{{$type['id']}}">{{$type['label']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="delayBlock" class="form-group">
                        <label for="delay" class="d-block">Popup Delay(seconds)</label>
                        <input type="number" min="1" name="delay" id="delay" value="{{old('delay', $popup->delay)}}"
                               class="form-control"
                               placeholder="Enter popup delay in seconds">
                    </div>

                    <div class="form-group">
                        <label for="animation" class="d-block">Animation</label>
                        <select class="form-control" required name="animation" id="animation">
                            @foreach(\App\Models\Popup::ANIMATION_IN as $animate)
                                <option {{old('animation', $popup->animation) === $animate['id'] ? 'selected':''}} value="{{$animate['id']}}">{{$animate['label']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" id="testAnimation" class="btn btn-primary">Test Animation</button>

                    <div class="row  justify-content-center">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        let typeValue = $('#type').find('option:selected').val();
        if (typeValue == 'show_after_sometime') {
            $('#delayBlock').show();
        } else {
            $('#delayBlock').hide();
        }

        $('#testAnimation').on('click', function () {
            let animationValue = $('#animation').find('option:selected').val();
            $("#testPopup").removeClass();
            $('#testPopup').addClass(animationValue);
            $('#testPopup').addClass('modal');
            $('#testPopup').addClass('animate__animated');
            $('#testPopup').modal('show');
        });

        $('#type').on('change', function () {
            let typeValue = $('#type').find('option:selected').val();
            if (typeValue == 'show_after_sometime') {
                $('#delayBlock').show();
            } else {
                $('#delayBlock').hide();
            }
        });
    </script>
@endsection

