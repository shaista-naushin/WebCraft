@extends('layout-backend.master')

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Auto Responders</h5>
            </div>
            <div class="card-body">
                @if($enable_aweber)
                    @if(auth()->user()->aweber_access_token == null)
                        <a class="btn btn-primary btn-md" href="/user/connect/aweber">Connect Aweber</a>
                    @else
                        <a class="btn btn-warning btn-md" href="/user/disconnect/aweber">Disconnect Aweber</a>
                    @endif
                @endif

                @if($enable_get_response)
                    @if(auth()->user()->get_response_access_token == null)
                        <a class="btn btn-primary btn-md" href="/user/connect/get_response">Connect Get Response</a>
                    @else
                        <a class="btn btn-warning btn-md" href="/user/disconnect/get_response">Disconnect Get
                            Response</a>
                    @endif
                @endif

                @if(!$enable_aweber && !$enable_get_response)
                    <div class="alert alert-warning">No autoresponders available, contact admin</div>
                @endif
            </div>
        </div>
    </div>
@endsection
