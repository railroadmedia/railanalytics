<!doctype html>

<html lang="en">
<head>

    <script type="text/javascript">
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>

    @if(request()->has('headTop'))
        {!! request()->get('headTop') !!}
    @else
        {!! \Railroad\Railanalytics\Tracker::headTop() !!}
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Musora Tracking Page</title>
    <meta name="description" content="Musora Tracking Page">
    <meta name="author" content="Musora Media Inc">

    @if(request()->has('headBottom'))
        {!! request()->get('headBottom') !!}
    @else
        {!! \Railroad\Railanalytics\Tracker::headBottom() !!}
    @endif
</head>

<body>

@if(request()->has('bodyTop'))
    {!! request()->get('bodyTop') !!}
@else
    {!! \Railroad\Railanalytics\Tracker::bodyTop() !!}
@endif

@if(request()->has('bodyBottom'))
    {!! request()->get('bodyBottom') !!}
@else
    {!! \Railroad\Railanalytics\Tracker::bodyBottom() !!}
@endif

</body>
</html>
