@extends('layout-backend.master')

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Settings</h5>
            </div>
            <div class="card-body">
                @include('layout-backend.notify')

                <form action="/admin/modules/website/settings" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="home_page" class="d-block">Home Page</label>
                        <select class="form-control" required name="home_page" id="home_page">
                            <option value="">Select your home page ...</option>
                            @foreach($pages as $page)
                                <option {{old('home_page', $home_page) == $page->id ? 'selected':''}} value="{{$page->id}}">{{$page->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_register"
                                       id="can_register" value="1" {{ old('can_register', $can_register) ? 'checked' : '' }}>

                                <label class="form-check-label ml-2" for="can_register">
                                    Users Can Register
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row  justify-content-center">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
