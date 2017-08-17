@extends('layouts.trntbl')

@section('title', (isset($user)?$user:'listening...') . ' - ' . env('APP_NAME'))


@section('content')
    <div class="masthead clearfix">
        <div class="inner">
            <input type="hidden" value="{{ $offset + 1 }}" id="offset">
            <input type="hidden" value="{{ $volume }}" id="volume">
            <input type="hidden" value="{{ $total_posts }}" id="total">
            <input type="hidden" value="{{ $toplay }}" id="toplay">
            <audio id="audioplayer" class="center-block"></audio>
            <div class="marquee">
                <marquee id="artistinfo"></marquee>
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
        <table class="table">
            @php
                $i = $offset + 1
            @endphp
            @foreach($posts as $post)
                <tr>
                    <td>
                        <span class="glyphicon glyphicon-play" aria-hidden="true" id="{{ $i }}"></span>
                        <input type="hidden" value="{{ $post['audio_source_url'] }}" id="audio_source_{{ $i }}">
                    </td>
                    <td id="trackname_{{ $i }}">
                        {{ isset($post['track_name'])?$post['track_name']:'' }}
                    </td>
                    <td id="artistname_{{ $i++ }}">
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

            if (parseInt($('#toplay').val()) > 0) {
                currentid = parseInt($('#toplay').val());
                $('#shuffle').attr('checked', true);
            }

            var player = new MediaElementPlayer('audioplayer', {
                pluginPath: 'http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/',
                startVolume: parseFloat($('#volume').val()),
                success: function (mediaPlayer, node) {
                    mediaPlayer.addEventListener('ended', function(e){
                        currentid += 1;
                        if ($('#audio_source_' + currentid).length) {
                            if ($('#shuffle')[0].checked) {
                                currentid = Math.floor(Math.random() * (parseInt($('#total').val()) - 1) + 1);
                                var pagenum = Math.floor(currentid / 20) + 1;
                                if (pagenum != parseInt("{!! $posts->currentPage() !!}")) {
                                    location.href ="{{ url()->current() }}?page=" + pagenum + "&volume=" + mediaPlayer.volume + "&toplay=" + currentid;
                                } else {
                                    updateMedia(mediaPlayer, $('#audio_source_' + currentid).val());
                                }
                            } else {
                                updateMedia(mediaPlayer, $('#audio_source_' + currentid).val());
                            }
                        } else {
                            //Load next page, if possible
                            if( "{{ $posts->nextPageUrl() }}".length ) {
                                location.href = "{{ $posts->nextPageUrl() }}&volume=" + mediaPlayer.volume;
                            }
                        }
                    });

                    if ($('#audio_source_' + currentid).length) {
                        updateMedia(mediaPlayer, $('#audio_source_' + currentid).val());
                    }
                }
            });

            $('.glyphicon-play').click(function () {
                currentid = parseInt($( this ).attr('id'));
                updateMedia(player.media, $('#audio_source_' + currentid).val());
            });

            function updateMedia(currentPlayer, source) {
                currentPlayer.setSrc(source);
                currentPlayer.load();
                currentPlayer.play();
                $('#artistinfo').text($('#trackname_' + currentid).text() +
                    (($('#trackname_' + currentid).text().trim().length > 0 && $('#artistname_' + currentid).text().trim().length > 0) ? " - " : "") +
                    $('#artistname_' + currentid).text());
            }
        });
    </script>
@endsection

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelementplayer.min.css">
@endsection