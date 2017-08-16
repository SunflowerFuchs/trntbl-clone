@extends('layouts.trntbl')

@section('title', (isset($user)?$user:'listening...') . ' - ' . env('APP_NAME'))


@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <input type="hidden" value="{{ $offset + 1 }}" id="offset">
            <input type="hidden" value="{{ $volume }}" id="volume">
            <audio id="audioplayer" class="center-block"></audio>
        </div>
    </div>

    <div class="inner cover pre-scrollable">
        <!-- Add table with posts here -->
        <table class="table">
            @php
                $i = $offset + 1
            @endphp
            @foreach($posts as $post)
                <tr>
                    <td>
                        <span class="glyphicon glyphicon-play" aria-hidden="true" id="{{ $i }}"></span>
                        <input type="hidden" value="{{ $post['audio_source_url'] }}" id="audio_source_{{ $i++ }}">
                    </td>
                    <td>
                        {{ isset($post['track_name'])?$post['track_name']:'' }}
                    </td>
                    <td>
                        {{ isset($post['artist'])?$post['artist']:(!isset($post['track_name'])?(!isset($post['source_title'])?$post['slug']:'@' . $post['source_title']):'') }}
                    </td>
                    <td>
                        {!! isset($post['album_art'])?'<img src="' . $post['album_art'] . '" class="img-responsive" style="width: 5rem;">':'' !!}
                    </td>
                    <td>
                        <a href="{{ $post['post_url'] }}" target="_blank">
                            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="mastfoot">
        <div class="inner">
            {{ $posts->links() }}
        </div>
    </div>
@endsection


@section('scripts')
    <script src="http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelement-and-player.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var currentid = parseInt($('#offset').val());
            var player = new MediaElementPlayer('audioplayer', {
                pluginPath: 'http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/',
                startVolume: parseFloat($('#volume').val()),
                success: function (mediaPlayer, node) {
                    mediaPlayer.addEventListener('ended', function(e){
                        currentid += 1;
                        if ($('#audio_source_' + currentid).length) {
                            mediaPlayer.setSrc($('#audio_source_' + currentid).val());
                            mediaPlayer.load();
                            mediaPlayer.play();
                        } else {
                            //Load next page, if possible
                            if( "{{ $posts->nextPageUrl() }}".length ) {
                                location.href = "{{ $posts->nextPageUrl() }}&volume=" + mediaPlayer.volume;
                            }
                        }
                    });

                    if ($('#audio_source_' + currentid).length) {
                        mediaPlayer.setSrc($('#audio_source_' + currentid).val());
                        mediaPlayer.load();
                        mediaPlayer.play();
                    }
                }
            });

            $('.glyphicon-play').click(function () {
                currentid = parseInt($( this ).attr('id'));
                player.setSrc($('#audio_source_' + currentid).val());
                player.media.load();
                player.media.play();
                //alert($('#audio_source_' + $( this ).attr('id')).val());
            });
        });
    </script>
@endsection

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelementplayer.min.css">
@endsection