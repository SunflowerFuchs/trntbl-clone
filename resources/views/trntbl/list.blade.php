@extends('layouts.trntbl')

@section('title', (isset($user)?$user:'listening...') . ' - ' . env('APP_NAME'))


@section('content')
    <input type="hidden" value="{{ url('/api/json') }}" id="apiurl">
    <div class="masthead clearfix">
        <div class="inner">
            <audio id="audioplayer" class="center-block" autoplay="autoplay"></audio>
            <div class="marquee">
                <marquee id="artistinfo">Loading...</marquee>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="shuffle"> Shuffle
                </label>
            </div>
        </div>
    </div>

    <div class="inner cover pre-scrollable">
        <!-- Add table with posts here -->
        <table class="table" id="posts-table">
            <tbody id="posts-table-body"></tbody>
        </table>
    </div>
    <img id="loading" src="/img/loading-bar.gif" style="display:block; position:absolute; left:0; right:0; top:50%; margin:auto;">
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelement-and-player.min.js"></script>

    <script src="{{ asset('js/playlist.js', true) }}?cbr={{ File::lastModified( public_path() . '/js/playlist.js' )  }}"></script>
    <script src="{{ asset('js/player.js', true) }}?cbr={{ File::lastModified( public_path() . '/js/player.js' )  }}"></script>
@endsection

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelementplayer.min.css">
    <link rel="stylesheet" href="{{ asset('css/playlist.css', true) }}?cbr={{ File::lastModified( public_path() . '/css/playlist.css' )  }}">

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