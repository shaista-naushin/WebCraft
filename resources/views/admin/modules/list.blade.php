@extends('layout-backend.master')

@section('styles')
    <link href="/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="modal fade" id="installModal" tabindex="-1" role="dialog" aria-labelledby="installModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="installModalLabel">Install New Module</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/modules/install" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="customFile">Select module(.zip file)</label>
                            <input type="file" name="module" class="form-control file-fix"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Install</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Modules</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <button data-toggle="modal" data-target="#installModal"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i data-feather="upload" class="wd-10 mg-r-5"></i> Install Module
                    </button>
                </div>

                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Alias</th>
                        <th>System Module</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th>Enable/Disable</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td>{{$module['name']}}</td>
                            <td>{{$module['description']}}</td>
                            <td>{{$module['alias']}}</td>
                            <td>
                                @if($module['system_module'])
                                    <span
                                        class="badge badge-primary">Yes</span>
                                @else
                                    <span
                                        class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($module['status'])
                                    <span
                                        class="badge badge-primary">Enabled</span>
                                @else
                                    <span
                                        class="badge badge-secondary">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    @if(!is_null($module['menu']))
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i data-feather="settings" class="wd-10 mg-r-5"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @foreach($module['menu'] as $menu_item)
                                                <a class="dropdown-item"
                                                   href="{{$menu_item['url']}}"> {{$menu_item['title']}}</a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span
                                            class="badge badge-secondary">No actions available</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if(!$module['system_module'])
                                    @if($module['status'])
                                        <a href="/admin/modules/disable/{{$module['alias']}}" class="btn btn-warning"><i
                                                data-feather="x" class="wd-10 mg-r-5"></i> Disable</a>
                                    @else
                                        <a href="/admin/modules/enable/{{$module['alias']}}" class="btn btn-primary"><i
                                                data-feather="check" class="wd-10 mg-r-5"></i> Enable</a>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if(!$module['system_module'])
                                    <a href="/admin/modules/delete/{{$module['alias']}}" class="btn btn-danger"><i
                                            data-feather="trash" class="wd-10 mg-r-5"></i> Delete</a>
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
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@stop


