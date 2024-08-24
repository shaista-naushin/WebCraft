@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your plugins</h5>
@stop

@section('pre_styles')
    <link href="/lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/lib/select2/css/select2.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="modal fade" id="installModal" tabindex="-1" role="dialog" aria-labelledby="installModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="installModalLabel">Install New Plugin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/plugins/install" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input required type="text" id="name" class="form-control" name="name"/>
                        </div>

                        <div class="form-group">
                            <label for="unique_id">Plugin Name</label>
                            <input required type="text" id="unique_id" class="form-control" name="unique_id"/>
                            <small class="form-text text-muted">It is plugin name which needs to be loaded ex: for
                                GrapesJS Typed the identifier is <code>grapesjs-typed</code></small>
                        </div>

                        <div class="form-group">
                            <label for="custom_js">Custom JS</label>
                            <textarea name="custom_js" id="custom_js" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Enter links to your js files each on a new line, you can
                                host your js files on gist.github.com</small>
                        </div>

                        <div class="form-group">
                            <label for="custom_css">Custom CSS</label>
                            <textarea name="custom_css" id="custom_css" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Enter links to your css files each on a new line, you
                                can host your css files on gist.github.com</small>
                        </div>

                        <div class="form-group">
                            <label for="plugin_options">Plugin Options</label>
                            <textarea name="plugin_options" id="plugin_options" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Enter your plugin options as <code>'grapesjs-typed': { /* options */ }</code></small>
                        </div>

                        <button type="submit" class="btn btn-primary">Install</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="/admin/plugins/list">Plugins</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('layout-backend.notify')

        <div class="card">
            <div class="card-header">
                <h5 class="mg-b-0 tx-spacing--1">Plugins</h5>
            </div>
            <div class="card-body">
                <div class="d-none d-md-block float-right mb-4">
                    <button data-toggle="modal" data-target="#installModal"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i data-feather="upload" class="wd-10 mg-r-5"></i> Install Plugin
                    </button>
                </div>


                <table class="table table-bordered table-hover" id="dtList">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Identifier</th>
                        <th>Status</th>
                        <th>Enable/Disable</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($plugins as $plugin)
                        <tr>
                            <td>{{$plugin->name}}</td>
                            <td>{{$plugin->unique_id}}</td>
                            <td>
                                @if($plugin->status)
                                    <span
                                        class="badge badge-primary">Enabled</span>
                                @else
                                    <span
                                        class="badge badge-secondary">Disabled</span>
                                @endif
                            </td>
                            <td>
                                @if($plugin->status)
                                    <a href="/admin/plugins/disable/{{$plugin->id}}" class="btn btn-warning"><i
                                            data-feather="x" class="wd-10 mg-r-5"></i> Disable</a>
                                @else
                                    <a href="/admin/plugins/enable/{{$plugin->id}}" class="btn btn-primary"><i
                                            data-feather="check" class="wd-10 mg-r-5"></i> Enable</a>
                                @endif
                            </td>
                            <td>
                                <a href="/admin/plugins/edit/{{$plugin->id}}" class="btn btn-primary"><i
                                        data-feather="edit" class="wd-10 mg-r-5"></i> Edit</a>
                            </td>
                            <td>
                                <a href="/admin/plugins/delete/{{$plugin->id}}" class="btn btn-danger"><i
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
    <script src="/lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="/lib/select2/js/select2.min.js"></script>
    <script>
        $(function () {
            'use strict'

            $('#dtList').DataTable({
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                }
            });

            // Select2
            $('.dataTables_length select').select2({minimumResultsForSearch: Infinity});

        });
    </script>
@stop

