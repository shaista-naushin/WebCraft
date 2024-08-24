@extends('layout-backend.master')

@section('styles')
    <link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog"
         aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/users/change_password" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password">
                        </div>

                        <div class="form-group">
                            <label>Confirm New password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>

                        <input type="hidden" name="change_password_user_id" id="change_password_user_id" value=""/>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Form Data</h5>
            </div>
            <div class="card-body">

                @include('layout-backend.notify')

                <div class="d-block mb-4">
                    <a role="button" href="/admin/users/create" class="btn pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="plus"
                                                                                                                               class="wd-10 mg-r-5"></i>
                        Create New User
                    </a>
                </div>

                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
                    <tr>
                        <th>Login</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Email Verified</th>
                        <th>Status</th>
                        <th>Change Password</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                @if($user->id != auth()->user()->id)
                                    <a title="Login as this user" href="/admin/users/impersonate/{{$user->id}}" class="btn btn-primary btn-sm">Login</a>
                                @endif
                            </td>
                            <td>
                                <img style="width: 50px; display: inline" src="{{$user->avatar}}"/>
                                <span class="ml-2">{{$user->name}}</span>
                            </td>
                            <td>{{$user->email}}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span
                                        class="badge badge-primary">Admin</span>
                                @endif

                                @if($user->role === 'user')
                                    <span
                                        class="badge badge-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span
                                        class="badge badge-success">Verified</span>
                                @else
                                    <a href="/admin/users/send_verification/{{$user->id}}" type="role"
                                       class="btn btn-primary btn-sm"><i data-feather="mail" class="wd-10 mg-r-5"></i> Resent Verification</a>
                                @endif
                            </td>

                            <td>
                                @if($user->id !== auth()->user()->id)
                                    @if($user->activated)
                                        <a href="/admin/users/change_status/{{$user->id}}" class="btn btn-warning btn-sm"><i data-feather="x" class="wd-10 mg-r-5"></i> Deactivate</a>
                                    @else
                                        <a href="/admin/users/change_status/{{$user->id}}" class="btn btn-primary btn-sm"><i data-feather="check" class="wd-10 mg-r-5"></i> Activate</a>
                                    @endif
                                @else
                                    <span
                                        class="badge badge-success">Activated</span>
                                @endif
                            </td>

                            <td>
                                <a href="#changePasswordModal" onclick="setUserId({{$user->id}})" data-toggle="modal"
                                   class="btn btn-primary btn-sm"><i data-feather="lock" class="wd-10 mg-r-5"></i> Change Password</a>
                            </td>

                            <td>
                                <a href="/admin/users/edit/{{$user->id}}" class="btn btn-primary btn-sm"><i data-feather="edit" class="wd-10 mg-r-5"></i> Edit</a>
                            </td>

                            <td>
                                @if($user->id != auth()->user()->id)
                                    <a href="/admin/users/delete/{{$user->id}}" class="btn btn-danger btn-sm"><i data-feather="x" class="wd-10 mg-r-5"></i> Delete</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        function setUserId(userId) {
            $('#change_password_user_id').val(userId);
        }

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@stop
