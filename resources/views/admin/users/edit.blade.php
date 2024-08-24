@extends('layout-backend.master')

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Edit User - {{$user->name}}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <a role="button" href="/admin/users/list"
                       class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i class="fas fa-arrow-left"></i>
                        Back to list
                    </a>
                </div>

                @include('layout-backend.notify')

                <form action="/admin/users/edit/{{$user->id}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <input type="hidden" name="avatar_url" value="{{$user->avatar}}"/>

                    <div class="form-group">
                        <label for="role" class="d-block">Role</label>
                        <select class="form-control" required name="role" id="role">
                            <option {{old('role', $user->role == 'admin') == 'admin' ? 'selected':''}} value="admin">Admin</option>
                            <option {{old('role', $user->role == 'user') == 'user' ? 'selected':''}} value="user">User</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name" class="d-block">Name</label>
                        <input type="text" name="name" id="name" value="{{old('name', $user->name)}}" required class="form-control"
                               placeholder="Enter name">
                    </div>

                    <div class="form-group">
                        <label for="email" class="d-block">Email</label>
                        <input type="email" name="email" required id="email" value="{{old('email', $user->email)}}"
                               class="form-control" placeholder="Enter email">
                    </div>

                    <div class="form-group">
                        <label for="avatar" class="d-block">Avatar</label>

                        <img class="w-100px" src="{{$user->avatar}}">
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="avatar">
                            <label class="custom-file-label" for="avatar">Choose image file (png, jpeg, jpg,
                                gif)</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" {{$user->activated ? 'checked':''}} name="activated" id="activated"> Activated
                    </div>

                    <div class="form-group">
                        <input type="checkbox" {{$user->email_verified_at ? 'checked':''}} name="verified" id="verified"> Verified
                    </div>

                    <div class="row  justify-content-center">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
