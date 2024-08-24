blockManager.add('{{$unique_id}}', {
    label: '<a href="{{$preview_img}}" target="_blank"><img style="width:100%;background-size: contain;background-repeat: round;max-width: 100%;height: 35px;background-image: url({{$component_img}});"/></a> <div>{{$name}}</div>',
    category: "{{$category}}",
    content: `{!! $html !!}`
});
