@extends('layout-backend.master')

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Pages</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <a role="button" href="/pages/list"
                       class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i class="fas fa-arrow-left"></i>
                        Back to list
                    </a>
                </div>

                @include('layout-backend.notify')

                <form action="/pages/create" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="type" class="d-block">Type</label>
                        <select class="form-control" required name="type" id="type">
                            @foreach(\App\Models\Page::TYPES as $type)
                                <option
                                    {{old('type', 'blank') === $type ? 'selected':''}} value="{{$type}}">{{ucwords($type)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" id="page_selected" name="page_selected"/>

                    <div id="template_selection" class="row">

                    </div>

                    <div class="form-group">
                        <label for="name" class="d-block">Name</label>
                        <input type="text" name="name" id="name" value="{{old('name')}}" required class="form-control"
                               placeholder="Enter name">
                    </div>

                    <div class="form-group">
                        <label for="title" class="d-block">Title</label>
                        <input type="text" name="title" id="title" value="{{old('title')}}" class="form-control"
                               placeholder="Enter page title">
                    </div>

                    <div class="form-group">
                        <label for="description" class="d-block">Description</label>
                        <textarea name="description" id="description" class="form-control"
                                  placeholder="Enter page description">{{old('description')}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="keywords" class="d-block">Keywords</label>
                        <input type="text" name="keywords" id="keywords" value="{{old('keywords')}}"
                               class="form-control"
                               placeholder="Enter page keywords">
                    </div>

                    <div class="form-group">
                        <label for="popup" class="d-block">Popup</label>
                        <select class="form-control" name="popup" id="popup">
                            <option value="0">Select a popup for this page ...</option>
                            @foreach($popups as $popup)
                                <option value="{{$popup->id}}">{{$popup->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="form-group">
                            <label for="page_preview" class="d-block">Page Preview</label>
                            <input type="file" name="page_preview" id="page_preview">
                            <small class="form-text text-muted">Image file can only be png, jpeg, jpg, gif</small>
                        </div>
                    @endif

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

        $(document).on('click', '.page_select', (ev) => {
            $('.page_select').removeClass('d-none');
            $('.page_select').addClass('d-block');

            $('.page_unselect').addClass('d-none');

            $(ev.target).removeClass('d-block');
            $(ev.target).addClass('d-none');
            $(ev.target).next().removeClass('d-none');
            $(ev.target).next().removeClass('d-block');

            $('#page_selected').val($(ev.target).data('id'));
        });

        $(document).on('click', '.page_unselect', (ev) => {
            $('.page_select').removeClass('d-none');
            $('.page_select').addClass('d-block');

            $('.page_unselect').addClass('d-none');

            $('#page_selected').val(null);
        });

        $('#type').on('change', () => {
            let type = $('#type option:selected').val();

            if (type === 'blank') {
                $('#template_selection').html('');
            } else {
                $.get("/pages/available/" + type, function (data) {
                    console.log(data);
                    $('#template_selection').html(data.data);
                });
            }
        });
    </script>
@endsection
