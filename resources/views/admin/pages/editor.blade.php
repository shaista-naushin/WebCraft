@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Editing Page - {{$page->name}}</h5>
@stop

@section('content')
    <div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <iframe style="border: none;margin-top: 10px;" width="100%"
                height="600px" src="/pages/editor_frame/{{$page->id}}"></iframe>


    </div>
@endsection
