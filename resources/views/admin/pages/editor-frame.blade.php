<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Editor</title>
    <meta content="Editor" name="description">
    <link rel="stylesheet" href="/editor/stylesheets/toastr.min.css">
    <link rel="stylesheet" href="/editor/stylesheets/grapes.min.css?v0.16.12">
    <link rel="stylesheet" href="/editor/stylesheets/demos.css?v3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/>
    <link href="https://unpkg.com/grapick/dist/grapick.min.css" rel="stylesheet">

    @foreach($pluginsCSSLinks as $link)
        <link href="{{$link}}" rel="stylesheet">
    @endforeach
</head>
<body>
<div id="gjs">

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/editor/js/toastr.min.js"></script>
<script src="/editor/js/grapes.min.js?v0.16.12"></script>
<script src="https://unpkg.com/grapesjs-style-bg@1.0.3/dist/grapesjs-style-bg.min.js"></script>
<script src="https://unpkg.com/grapesjs-custom-code@0.1.2/dist/grapesjs-custom-code.min.js"></script>
<script src="/editor/js/grapesjs-blocks-bootstrap4.min.js"></script>
<script src="/editor/js/grapesjs-component-countdown.min.js"></script>
<script src="/editor/js/grapesjs-blocks-avance.min.js"></script>
<script src="https://unpkg.com/draggabilly@2/dist/draggabilly.pkgd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>

@foreach($pluginsJSLinks as $link)
    <script src="{{$link}}"></script>
@endforeach

<script type="text/javascript">
    window.page_id = '{{$page->id}}';
    window.server_url = '{{url('/')}}';
    window.load_url = "/pages/editor/{{$page->id}}/load";
    window.save_url = "/pages/editor/{{$page->id}}/save";
    window.aweberLists = @json($aweberLists);
    window.getResponseCampaigns = @json($getResponseCampaigns);
    window.popups = @json($popups);
</script>

