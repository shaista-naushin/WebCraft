@extends('layout-backend.master')

@section('styles')
    <link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Form Data</h5>
            </div>
            <div class="card-body">
                <table class="table" id="dataTable">
                    <thead>
                    <tr>
                        <th>Page</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Data</th>
                        <th>Captured On</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($formData as $data)
                        <tr>
                            <td>{{$data->page->name}}</td>
                            <td>{{$data->name}}</td>
                            <td>{{$data->email}}</td>
                            <td>
                                <ul style="list-style: none;">
                                    @foreach(json_decode($data->extra) as $key => $value)
                                        @if(!is_null($value) && strlen($value) > 0 && !str_starts_with($key, "x-") && $key != 'page_id')
                                            <li>{{ucwords($key)}}: {{$value}}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                {{$data->created_at->toDayDateTimeString()}}
                            </td>
                            <td>
                                <a class="btn btn-danger" href="/form-data/delete/{{$data->id}}"><i data-feather="x"
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
