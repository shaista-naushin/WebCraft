@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your pages</h5>
@stop

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

                <form action="/pages/edit/{{$page->id}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <input type="hidden" name="default_page_preview" value="{{$page->preview_image}}"/>

                    @if(auth()->user()->role === 'admin')
                        <div class="form-group">
                            <label for="type" class="d-block">Type</label>
                            <select class="form-control" required name="type" id="type">
                                @foreach(\App\Models\Page::TYPES as $type)
                                    <option
                                        {{old('type', $page->type) === $type ? 'selected':''}} value="{{$type}}">{{ucwords($type)}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name" class="d-block">Name</label>
                        <input type="text" name="name" id="name" value="{{old('name', $page->name)}}" required class="form-control"
                               placeholder="Enter name">
                    </div>

                    <div class="form-group">
                        <label for="title" class="d-block">Title</label>
                        <input type="text" name="title" id="title" value="{{old('title', $page->title)}}" class="form-control"
                               placeholder="Enter page title">
                    </div>

                    <div class="form-group">
                        <label for="description" class="d-block">Description</label>
                        <textarea name="description" id="description" class="form-control"
                                  placeholder="Enter page description">{{old('description', $page->description)}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="keywords" class="d-block">Keywords</label>
                        <input type="text" name="keywords" id="keywords" value="{{old('keywords', $page->keywords)}}"
                               class="form-control"
                               placeholder="Enter page keywords">
                    </div>

                    {{--                    <div class="form-group">--}}
                    {{--                        <label for="redirect_page" class="d-block">Redirect To</label>--}}
                    {{--                        <input type="text" name="redirect_page" id="redirect_page" value="{{old('redirect_page', $page->redirect_page)}}"--}}
                    {{--                               class="form-control"--}}
                    {{--                               placeholder="Enter redirect to url">--}}
                    {{--                        <small class="form-text text-muted">User will be redirected to this page after form submission,--}}
                    {{--                            it can be a thank you page or confirmation page</small>--}}
                    {{--                    </div>--}}

                    <div class="form-group">
                        <label for="popup" class="d-block">Popup</label>
                        <select class="form-control" name="popup" id="popup">
                            <option>Select a popup for this page ...</option>
                            @foreach($popups as $popup)
                                <option {{old('popup', $page->popup) === $popup->id ? 'selected':''}} value="{{$popup->id}}">{{$popup->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="form-group">
                            <label for="page_preview" class="d-block">Page Preview</label>
                            <input type="file" name="page_preview" id="page_preview">
                            <small class="form-text text-muted">Image file can only be png, jpeg, jpg, gif</small>

                            <img class="w-100px" src="{{$page->preview_image}}"/>
                        </div>
                    @endif

                    <div class="row justify-content-center">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
