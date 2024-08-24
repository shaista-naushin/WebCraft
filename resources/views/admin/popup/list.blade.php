@extends('layout-backend.master')

@section('styles')
    <link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Popup List</h5>
            </div>
            <div class="card-body">

                @include('layout-backend.notify')

                <div class="d-flex mb-4">
                    <a href="/popup/create" role="button"
                       class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i data-feather="plus" class="wd-10 mg-r-5"></i> Create New Popup
                    </a>
                </div>

                <table class="table" id="dataTable">
                    <thead>
                    <tr>
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
                    @foreach($popup as $pop)
                        <tr>
                            <td>{{$pop->name}}</td>
                            <td>{{$pop->title}}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" target="_blank" href="/popup/view/{{$pop->id}}"><i data-feather="eye"
                                                                                                                     class="wd-10 mg-r-5"></i>
                                    View</a>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/popup/editor/{{$pop->id}}"><i data-feather="layout"
                                                                                                       class="wd-10 mg-r-5"></i>
                                    Editor</a>
                            </td>
                            <td>
                                @if($pop->status == 1)
                                    <a class="btn btn-warning btn-sm" href="/popup/disable/{{$pop->id}}"><i data-feather="x"
                                                                                                            class="wd-10 mg-r-5"></i>
                                        Disable</a>
                                @else
                                    <a class="btn btn-primary btn-sm" href="/popup/enable/{{$pop->id}}"><i
                                            data-feather="check" class="wd-10 mg-r-5"></i> Enable</a>
                                @endif
                            </td>

                            <td>
                                <a class="btn btn-primary btn-sm" href="/popup/duplicate/{{$pop->id}}"><i
                                        data-feather="copy"
                                        class="wd-10 mg-r-5"></i>
                                    Duplicate</a>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/popup/edit/{{$pop->id}}"><i data-feather="edit"
                                                                                                     class="wd-10 mg-r-5"></i>
                                    Edit</a>
                            </td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="/popup/delete/{{$pop->id}}"><i data-feather="x"
                                                                                                      class="wd-10 mg-r-5"></i>
                                    Delete</a>
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
