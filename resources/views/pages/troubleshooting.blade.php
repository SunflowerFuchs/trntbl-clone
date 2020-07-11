@extends('layouts.general')

@section('title', 'Troubleshooting')

@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <h3 class="masthead-brand"><a href="{{ url('/') }}">{{ strtoupper(env('APP_NAME')) }}</a></h3>
        </div>
    </div>
    <div class="inner cover">
        <h1 class="cover-heading">Troubleshooting</h1>
        <h2>If no audio posts are loading, check the following:</h2>
        <ul style="display: inline-block; text-align: left">
            <li>Have you had tumblr open in this browser? Since the songs load directly from tumblr, you have to accept their cookie policy first.</li>
            <li>Is your profile set to public?</li>
            <li>If you're missing posts, are the audios directly uploaded to tumblr, or are they hosted third-party (soundcloud, spotify, etc.)? Currently, I only support audios directly on tumblr.</li>
            <li>Still experiencing problems? Feel free to <a href="https://github.com/sunflowerfuchs/trntbl-clone/issues/new/choose" class="cover-heading">open a new issue on github.</a></li>
        </ul>
    </div>
@endsection
