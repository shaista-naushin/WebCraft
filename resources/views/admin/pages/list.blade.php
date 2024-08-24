@extends('layout-backend.master')

@section('styles')
    <link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Pages</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <a href="/pages/create" role="button"
                       class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i data-feather="plus" class="wd-10 mg-r-5"></i> Create New Page
                    </a>
                </div>

                <table class="table" id="dataTable">
                    <thead>
                    <tr>
                        @if(auth()->user()->role === 'admin')
                            <th>Preview Image</th>
                        @endif
                        <th>Name</th>
                        <th>Title</th>
                        <th>View</th>
                        <th>Editor</th>
                        <th>Enable/Disable</th>
                        <th>Duplicate</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pages as $page)
                        <tr>
                            @if(auth()->user()->role === 'admin')
                                <td><img class="w-100px" src="{{$page->preview_image}}"/></td>
                            @endif
                            <td>{{$page->name}}</td>
                            <td>{{$page->title}}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" target="_blank" href="/pages/view/{{$page->id}}">
                                    <i class="fas fa-eye"></i>
                                    View</a>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/pages/editor/{{$page->id}}">
                                    Editor</a>
                            </td>
                            <td>
                                @if($page->status == 1)
                                    <a class="btn btn-warning btn-sm" href="/pages/disable/{{$page->id}}">
                                        Disable</a>
                                @else
                                    <a class="btn btn-primary btn-sm" href="/pages/enable/{{$page->id}}">
                                        Enable
                                    </a>
                                @endif
                            </td>

                            <td>
                                <a class="btn btn-primary btn-sm" href="/pages/duplicate/{{$page->id}}">
                                    Duplicate
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/pages/edit/{{$page->id}}">
                                    Edit
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="/pages/delete/{{$page->id}}">
                                    Delete
                                </a>
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
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@stop
