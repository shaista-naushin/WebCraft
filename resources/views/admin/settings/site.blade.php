@extends('layout-backend.master')

@section('styles')
    <link href="/assets/plugins/quill/quill.core.css" rel="stylesheet">
    <link href="/assets/plugins/quill/quill.snow.css" rel="stylesheet">
    <link href="/assets/plugins/quill/quill.bubble.css" rel="stylesheet">
    <style type="text/css">
        .ql-picker-label svg {
            width: 20px;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Site Settings</h5>
            </div>
            <div class="card-body">
                @include('layout-backend.notify')

                <form action="/admin/settings/site" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="url" class="d-block">Site URL</label>
                        <input type="text" name="url" id="url"
                               value="{{old('url', $settings[\App\Models\Settings::SITE_URL])}}" class="form-control"
                               placeholder="Enter site url">
                        <small class="form-text text-muted">Site URL should start with http:// or https://</small>
                    </div>

                    <div class="form-group">
                        <label for="title" class="d-block">Site Title</label>
                        <input type="text" name="title" id="title"
                               value="{{old('title', $settings[\App\Models\Settings::SITE_TITLE])}}"
                               class="form-control"
                               placeholder="Enter site title">
                    </div>

                    <div class="form-group">
                        <label for="description" class="d-block">Site Description</label>
                        <textarea name="description" id="description" class="form-control"
                                  placeholder="Enter site description">{{old('description', $settings[\App\Models\Settings::SITE_DESCRIPTION])}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="terms" class="d-block">Terms of service(Sign Up Page)</label>
                        <input type="hidden" name="terms" id="terms"
                               value="{!! old('terms', $settings[\App\Models\Settings::SITE_TERMS]) !!}"/>

                        <div id="toolbar-container">
  <span class="ql-formats">
    <select class="ql-font"></select>
    <select class="ql-size"></select>
  </span>
                            <span class="ql-formats">
    <button class="ql-bold"></button>
    <button class="ql-italic"></button>
    <button class="ql-underline"></button>
    <button class="ql-strike"></button>
  </span>
                            <span class="ql-formats">
    <select class="ql-color"></select>
    <select class="ql-background"></select>
  </span>
                            <span class="ql-formats">
    <button class="ql-script" value="sub"></button>
    <button class="ql-script" value="super"></button>
  </span>
                            <span class="ql-formats">
    <button class="ql-header" value="1"></button>
    <button class="ql-header" value="2"></button>
    <button class="ql-blockquote"></button>
    <button class="ql-code-block"></button>
  </span>
                            <span class="ql-formats mg-t-5">
    <button class="ql-list" value="ordered"></button>
    <button class="ql-list" value="bullet"></button>
    <button class="ql-indent" value="-1"></button>
    <button class="ql-indent" value="+1"></button>
  </span>
                            <span class="ql-formats mg-t-5">
    <button class="ql-direction" value="rtl"></button>
    <select class="ql-align"></select>
  </span>
                            <span class="ql-formats mg-t-5">
    <button class="ql-link"></button>
    <button class="ql-image"></button>
    <button class="ql-video"></button>
    <button class="ql-formula"></button>
  </span>
                            <span class="ql-formats">
    <button class="ql-clean"></button>
  </span>
                        </div>

                        <div id="editor-container" class="ht-200">
                            {!! old('terms', $settings[\App\Models\Settings::SITE_TERMS]) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="keywords" class="d-block">Keywords</label>
                        <input type="text" name="keywords" id="keywords"
                               value="{{old('keywords', $settings[\App\Models\Settings::SITE_KEYWORDS])}}"
                               class="form-control"
                               placeholder="Enter keywords">
                        <small class="form-text text-muted">Enter comma separated keywords like keyword1, keyword2
                            etc</small>
                    </div>

                    <div class="form-group">
                        <label for="googleDomainVerify" class="d-block">Google Domain Verify</label>
                        <input type="text" name="googleDomainVerify" id="googleDomainVerify"
                               value="{{old('googleDomainVerify', $settings[\App\Models\Settings::SITE_GOOGLE_DOMAIN_VERIFY])}}"
                               class="form-control"
                               placeholder="Enter google domain verify">
                        <small class="form-text text-muted">
                            Paste your meta tag here to verify your website on google webmasters , it will
                            look something like below
                            <pre class="p-0"><code>&lt;meta name=&quot;google-site-verification&quot; content=&quot;QsHIQMfsdaassq1kr8irG33KS7LoaJhZY8XLTdAQ7PA&quot; /&gt;</code></pre>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="bingDomainVerify" class="d-block">Bing Domain Verify</label>
                        <input type="text" name="bingDomainVerify" id="bingDomainVerify"
                               value="{{old('bingDomainVerify', $settings[\App\Models\Settings::SITE_BING_DOMAIN_VERIFY])}}"
                               class="form-control"
                               placeholder="Enter bing domain verify">
                        <small class="form-text text-muted">
                            Paste your meta tag here to verify your website on bing webmasters , it will
                            look something like below
                            <pre class="p-0"><code>&lt;meta name=&quot;msvalidate.01&quot; content=&quot;5A3A378F55B7518E3733ffS784711DC0&quot; /&gt;</code></pre>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="googleAnalytics" class="d-block">Google Analytics</label>
                        <textarea name="googleAnalytics" id="googleAnalytics" class="form-control"
                                  placeholder="Enter google analytics code">{{old('googleAnalytics', $settings[\App\Models\Settings::SITE_GOOGLE_ANALYTICS])}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="locale" class="d-block">Default Locale</label>
                        <select name="locale" id="locale" class="form-control">
                            <option value="en">English</option>
                        </select>
                    </div>

                    {{--                    <div class="form-group">--}}
                    {{--                        <label for="bitlyAccessToken" class="d-block">Bitly Access Token(URL Shortener)</label>--}}
                    {{--                        <input type="text" name="bitlyAccessToken" id="bitlyAccessToken"--}}
                    {{--                               value="{{old('bitlyAccessToken', $settings[\App\Models\Settings::BITLY_ACCESS_TOKEN])}}"--}}
                    {{--                               class="form-control"--}}
                    {{--                               placeholder="Enter bitly access token">--}}
                    {{--                    </div>--}}

                    <div class="form-group">
                        <label for="getResponseClientId" class="d-block">Get Response Client Id</label>
                        <input type="text" name="getResponseClientId" id="getResponseClientId"
                               value="{{old('getResponseClientId', $settings[\App\Models\Settings::GET_RESPONSE_CLIENT_ID])}}"
                               class="form-control"
                               placeholder="Enter get response client id">
                    </div>

                    <div class="form-group">
                        <label for="getResponseClientSecret" class="d-block">Get Response Client Secret</label>
                        <input type="text" name="getResponseClientSecret" id="getResponseClientSecret"
                               value="{{old('getResponseClientSecret', $settings[\App\Models\Settings::GET_RESPONSE_CLIENT_SECRET])}}"
                               class="form-control"
                               placeholder="Enter get response client secret">
                    </div>

                    <div class="form-group">
                        <label for="aweberClientId" class="d-block">Aweber Client Id</label>
                        <input type="text" name="aweberClientId" id="aweberClientId"
                               value="{{old('aweberClientId', $settings[\App\Models\Settings::AWEBER_CLIENT_ID])}}"
                               class="form-control"
                               placeholder="Enter aweber client id">
                    </div>

                    <div class="form-group">
                        <label for="aweberClientSecret" class="d-block">Aweber Client Secret</label>
                        <input type="text" name="aweberClientSecret" id="aweberClientSecret"
                               value="{{old('aweberClientSecret', $settings[\App\Models\Settings::AWEBER_CLIENT_SECRET])}}"
                               class="form-control"
                               placeholder="Enter aweber client secret">
                    </div>

                    <input name="logo" type="hidden" value="{{$settings[\App\Models\Settings::SITE_LOGO]}}"/>
                    <input name="favicon" type="hidden" value="{{$settings[\App\Models\Settings::SITE_FAVICON]}}"/>

                    <div class="form-group">
                        <label for="logo" class="d-block">Logo</label>
                        <input type="file" name="logoFile" id="logo">
                        <small class="form-text text-muted">Image file can only be png, jpeg, jpg, gif</small>

                        <img class="w-100px" src="{{$settings[\App\Models\Settings::SITE_LOGO]}}"/>
                    </div>

                    <div class="form-group">
                        <label for="favicon" class="d-block">Favicon</label>
                        <input type="file" name="faviconFile" id="favicon">
                        <small class="form-text text-muted">Image file can only be png, jpeg, jpg, gif</small>

                        <img class="w-100px" src="{{$settings[\App\Models\Settings::SITE_FAVICON]}}"/>
                    </div>

                    <div class="form-group">
                        <input type="checkbox"
                               {{$settings[\App\Models\Settings::DEVELOPER_MODE] == 1 ? 'checked':''}} name="developer_mode"
                               id="developer_mode"> Developer mode
                    </div>

                    <div class="row  justify-content-center">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/assets/plugins/quill/quill.min.js"></script>
    <script type="text/javascript">
        let quill = new Quill('#editor-container', {
            modules: {
                toolbar: '#toolbar-container'
            },
            placeholder: 'Enter terms of service',
            theme: 'snow'
        });

        quill.on('text-change', function (delta, oldDelta, source) {
            $('#terms').val(quill.container.firstChild.innerHTML);
        });
    </script>
@endsection
