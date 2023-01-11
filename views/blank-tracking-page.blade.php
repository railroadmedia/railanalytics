<!doctype html>

<html lang="en">
<head>

    @php
        $cacheKey = 'none';
            \Illuminate\Support\Facades\Log::info('--- $cacheKeyIframe: ' . $cacheKey);

        if (!empty(request()->get('cache_key'))) {
            $cacheKey = request()->get('cache_key');
            \Illuminate\Support\Facades\Log::info('--- Iframe:cache_key: ' . $cacheKey);

            \Illuminate\Support\Facades\Log::info('--- inIframeData: ' . var_export(cache()->get($cacheKey), true));
        }


    @endphp

    @if(cache()->has($cacheKey))
        {!! cache()->get($cacheKey)['headTop'] ?? '' !!}
    @else
        {!! \Railroad\Railanalytics\Tracker::headTop() !!}
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Musora Tracking Page</title>
    <meta name="description" content="Musora Tracking Page">
    <meta name="author" content="Musora Media Inc">

    @if(cache()->has($cacheKey))
        {!! cache()->get($cacheKey)['headBottom'] ?? '' !!}
    @else
        {!! \Railroad\Railanalytics\Tracker::headBottom() !!}
    @endif

</head>

<body>

@if(cache()->has($cacheKey))
    {!! cache()->get($cacheKey)['bodyTop'] ?? '' !!}
@else
    {!! \Railroad\Railanalytics\Tracker::bodyTop() !!}
@endif

@if(cache()->has($cacheKey))
    {!! cache()->get($cacheKey)['bodyBottom'] ?? '' !!}
@else
    {!! \Railroad\Railanalytics\Tracker::bodyBottom() !!}
@endif

@php
    if (cache()->has($cacheKey)) {
//        cache()->delete($cacheKey);
    }
@endphp

</body>
</html>
