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
                    <h5 class="modal-title" id="installModalLabel">Install New Component</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/components/install" method="post" enctype="multipart/form-data">
                        @csrf

                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="name">Name</label>--}}
                        {{--                            <input required type="text" id="name" class="form-control" name="name"/>--}}
                        {{--                        </div>--}}

                        <div class="form-group">
                            <label>Select component file(.zip file)</label>
                            <input required type="file" name="component" class="form-control file-fix"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Install</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New Component</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/components/create" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input required type="text" id="name" class="form-control" name="name"/>
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <input required type="text" id="category" class="form-control" name="category"/>
                        </div>

                        <div class="form-group">
                            <label for="html">HTML</label>
                            <textarea name="html" id="html" class="form-control"
                                      placeholder=""></textarea>
                        </div>

                        <div class="form-group">
                            <label for="custom_css">CSS</label>
                            <textarea name="custom_css" id="custom_css" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Paste css code without style tag</small>
                        </div>

                        <div class="form-group">
                            <label for="custom_js">JS</label>
                            <textarea name="custom_js" id="custom_js" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Paste script without script tag</small>
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <input required type="file" id="icon" class="form-control file-fix" name="icon"/>
                            <small class="form-text text-muted">Upload an image file to show as icon for this component, it can be png, jpeg, jpg or svg file</small>
                        </div>

                        <div class="form-group">
                            <label for="preview_image">Preview Image</label>
                            <input required type="file" id="preview_image" class="form-control file-fix" name="preview_image"/>
                            <small class="form-text text-muted">Upload an image file to show preview for this component, it can be png, jpeg, jpg or svg file</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Components</h5>
            </div>
            <div class="card-body">

                @include('layout-backend.notify')

                <div class="d-flex mb-4">
                    <button data-toggle="modal" data-target="#createModal"
                            class="btn btn-sm pd-x-15 btn-outline-primary btn-uppercase mg-l-5">
                        <i class="fas fa-plus"></i> Create Component
                    </button>

                    <button data-toggle="modal" data-target="#installModal"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase ml-2">
                        <i class="fas fa-upload"></i> Install Component
                    </button>
                </div>

                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Enable/Disable</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($components as $component)
                        <tr>
                            <td><img src="{{$component->component_img}}" class="w-100px"/></td>
                            <td>{{$component->name}}</td>
                            <td>
                                @if($component->status)
                                    <span
                                        class="badge badge-primary">Enabled</span>
                                @else
                                    <span
                                        class="badge badge-secondary">Disabled</span>
                                @endif
                            </td>
                            <td>
                                @if($component->status)
                                    <a href="/admin/components/disable/{{$component->id}}" class="btn btn-warning"><i
                                            data-feather="x" class="wd-10 mg-r-5"></i> Disable</a>
                                @else
                                    <a href="/admin/components/enable/{{$component->id}}" class="btn btn-primary"><i
                                            data-feather="check" class="wd-10 mg-r-5"></i> Enable</a>
                                @endif
                            </td>
                            <td>
                                <a href="/admin/components/edit/{{$component->id}}" class="btn btn-primary"><i
                                        data-feather="edit" class="wd-10 mg-r-5"></i> Edit</a>
                            </td>
                            <td>
                                <a href="/admin/components/delete/{{$component->id}}" class="btn btn-danger"><i
                                        data-feather="trash" class="wd-10 mg-r-5"></i> Delete</a>
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

