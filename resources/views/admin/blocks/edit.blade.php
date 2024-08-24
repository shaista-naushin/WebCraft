@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your blocks</h5>
@stop

@section('content')
    <div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="/admin/blocks/edit/{{$component->id}}">Blocks</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mg-b-0 tx-spacing--1">Edit Block</h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-2">
                        <div class="d-none d-md-block mb-4">
                            <a role="button" href="/admin/blocks/list"
                               class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="arrow-left"
                                                                                              class="wd-10 mg-r-5"></i>
                                Back to list
                            </a>
                        </div>
                    </div>
                </div>

                @include('layout-backend.notify')

                <form action="/admin/blocks/edit/{{$component->id}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <input type="hidden" name="default_preview_image" value="{{$component->preview_img}}"/>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input required type="text" id="name" value="{{old('name', $component->name)}}" class="form-control" name="name" />
                    </div>

                    <div class="form-group">
                        <label for="unique_id">Unique ID</label>
                        <input required type="number" id="unique_id" value="{{old('unique_id', $component->unique_id)}}" class="form-control" name="unique_id"/>
                        <small class="form-text text-muted">Unique id is used to differentiate this block from others and show the settings at the right time, your block content should contain data-id attribute which should be same as this unique id, unique id can be anything numerical</small>
                    </div>

                    <div class="form-group">
                        <label for="component_js">Block JS Code</label>
                        <textarea name="component_js" id="component_js" class="form-control"
                                  placeholder="">{{old('component_js', $component->js_code)}}</textarea>
                        <small class="form-text text-muted">Paste JS code for component creation.
                            <code>editor</code> variable will be available for accessing blockManager, DomComponents
                            etc </small>
                    </div>

                    <div class="form-group">
                        <label for="settings_js">Settings JS Code</label>
                        <textarea name="settings_js" id="settings_js" class="form-control"
                                  placeholder="">{{old('settings_js', $component->selected_code)}}</textarea>
                        <small class="form-text text-muted">Paste JS code for settings screen. <code>model</code>
                            variable will be available for accessing different methods which are listed <a
                                href="https://grapesjs.com/docs/api/component.html">here</a> </small>
                    </div>

                    <div class="form-group">
                        <label for="custom_css">Custom CSS</label>
                        <textarea name="custom_css" id="custom_css" class="form-control" placeholder="">{{old('custom_css', $component->custom_css)}}</textarea>
                        <small class="form-text text-muted">Paste css code without style tag</small>
                    </div>

                    <div class="form-group">
                        <label for="custom_js">Custom JS</label>
                        <textarea name="custom_js" id="custom_js" class="form-control" placeholder="">{{old('custom_js', $component->custom_js)}}</textarea>
                        <small class="form-text text-muted">Paste script without script tag</small>
                    </div>

                    <div class="form-group">
                        <label for="preview_image">Preview Image</label>
                        <input type="file" id="preview_image" class="form-control file-fix" name="preview_image" />
                        <img src="{{$component->preview_img}}" class="w-100px"/>
                    </div>

                    <div class="row justify-content-center">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>

            </div>
        </div>


    </div>
@endsection
