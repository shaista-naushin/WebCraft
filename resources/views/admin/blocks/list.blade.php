@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your blocks</h5>
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
                    <h5 class="modal-title" id="installModalLabel">Install New Block</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/blocks/install" method="post" enctype="multipart/form-data">
                        @csrf

{{--                        <div class="form-group">--}}
{{--                            <label for="name">Name</label>--}}
{{--                            <input required type="text" id="name" class="form-control" name="name"/>--}}
{{--                        </div>--}}

                        <div class="form-group">
                            <label>Select block file(.zip file)</label>
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
                    <h5 class="modal-title" id="createModalLabel">Create New Block</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/blocks/create" method="post" enctype="multipart/form-data">
                        @csrf

                        <p>Editor uses grapesjs so if you want to create blocks for your editor please refer
                            grapesjs <a target="_blank" href="https://grapesjs.com/docs/">documentation</a></p>

                        <p><a href="/">Download sample block</a></p>

                        <p class="text-warning">Only paste the code given by other developers if you can confirm it is
                            secure and reliable</p>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input required type="text" id="name" class="form-control" name="name"/>
                        </div>

                        <div class="form-group">
                            <label for="unique_id">Unique ID</label>
                            <input required type="number" id="unique_id" class="form-control" name="unique_id"/>
                            <small class="form-text text-muted">Unique id is used to differentiate this block from others and show the settings at the right time, your blocks content should contain data-id attribute which should be same as this unique id, unique id can be anything numerical</small>
                        </div>

                        <div class="form-group">
                            <label for="component_js">Block JS Code</label>
                            <textarea name="component_js" id="component_js" class="form-control"
                                      placeholder=""></textarea>
                            <small class="form-text text-muted">Paste JS code for block creation.
                                <code>editor</code> variable will be available for accessing blockManager, DomComponents
                                etc </small>
                        </div>

                        <div class="form-group">
                            <label for="settings_js">Settings JS Code</label>
                            <textarea name="settings_js" id="settings_js" class="form-control"
                                      placeholder=""></textarea>
                            <small class="form-text text-muted">Paste JS code for settings screen. <code>model</code>
                                variable will be available for accessing different methods which are listed <a
                                    href="https://grapesjs.com/docs/api/component.html">here</a> </small>
                        </div>

                        <div class="form-group">
                            <label for="custom_css">Custom CSS</label>
                            <textarea name="custom_css" id="custom_css" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Paste css code without style tag</small>
                        </div>

                        <div class="form-group">
                            <label for="custom_js">Custom JS</label>
                            <textarea name="custom_js" id="custom_js" class="form-control" placeholder=""></textarea>
                            <small class="form-text text-muted">Paste script without script tag</small>
                        </div>

                        <div class="form-group">
                            <label for="preview_image">Preview Image</label>
                            <input type="file" id="preview_image" class="form-control file-fix" name="preview_image"/>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
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
                        <li class="breadcrumb-item active"><a href="/admin/blocks/list">Blocks</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('layout-backend.notify')

        <div class="card">
            <div class="card-header">
                <h5 class="mg-b-0 tx-spacing--1">Blocks</h5>
            </div>
            <div class="card-body">
                <div class="d-none d-md-block float-right mb-4">
                    <button data-toggle="modal" data-target="#createModal"
                            class="btn btn-sm pd-x-15 btn-outline-primary btn-uppercase mg-l-5">
                        <i data-feather="plus" class="wd-10 mg-r-5"></i> Create Block
                    </button>
                </div>

                <div class="d-none d-md-block float-right mb-4">
                    <button data-toggle="modal" data-target="#installModal"
                            class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5">
                        <i data-feather="upload" class="wd-10 mg-r-5"></i> Install Block
                    </button>
                </div>


                <table class="table table-bordered table-hover" id="dtList">
                    <thead>
                    <tr>
                        <th>Preview</th>
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
                            <td><img src="{{$component->preview_img}}" class="w-100px"/></td>
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
                                    <a href="/admin/blocks/disable/{{$component->id}}" class="btn btn-warning"><i
                                            data-feather="x" class="wd-10 mg-r-5"></i> Disable</a>
                                @else
                                    <a href="/admin/blocks/enable/{{$component->id}}" class="btn btn-primary"><i
                                            data-feather="check" class="wd-10 mg-r-5"></i> Enable</a>
                                @endif
                            </td>
                            <td>
                                <a href="/admin/blocks/edit/{{$component->id}}" class="btn btn-primary"><i
                                        data-feather="edit" class="wd-10 mg-r-5"></i> Edit</a>
                            </td>
                            <td>
                                <a href="/admin/blocks/delete/{{$component->id}}" class="btn btn-danger"><i
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

