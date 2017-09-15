@extends('layouts.trntbl')

@section('title', (isset($user)?$user:'listening...') . ' - ' . env('APP_NAME'))


@section('content')
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
    <img id="loading" src="/img/loading-bar.gif" style="width:50%; display:block; position:absolute; left:0; right:0; top:50%; margin:auto;">
@endsection


@section('scripts')
    <script src="http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelement-and-player.min.js"></script>

    <script type="text/javascript">
        var player;
        var lastid;
        var total = 0;
        var currentid = 1;
        var loading = $('#loading');
        var shuffle = $('#shuffle');
        var artistinfo = $('#artistinfo');
        var params = window.location.href.toString().split(window.location.host)[1];

        var volume = 0.5;
        var cookieVolume = getCookie("volume");
        if (cookieVolume !== "") {
            volume = parseFloat(cookieVolume);
        }

        function loadPage(pagenum) {
            loading.show();

            return $.getJSON("{{ url('/api/json') }}" + params + "?page=" + pagenum, function (data) {
                data.posts.data.forEach(function (post) {
                    var artist = '';
                    var name = '';

                    if (post.hasOwnProperty('artist')) {
                        artist = post.artist;
                    } else if(post.hasOwnProperty('source_title')) {
                        artist = '@' + post.source_title;
                    }

                    if (post.hasOwnProperty('track_name')) {
                        name = post.track_name;
                    } else if (post.hasOwnProperty('summary')) {
                        name = post.summary;
                    }

                    createListItem('glyphicon-play', post.id, name, artist, post.audio_url, post.post_url, (post.album_art));
                });

                loading.hide();

                $('.glyphicon-play').click(function () {
                    updateMedia('id', $( this ).parent().attr('id'));
                });

                if (data.posts.data.length === 0) {
                    loading.remove();
                }

                total = data.posts.total;
            });
        }

        function createListItem(button, id, trackname, trackartist, trackurl, originalurl, trackimage) {
            var row = "<tr>";
            row += '<td id="' + id + '"><span class="glyphicon ' + button + '" aria-hidden="true"></span></td>';
            row += '<td id="name_' + id + '">' + trackname + '</td>';
            row += '<td id="artist_' + id + '">' + trackartist + '</td>';

            //Image
            if (typeof trackimage !== undefined) {
                row += '<td><img src="' + trackimage + '" class="img-responsive" style="width: 5rem;"></td>';
            }

            row += '<td><a href="' + originalurl + '" target="_blank"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a></td>';
            row += '<input type="hidden" id="source_' + id + '" value="' + trackurl + '">';
            row += '</tr>';

            $('#posts-table-body').append(row);
        }

        $(document).ready(function () {
            loadPage(currentid++).then(function (data) {
                loadNextMedia();
            });

            $('.pre-scrollable').scroll(function () {
                if (loading.css('display') === 'none' && Math.ceil($(this).scrollTop() + $(this).height())>=$(this)[0].scrollHeight) {
                    loadPage(currentid++);
                }
            });

            player = new MediaElementPlayer('audioplayer', {
                pluginPath: 'http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/',
                startVolume: volume,
                success: function (mediaPlayer, node) {
                    mediaPlayer.addEventListener('ended', function(e){
                        loadNextMedia();
                    });

                    mediaPlayer.addEventListener("volumechange", function () {
                        setCookie("volume", mediaPlayer.volume, 90);
                    });

                    mediaPlayer.addEventListener("progress", function () {
                        /*var x = new XMLHttpRequest();
                        x.open('GET', mediaPlayer.getSrc(), true);
                        x.onreadystatechange = function () {
                            console.log(this.getResponseHeader('content-type'));
                        };
                        x.send();*/
                        // TODO: check, if audio can be played, if not, skip or throw an error
                    });
                }
            });
        });

        function loadNextMedia() {
            if (shuffle[0].checked) {
                var offset = Math.floor(Math.random() * (total - 1));
                updateMedia('offset', offset);
            } else if(typeof lastid === "undefined") {
                updateMedia('offset', 0);
            } else {
                var current = $('#' + lastid);
                if (current.parent().nextAll('tr').length === 0) {
                    loadPage(currentid++).then(function (data) {
                        if (data.posts.data.length === 0) {
                            var next = current.parent().parent().children().first().children().first();
                        } else {
                            var next = current.parent().next().children().first();
                        }

                        updateMedia('id', next.attr('id'));
                    });
                } else {
                    next = current.parent().next().children().first();
                    updateMedia('id', next.attr('id'));
                }
            }
        }

        function updateMedia(source, id) {
            player.pause();
            artistinfo.text('Loading...');

            switch(source) {
                case 'id':
                    lastid = id;
                    player.setSrc($('#source_' + id).val());
                    player.load();
                    var name = $('#name_' + id).text();
                    var artist =  $('#artist_' + id).text();
                    artistinfo.text(name + ((name.trim().length > 0 && artist.trim().length > 0) ? " - " : "") + artist);
                    break;
                case 'offset':
                    var split = params.split('/');
                    $.getJSON("{{ url('/api/json') }}/" + source + "/" + split[1] + "/" + id + (split.length === 3 ? "/" + split[2] : ""), function (data) {
                        if (typeof data.error === "undefined" && data.posts.length > 0) {
                            lastid = data.posts[0].id;
                            player.setSrc(data.posts[0].audio_url);
                            player.load();
                            var artist = '';
                            var name = '';

                            if (data.posts[0].hasOwnProperty('artist')) {
                                artist = data.posts[0].artist;
                            } else if (data.posts[0].hasOwnProperty('source_title')) {
                                artist = '@' + data.posts[0].source_title;
                            }

                            if (data.posts[0].hasOwnProperty('track_name')) {
                                name = data.posts[0].track_name;
                            } else if (data.posts[0].hasOwnProperty('summary')) {
                                name = data.posts[0].summary;
                            }
                            artistinfo.text(name + ((name.trim().length > 0 && artist.trim().length > 0) ? " - " : "") + artist);
                        } else {
                            artistinfo.text('Couldn\'t load audio, please try again');
                        }
                    });
                    break;
            }
        }

        shuffle.change(function() {
            if(this.checked) {
                setCookie("shuffle", "true");
            } else {
                setCookie("shuffle", "", "01 Jan 1970 00:00:00 UTC");
                lastid = undefined;
            }
        });

        var shuffleCookie = getCookie("shuffle");
        if (shuffleCookie !== "") {
            shuffle.prop('checked', true);
        }
    </script>
@endsection

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/mediaelementplayer.min.css">

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