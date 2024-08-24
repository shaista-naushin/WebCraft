<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <meta content="" name="description">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Bangers&family=Concert+One&family=Graduate&family=Harmattan&family=Luckiest+Guy&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Orbitron:wght@400;500;600;700;800;900&family=Oswald:wght@200;300;400;500;600;700&family=Pacifico&family=Sen:wght@400;700;800&display=swap">
    @if($popup)
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    @endif
</head>

@foreach($defaultCSSLinks as $link)
    <link rel="stylesheet" type="text/css" href="{{$link}}">
@endforeach
<body>
<div>
    {!! $html !!}
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if($popup)
    <script type="text/javascript">
        $(document).ready(function () {
            @if($popup->type === 'show_on_load')
            $('#modal{{$popup->id}}').modal('show');
            @endif

            @if($popup->type === 'show_after_sometime')
            setTimeout(function(){
                $('#modal{{$popup->id}}').modal('show');
            }, {{$popup->delay * 1000}});
            @endif

            @if($popup->type === 'show_before_closing')
            $(document).bind("mouseleave", function(e) {
                $('#modal{{$popup->id}}').modal('show');
            });
            @endif
        });
    </script>
@endif

@foreach($defaultJSLinks as $link)
    <script src="{{$link}}"></script>
@endforeach
</body>
</html>
