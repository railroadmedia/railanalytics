<!doctype html>

<html lang="en">
<head>

    @if(!empty($cacheKey))
        {!! $cacheTrackingData['headTop'] ?? '' !!}
    @else
        {!! \Railroad\Railanalytics\Tracker::headTop() !!}
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Musora Tracking Page</title>
    <meta name="description" content="Musora Tracking Page">
    <meta name="author" content="Musora Media Inc">

    @if(!empty($cacheKey))
        {!! $cacheTrackingData['headBottom'] ?? '' !!}
    @else
        {!! \Railroad\Railanalytics\Tracker::headBottom() !!}
    @endif

</head>

<body>

@if(!empty($cacheKey))
    {!! $cacheTrackingData['bodyTop'] ?? '' !!}
@else
    {!! \Railroad\Railanalytics\Tracker::bodyTop() !!}
@endif

@if(!empty($cacheKey))
    {!! $cacheTrackingData['bodyBottom'] ?? '' !!}
@else
    {!! \Railroad\Railanalytics\Tracker::bodyBottom() !!}
@endif

</body>
</html>
