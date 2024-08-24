@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Manage your components</h5>
@stop

@section('content')
    <div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="/admin/components/edit/{{$component->id}}">Custom Components</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mg-b-0 tx-spacing--1">Edit Component</h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-2">
                        <div class="d-none d-md-block mb-4">
                            <a role="button" href="/admin/components/list"
                               class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="arrow-left"
                                                                                              class="wd-10 mg-r-5"></i>
                                Back to list
                            </a>
                        </div>
                    </div>
                </div>

                @include('layout-backend.notify')

                <form action="/admin/components/edit/{{$component->id}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <input type="hidden" name="default_preview_image" value="{{$component->preview_img}}"/>

                    <input type="hidden" name="default_component_image" value="{{$component->component_img}}"/>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input required type="text" id="name" value="{{old('name', $component->name)}}" class="form-control" name="name" />
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input required type="text" id="category" value="{{old('category', $component->category)}}" class="form-control" name="category" />
                    </div>

                    <div class="form-group">
                        <label for="html">HTML</label>
                        <textarea name="html" id="html" class="form-control"
                                  placeholder="">{{old('html', $component->html)}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="custom_css">CSS</label>
                        <textarea name="custom_css" id="custom_css" class="form-control" placeholder="">{{old('custom_css', $component->custom_css)}}</textarea>
                        <small class="form-text text-muted">Paste css code without style tag</small>
                    </div>

                    <div class="form-group">
                        <label for="custom_js">JS</label>
                        <textarea name="custom_js" id="custom_js" class="form-control" placeholder="">{{old('custom_js', $component->custom_js)}}</textarea>
                        <small class="form-text text-muted">Paste script without script tag</small>
                    </div>

                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <input type="file" id="icon" class="form-control file-fix" name="icon" />
                        <img src="{{$component->component_img}}" class="w-100px"/>
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
