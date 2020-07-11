@extends('layouts.general')

@section('title', (isset($user)?$user:'listening...') . ' - ' . strtoupper(env('APP_NAME')))

@section('header')
    <div class="masthead">
        <div class="marquee">
            <marquee id="artistinfo">Loading...</marquee>
        </div>
        <audio id="audioplayer" class="center-block" autoplay="autoplay"></audio>
        <div class="checkbox col-med-6">
            <label>
                <input type="checkbox" id="shuffle"> Shuffle
            </label>
        </div>
    </div>
@endsection

@section('content')
    <div class="pre-scrollable">
        <input type="hidden" value="{{ url('/api/json') }}" id="apiurl">

        <table class="table" id="posts-table">
            <tbody id="posts-table-body"></tbody>
        </table>

        <img id="loading" src="/img/loading-bar.gif" style="display:block; position:absolute; left:0; right:0; top:50%; margin:auto;">
    </div>
@endsection

@section('footer')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelement-and-player.min.js"></script>

    <script src="{{ asset('js/playlist.js') }}?cbr={{ File::lastModified( public_path() . '/js/playlist.js' )  }}"></script>
    <script src="{{ asset('js/player.js') }}?cbr={{ File::lastModified( public_path() . '/js/player.js' )  }}"></script>
@endsection

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelementplayer.min.css">
    <link rel="stylesheet" href="{{ asset('css/playlist.css') }}?cbr={{ File::lastModified( public_path() . '/css/playlist.css' )  }}">

    <style>
        .site-wrapper, .site-wrapper-inner, .cover-container {
            height: 100%;
        }

        html {
            height: 100vh;
        }

        .masthead {
            position: relative;
        }
    </style>
@endsection
