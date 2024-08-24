@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your plugins</h5>
@stop

@section('content')
    <div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="/admin/plugins/edit/{{$plugin->id}}">Pluginss</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mg-b-0 tx-spacing--1">Edit Plugin</h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-2">
                        <div class="d-none d-md-block mb-4">
                            <a role="button" href="/admin/plugins/list"
                               class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="arrow-left"
                                                                                              class="wd-10 mg-r-5"></i>
                                Back to list
                            </a>
                        </div>
                    </div>
                </div>

                @include('layout-backend.notify')

                <form action="/admin/plugins/edit/{{$plugin->id}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input required type="text" value="{{old('name', $plugin->name)}}" id="name" class="form-control" name="name"/>
                    </div>

                    <div class="form-group">
                        <label for="unique_id">Plugin Name</label>
                        <input required type="text" value="{{old('unique_id', $plugin->unique_id)}}" id="unique_id" class="form-control" name="unique_id"/>
                        <small class="form-text text-muted">It is plugin name which needs to be loaded ex: for
                            GrapesJS Typed the identifier is <code>grapesjs-typed</code></small>
                    </div>

                    <div class="form-group">
                        <label for="custom_js">Custom JS</label>
                        <textarea name="custom_js" id="custom_js" class="form-control" placeholder="">{{old('custom_js', $plugin->custom_js)}}</textarea>
                        <small class="form-text text-muted">Enter links to your js files each on a new line, you can
                            host your js files on gist.github.com</small>
                    </div>

                    <div class="form-group">
                        <label for="custom_css">Custom CSS</label>
                        <textarea name="custom_css" id="custom_css" class="form-control" placeholder="">{{old('custom_css', $plugin->custom_css)}}</textarea>
                        <small class="form-text text-muted">Enter links to your css files each on a new line, you
                            can host your css files on gist.github.com</small>
                    </div>

                    <div class="form-group">
                        <label for="plugin_options">Plugin Options</label>
                        <textarea name="plugin_options" id="plugin_options" class="form-control" placeholder="">{{old('plugin_options', $plugin->plugin_options)}}</textarea>
                        <small class="form-text text-muted">Enter your plugin options as <code>'grapesjs-typed': { /* options */ }</code></small>
                    </div>

                    <div class="row justify-content-center">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>

            </div>
        </div>


    </div>
@endsection