<script type="text/javascript">
    const editor = grapesjs.init({
        avoidInlineStyle: 1,
        height: '100%',
        container: '#gjs',
        fromElement: 1,
        showOffsets: 1,
        assetManager: {
            upload: '/pages/upload/assets',
            uploadName: 'image',
            multiUpload: false
        },
        selectorManager: {componentFirst: true},
        plugins: [
            'grapesjs-blocks-bootstrap4',
            'grapesjs-style-bg',
            'gjs-component-countdown',
            'grapesjs-custom-code',
            'gjs-blocks-avance',
            @foreach($plugins as $plugin)
                '{{$plugin->unique_id}}',
            @endforeach
        ],
        styleManager: {
            clearProperties: 1,
            sectors: [{
                name: 'General',
                open: false,
                buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
                properties: [{
                    type: 'slider',
                    property: 'top',
                    defaults: 10,
                    step: 1,
                    max: 500,
                    min: 0,
                },
                    {
                        type: 'slider',
                        property: 'right',
                        defaults: 0,
                        step: 1,
                        max: 500,
                        min: 0,
                    },
                    {
                        type: 'slider',
                        property: 'left',
                        defaults: 0,
                        step: 1,
                        max: 500,
                        min: 0,
                    },
                    {
                        type: 'slider',
                        property: 'bottom',
                        defaults: 0,
                        step: 1,
                        max: 500,
                        min: 0,
                    }]
            },
                {
                    name: 'Dimension',
                    open: false,
                    buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                    properties: [
                        {
                            property: 'margin',
                            properties: [
                                {
                                    name: 'Top',
                                    type: 'slider',
                                    property: 'margin-top',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }, {
                                    name: 'Right',
                                    type: 'slider',
                                    property: 'margin-right',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }, {
                                    name: 'Bottom',
                                    type: 'slider',
                                    property: 'margin-bottom',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }, {
                                    name: 'Left',
                                    type: 'slider',
                                    property: 'margin-left',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }
                            ],
                        },
                        {
                            property: 'padding',
                            properties: [
                                {
                                    name: 'Top',
                                    type: 'slider',
                                    property: 'padding-top',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }, {
                                    name: 'Right',
                                    type: 'slider',
                                    property: 'padding-right',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }, {
                                    name: 'Bottom',
                                    type: 'slider',
                                    property: 'padding-bottom',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }, {
                                    name: 'Left',
                                    type: 'slider',
                                    property: 'padding-left',
                                    step: 1,
                                    max: 500,
                                    min: 0,
                                }
                            ],
                        }]
                },
                {
                    name: 'Typography',
                    open: false,
                    buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-shadow'],
                    properties: [{
                        type: 'slider',
                        property: 'font-size',
                        defaults: 10,
                        step: 1,
                        max: 100,
                        min: 0,
                    },
                        {
                            property: 'font-family',
                            name: 'Font',
                            list: [
                                {name: 'Arial', value: 'Arial, Helvetica, sans-serif'},
                                {name: 'Arial Black', value: 'Arial Black, Gadget, sans-serif'},
                                {name: 'Baloo 2', value: 'Baloo 2, cursive'},
                                {name: 'Bangers', value: 'Bangers, cursive'},
                                {name: 'Brush Script MT', value: 'Brush Script MT, sans-serif'},
                                {name: 'Comic Sans MS', value: 'Comic Sans MS, cursive, sans-serif'},
                                {name: 'Concert One', value: 'Concert One, cursive'},
                                {name: 'Georgia', value: 'Georgia, serif'},
                                {name: 'Graduate', value: 'Graduate, cursive'},
                                {name: 'Helvetica', value: 'Helvetica, serif'},
                                {name: 'Harmattan', value: 'Harmattan, sans-serif'},
                                {name: 'Impact', value: 'Impact, Charcoal, sans-serif'},
                                {name: 'Lucida Sans Unicode', value: 'Lucida Sans Unicode, Lucida Grande, sans-serif'},
                                {name: 'Luckiest Guy', value: 'Luckiest Guy, sans-serif'},
                                {name: 'Montserrat', value: 'Montserrat, sans-serif'},
                                {name: 'Orbitron', value: 'Orbitron, sans-serif'},
                                {name: 'Oswald', value: 'Oswald, sans-serif'},
                                {name: 'Pacifico', value: 'Pacifico, cursive'},
                                {name: 'Sen', value: 'Sen, sans-serif'},
                                {name: 'Tahoma', value: 'Tahoma, Geneva, sans-serif'},
                                {name: 'Times New Roman', value: 'Times New Roman, Times, serif'},
                                {name: 'Trebuchet MS', value: 'Trebuchet MS, Helvetica, sans-serif'},
                                {name: 'Verdana', value: 'Verdana, Geneva, sans-serif'},
                            ]
                        }]
                }, {
                    name: 'Decorations',
                    open: false,
                    buildProps: ['opacity', 'border-radius', 'border', 'box-shadow', 'background-bg'],
                    properties: [{
                        type: 'slider',
                        property: 'opacity',
                        defaults: 1,
                        step: 0.01,
                        max: 1,
                        min: 0,
                    }, {
                        property: 'border-radius',
                        properties: [
                            {name: 'Top', property: 'border-top-left-radius'},
                            {name: 'Right', property: 'border-top-right-radius'},
                            {name: 'Bottom', property: 'border-bottom-left-radius'},
                            {name: 'Left', property: 'border-bottom-right-radius'}
                        ],
                    }, {
                        property: 'box-shadow',
                        properties: [
                            {name: 'X position', property: 'box-shadow-h'},
                            {name: 'Y position', property: 'box-shadow-v'},
                            {name: 'Blur', property: 'box-shadow-blur'},
                            {name: 'Spread', property: 'box-shadow-spread'},
                            {name: 'Color', property: 'box-shadow-color'},
                            {name: 'Shadow type', property: 'box-shadow-type'}
                        ],
                    }, {
                        id: 'background-bg',
                        property: 'background',
                        type: 'bg',
                    },],
                },
                {
                    name: 'Extra',
                    open: false,
                    buildProps: ['opacity', 'transition', 'perspective', 'transform'],
                    properties: [{
                        type: 'slider',
                        property: 'opacity',
                        defaults: 1,
                        step: 0.01,
                        max: 1,
                        min: 0
                    }]
                }]
        },
        pluginsOpts: {
            'gjs-blocks-avance': {
                blocks: ['iframe']
            },
            'grapesjs-blocks-bootstrap4': {
                blocks: {
                    column_break: false,
                    media_object: false,
                    alert: false,
                    tabs: false,
                    badge: false,
                    card: false,
                    card_container: false,
                    collapse: false,
                    dropdown: false,
                    image: false,
                    form: false,
                    select: false,
                    button: false,
                    button_group: false,
                    button_toolbar: false,
                    input: false,
                    input_group: false,
                    form_group_input: false,
                    textarea: false,
                    checkbox: false,
                    radio: false,
                    list: true,
                    link: false
                }
            }
        },
        storageManager: {
            type: 'remote',
            stepsBeforeSave: 1,
            params: {},   // For custom values on requests
            // your SERVER endpoints
            urlStore: window.save_url,
            urlLoad: window.load_url,
        },
        canvas: {
            styles: @json($defaultCSSLinks),
            scripts: @json($defaultJSLinks),
        }
    });

    editor.I18n.addMessages({
        en: {
            styleManager: {
                properties: {
                    'background-repeat': 'Repeat',
                    'background-position': 'Position',
                    'background-attachment': 'Attachment',
                    'background-size': 'Size',
                }
            },
        }
    });

    let blockManager = editor.BlockManager;

    blockManager.add("form", {
        label: '<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path><path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path><rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect></svg><div>Form</div>',
        category: "Forms",
        content: '<form style="margin: 20px;padding:20px;" id="lead-form" method="POST" enctype="multipart/form-data" action="' + window.server_url + '/api/lead"><input type="hidden" name="page_id" value="' + window.page_id + '" /><div class="form-group"><label>Email</label><input name="email" placeholder="Enter your email" type="email" required class="form-control"/></div><div class="form-group"><button type="submit" class="btn btn-primary btn-block"><span>Send</span></button></div></form>'
    });

    blockManager.add('email-input', {
        label: '<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z"></path><polygon class="gjs-block-svg-path" points="4 10 5 10 5 14 4 14"></polygon></svg><div>Email Input</div>',
        category: "Forms",
        content: {
            draggable: "form .form-group",
            content: '<div class="form-group"><input name="email" type="email" placeholder="Enter your email" required class="form-control"/></div>'
        }
    });

    blockManager.add('link', {
        label: '<svg width="24" height="50" focusable="false" data-prefix="fas" data-icon="link" class="svg-inline--fa fa-link fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"></path></svg><div>Link</div>',
        category: "Extra",
        content: '<a href="">Link Text</a>'
    });

    @foreach($components as $component)
    {!! $component->js_code !!}
    @endforeach

    blockManager.add("button", {
        label: '<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z" fill-rule="nonzero"></path><rect class="gjs-block-svg-path" x="4" y="11.5" width="16" height="1"></rect></svg><div>Button</div>',
        category: "Forms",
        extend: 'button',
        content: '<a href="#" role="button" class="btn btn-primary btn-block"><span>Send</span></a>'
    });

    let domComponents = editor.DomComponents;

    let t = domComponents.getType("image"), n = t.model, a = t.view;
    domComponents.addType("bs-image", {
        model: n.extend({
            defaults: Object.assign({}, n.prototype.defaults, {
                "custom-name": "Image",
                tagName: "img",
                resizable: 1,
                attributes: {src: "https://dummyimage.com/800x500/999/222"},
                classes: ["img-fluid"],
                traits: [{type: "text", label: "Source (URL)", name: "src"}, {
                    type: "text",
                    label: "Alternate text",
                    name: "alt"
                }].concat(n.prototype.defaults.traits)
            })
        }, {
            isComponent: function (e) {
                if (e && "IMG" === e.tagName) return {type: 'bs-image'}
            }
        }), view: a
    })


    let leadUrl = encodeURI(window.server_url + '/api/lead/' + window.page_id);


    blockManager.add("bs-image", {
        label: '<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="image" class="svg-inline--fa fa-image fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 448H48c-26.51 0-48-21.49-48-48V112c0-26.51 21.49-48 48-48h416c26.51 0 48 21.49 48 48v288c0 26.51-21.49 48-48 48zM112 120c-30.928 0-56 25.072-56 56s25.072 56 56 56 56-25.072 56-56-25.072-56-56-56zM64 384h384V272l-87.515-87.515c-4.686-4.686-12.284-4.686-16.971 0L208 320l-55.515-55.515c-4.686-4.686-12.284-4.686-16.971 0L64 336v48z"></path></svg><div>Image</div>',
        category: "Media",
        content: {type: "bs-image"}
    });

    blockManager.add("share-pinterest", {
        label: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"/></svg><div>PInterest</div>',
        category: "Share",
        content: '<a target="_blank" href="http://pinterest.com/pin/create/button/?url=' + leadUrl + '" role="button" class="btn btn-primary btn-social btn-pinterest"><span class="fa fa-pinterest"></span><span>Share on PInterest <i></i></span></a>',
    });

    blockManager.add("share-facebook", {
        label: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"/></svg><div>Facebook</div>',
        category: "Share",
        content: '<a role="button" target="_blank" href="https://www.facebook.com/sharer.php?u=' + leadUrl + '" class="btn btn-primary btn-social btn-facebook"><span class="fa fa-facebook"></span><span>Share on Facebook <i></i></span></a>'
    });

    blockManager.add("share-linkedin", {
        label: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg><div>LinkedIn</div>',
        category: "Share",
        content: '<a role="button" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=' + leadUrl + '" class="btn btn-primary btn-social btn-linkedin"><span class="fa fa-linkedin"></span><span>Share on LinkedIn <i></i></span></a>'
    });

    blockManager.add("share-twitter", {
        label: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg><div>Twitter</div>',
        category: "Share",
        content: '<a role="button" target="_blank" href="https://twitter.com/intent/tweet?url=' + leadUrl + '" class="btn btn-primary btn-social btn-twitter"><span class="fa fa-twitter"></span> <span>Share on Twitter <i></i></span></a>'
    });

    editor.on('component:deselected', (model) => {
        let widget = $('#gjs .widget-options');
        widget.remove();
    });

    editor.on('component:selected', (model) => {

        model.removeTrait('id');
        model.removeTrait('title');

        const $el = model.getEl();

        const parent = model.parent();

        if ($($el).children()[0]) {
            const child = $($el).children()[0];
            const dataAttr = $(child).attr('data-id');

            if (typeof dataAttr !== typeof undefined && dataAttr !== false && dataAttr == 12211) {
                let html = `<div class="widget-options">
<div>
            <label>Width</label>
        	<input name="width" style="width:100%;" value="" type="range" min="0" max="100" step="2" data-orientation="vertical">
        	</div></div>`;

                $('#gjs').append(html);

                let sector = $('#gjs .gjs-sm-sector');
                $(sector).first().find('.gjs-sm-properties').css('display', 'block');
                $(sector).first().find('.gjs-sm-properties').append($('#gjs .widget-options'));

                const openSmBtn = editor.Panels.getButton('views', 'open-sm');
                openSmBtn.set('active', 1);

                return;
            }

            @foreach($components as $component)
            if (typeof dataAttr !== typeof undefined && dataAttr !== false && dataAttr == {{$component->unique_id}}) {
                    {!! $component->selected_code !!}

                let sector = $('#gjs .gjs-sm-sector');
                $(sector).first().find('.gjs-sm-properties').css('display', 'block');
                $(sector).first().find('.gjs-sm-properties').append($('#gjs .widget-options'));

                const openSmBtn = editor.Panels.getButton('views', 'open-sm');
                openSmBtn.set('active', 1);

                return;
            }
            @endforeach
        }

        if (model.is('form')) {

            let redirectType = $($el).attr('x-form-redirect-type') ? $($el).attr('x-form-redirect-type') : 'same';
            let redirectUrl = $($el).attr('x-form-redirect-url') ? $($el).attr('x-form-redirect-url') : '';
            let redirectMessage = $($el).attr('x-form-redirect-message') ? $($el).attr('x-form-redirect-message') : '';
            let formType = $($el).attr('gs-type') ? $($el).attr('gs-type') : 'default';
            let responder = $($el).attr('autoresponder') ? $($el).attr('autoresponder') : null;
            let aweberAccount = $($el).attr('aweber-account') ? $($el).attr('aweber-account') : null;
            let getResponseCampaign = $($el).attr('get-response-campaign') ? $($el).attr('get-response-campaign') : null;

            let html = `<div class="widget-options">

            <div class="mt-10" id="redirect_block">
                <label>Redirect To</label>
                <select id="redirectType">
                    <option ` + (redirectType === 'same' ? 'selected' : '') + ` value='same'>Same Page</option>
                    <option ` + (redirectType === 'url' ? 'selected' : '') + ` value='url'>URL</option>
                </select>
            </div>

            <div class="mt-10" id="redirect_url_block">
                <label>Redirect URL</label>
                <input name="redirect_url" id="redirect_url" style="width:100%;" value="` + redirectUrl + `">
            </div>

            <div class="mt-10" id="redirect_success_block">
                <label>Redirect Message</label>
                <input name="redirect_success" id="redirect_success" style="width:100%;" value="` + redirectMessage + `">
            </div>

            <div class="mt-10">
                <label>Type</label>
                <select id="formType">
                    <option ` + (formType === 'default' ? 'selected' : '') + ` value='default'>Default</option>
                    <option ` + (formType === 'lead' ? 'selected' : '') + ` value='lead'>Lead</option>
                </select>
        	</div>`

            html = html + `<div id="autoresponder_block" class="mt-10" style="display:none;">
                <label>Autoresponder</label>
                <select id="autoresponder">
                    <option>Select autoresponder</option>
                    <option ` + (responder === 'aweber' ? 'selected' : '') + ` value="aweber">Aweber</option>
                    <option ` + (responder === 'get_response' ? 'selected' : '') + ` value="get_response">Get Response</option>
                </select>
        	</div>`;

            html = html + `<div id="aweber_account_block" class="mt-10" style="display:none;">
                <label>Aweber List</label>
                <select id="aweber_account"><option value="">Select an account</option>`

            if (window.aweberLists && window.aweberLists instanceof Array && window.aweberLists.length > 0) {
                window.aweberLists.forEach((account) => {
                    html = html + `<option ` + ((account.account_id + `&` + account.list_id) == aweberAccount ? 'selected' : '') + ` value='` + account.account_id + `&` + account.list_id + `'>` + account.list_name + `</option>`;
                });
            }

            html = html + `</select>
        	</div>`;


            html = html + `<div id="get_response_block" class="mt-10" style="display:none;">
                <label>Get Response Campaign</label>
                <select id="get_response_campaign"><option></option>`

            if (window.getResponseCampaigns && window.getResponseCampaigns instanceof Array && window.getResponseCampaigns.length > 0) {
                window.getResponseCampaigns.forEach((account) => {
                    html = html + `<option ` + (account.campaign_id == getResponseCampaign ? 'selected' : '') + ` value='` + account.campaign_id + `'>` + account.account_name + `</option>`;
                });
            }

            html = html + `</select>
        	</div>`;

            html = html + `</div>`;

            $('#gjs').append(html);

            let widget = $('#gjs .widget-options');

            if (redirectType) {
                if (redirectType === 'url') {
                    widget.find('#redirect_url_block').show();
                    widget.find('#redirect_success_block').hide();
                }

                if (redirectType === 'same') {
                    widget.find('#redirect_success_block').show();
                    widget.find('#redirect_url_block').hide();
                }
            }

            if (formType) {
                if (formType === 'lead') {
                    widget.find('#autoresponder_block').show();
                }

                if (formType === 'default') {
                    widget.find('#autoresponder_block').hide();
                }
            }

            if (formType === 'lead') {
                if (responder && responder.length > 0) {
                    if (responder === 'aweber') {
                        widget.find('#aweber_account_block').show();
                        widget.find('#get_response_block').hide();
                    }

                    if (responder === 'get_response') {
                        widget.find('#get_response_block').show();
                        widget.find('#aweber_account_block').hide();
                    }
                }
            } else {
                //hide responders
                widget.find('#autoresponder_block').hide();
                widget.find('#aweber_account_block').hide();
                widget.find('#get_response_block').hide();
            }

            if (widget.length > 0) {

                widget.find('#redirectType').on('change', (event) => {
                    let selectedOption = $(event.target).find('option:selected').val();
                    model.addAttributes({'x-form-redirect-type': selectedOption});

                    if (selectedOption === 'same') {
                        widget.find('#redirect_success_block').show();
                        widget.find('#redirect_url_block').hide();
                        model.addAttributes({'x-form-redirect-url': ''});
                        widget.find('#redirect_url').val('');
                    }

                    if (selectedOption === 'url') {
                        widget.find('#redirect_url_block').show();
                        widget.find('#redirect_success_block').hide();
                        model.addAttributes({'x-form-redirect-message': ''});
                        widget.find('#redirect_success').val('');
                    }
                });

                widget.find('#redirect_url').on('change', (event) => {
                    let selectedOption = $(event.target).val();
                    model.addAttributes({'x-form-redirect-url': selectedOption});
                });

                widget.find('#redirect_success').on('change', (event) => {
                    let selectedOption = $(event.target).val();
                    model.addAttributes({'x-form-redirect-message': selectedOption});
                });

                widget.find('#formType').on('change', (event) => {
                    let selectedOption = $(event.target).find('option:selected').val();
                    model.addAttributes({'gs-type': selectedOption});

                    if (selectedOption === 'lead') {
                        widget.find('#autoresponder_block').show();
                    }

                    if (selectedOption === 'default') {
                        widget.find('#autoresponder_block').hide();
                        widget.find('#aweber_account_block').hide();
                        widget.find('#get_response_block').hide();
                        model.addAttributes({'aweber-account': '', 'aweber-list': '', 'get-response-campaign': ''});
                    }
                });

                widget.find('#autoresponder').on('change', (event) => {
                    let selectedOption = $(event.target).find('option:selected').val();
                    model.addAttributes({'autoresponder': selectedOption});

                    console.log(selectedOption);

                    if (selectedOption === 'aweber') {
                        widget.find('#aweber_account_block').show();
                        widget.find('#get_response_block').hide();
                        return;
                    }

                    if (selectedOption === 'get_response') {
                        widget.find('#get_response_block').show();
                        widget.find('#aweber_account_block').hide();
                        return;
                    }

                    widget.find('#aweber_account_block').hide();
                    widget.find('#get_response_block').hide();
                });

                widget.find('#aweber_account').on('change', (event) => {
                    let selectedOption = $(event.target).find('option:selected').val();
                    model.addAttributes({'aweber-account': selectedOption});
                });

                widget.find('#get_response_campaign').on('change', (event) => {
                    let selectedOption = $(event.target).find('option:selected').val();
                    model.addAttributes({'get-response-campaign': selectedOption});
                    model.addAttributes({'aweber-account': '', 'aweber-list': ''});
                });
            }
        } else if (model.is('bs-image') || model.is('image') || model.is('bs-embed-responsive') || model.is('video')) {


        } else if (model.is('text') || model.is('header') || model.is('paragraph') || model.is('label')) {

        } else if (model.getName() === "Button" || model.get("type") === 'link') {

            let popupList = $($el).attr('popup-list') ? $($el).attr('popup-list') : null;

            let html = `<div class="widget-options">`

            html = html + `<div id="popup_list" class="mt-10">
                <label>Popup</label>
                <select class="mt-10" id="popup"><option value="">Select a popup</option>`

            if (window.popups && window.popups instanceof Array && window.popups.length > 0) {
                window.popups.forEach((popup) => {
                    html = html + `<option ` + (popup.id == popupList ? 'selected' : '') + ` value='` + popup.id + `'>` + popup.name + `</option>`;
                });
            }

            html = html + `</select>
        	</div>`;

            html = html + `</div>`;

            $('#gjs').append(html);

            let widget = $('#gjs .widget-options');

            if (widget.length > 0) {
                widget.find('#popup').on('change', (event) => {
                    let selectedOption = $(event.target).find('option:selected').val();
                    if (selectedOption && selectedOption.length > 0) {
                        model.addAttributes({'data-toggle': 'modal'});
                        model.addAttributes({'data-target': '#modal' + selectedOption});
                        model.addAttributes({'popup-list': selectedOption});
                    } else {
                        const attr = model.getAttributes();
                        delete attr['data-toggle'];
                        delete attr['data-target'];
                        delete attr['popup-list'];
                        model.setAttributes(attr);
                    }
                });
            }
        }

        let sector = $('#gjs .gjs-sm-sector');
        $(sector).first().find('.gjs-sm-properties').css('display', 'block');
        $(sector).first().find('.gjs-sm-properties').append($('#gjs .widget-options'));

        const openSmBtn = editor.Panels.getButton('views', 'open-sm');
        openSmBtn.set('active', 1);
    });

    const pn = editor.Panels;
    const modal = editor.Modal;
    const cmdm = editor.Commands;
    cmdm.add('canvas-clear', function () {
        if (confirm('Are you sure to clean the canvas?')) {
            const comps = editor.DomComponents.clear();
            setTimeout(function () {
                localStorage.clear()
            }, 0)
        }
    });
    cmdm.add('set-device-desktop', {
        run: function (ed) {
            ed.setDevice('Desktop')
        },
        stop: function () {
        },
    });
    cmdm.add('set-device-tablet', {
        run: function (ed) {
            ed.setDevice('Tablet')
        },
        stop: function () {
        },
    });
    cmdm.add('set-device-mobile', {
        run: function (ed) {
            ed.setDevice('Mobile portrait')
        },
        stop: function () {
        },
    });

    const panelManager = editor.Panels;
    panelManager.addButton('options', {
        id: 'canvas-clear',
        className: 'fa fa-trash',
        command: e => e.runCommand('canvas-clear'),
        active: false
    });

    panelManager.addButton('options', {
        id: 'save',
        className: 'fa fa-floppy-o icon-blank',
        command: 'save-db',
        active: false
    });

    editor.Commands.add
    ('save-db',
        {
            run: function (editor, sender) {
                sender && sender.set('active', 0); // turn off the button
                editor.store();
                toastr.info('Saved successfully');
            }
        });

    // Add and beautify tooltips
    [['sw-visibility', 'Show Borders'], ['preview', 'Preview'], ['fullscreen', 'Fullscreen'],
        ['export-template', 'Export']]
        .forEach(function (item) {
            console.log(item);
            pn.getButton('options', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
        });
    [['open-sm', 'Style Manager'], ['open-layers', 'Layers'], ['open-blocks', 'Blocks']]
        .forEach(function (item) {
            pn.getButton('views', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
        });
    const titles = document.querySelectorAll('*[title]');

    for (let i = 0; i < titles.length; i++) {
        const el = titles[i];
        let title = el.getAttribute('title');
        title = title ? title.trim() : '';
        if (!title)
            break;
        el.setAttribute('data-tooltip', title);
        el.setAttribute('title', '');
    }

    // Show borders by default
    pn.getButton('options', 'sw-visibility').set('active', 1);


    // Do stuff on load
    editor.on('load', function () {
        const $ = grapesjs.$;

        // Load and show settings and style manager
        const openTmBtn = pn.getButton('views', 'open-tm');
        openTmBtn && openTmBtn.set('active', 1);
        const openSm = pn.getButton('views', 'open-sm');
        openSm && openSm.set('active', 0);

        // Add Settings Sector
        const traitsSector = $('<div class="gjs-sm-sector no-select">' +
            '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
            '<div class="gjs-sm-properties" style="display: none;"></div></div>');
        const traitsProps = traitsSector.find('.gjs-sm-properties');
        traitsProps.append($('.gjs-trt-traits'));
        $('.gjs-sm-sectors').before(traitsSector);
        traitsSector.find('.gjs-sm-title').on('click', function () {
            const traitStyle = traitsProps.get(0).style;
            const hidden = traitStyle.display === 'none';
            if (hidden) {
                traitStyle.display = 'block';
            } else {
                traitStyle.display = 'none';
            }
        });

        // Open block manager
        const openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
        openBlocksBtn && openBlocksBtn.set('active', 1);
    });

</script>
</body>
</html>
