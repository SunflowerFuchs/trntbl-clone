var apiurl = $('#apiurl').val();

var player;
var lastid;
var tries = 0;
var maxRetries = 10;
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

$(document).ready(function () {
    $('.pre-scrollable').scroll(function () {
        if (loading.css('display') === 'none' && Math.ceil($(this).scrollTop() + $(this).height())>=$(this)[0].scrollHeight) {
            loadPage(currentid++);
        }
    });

    player = new MediaElementPlayer('audioplayer', {
        pluginPath: 'https://cdn.jsdelivr.net/npm/mediaelement@4.2.5/build/',
        features: ['playlist', 'prevtrack', 'nexttrack', 'playpause', 'current', 'progress', 'duration', 'tracks', 'volume', 'fullscreen'],
        startVolume: volume,
        autoplay: true,
        success: function (mediaPlayer) {
            mediaPlayer.addEventListener("play", function(){
                player.addToHistory(player, player.getSrc(), player.artistinfo);
                artistinfo.text(player.artistinfo);
                player.play();
            });

            mediaPlayer.addEventListener("ended", function(){
                loadNextMedia();
            });

            mediaPlayer.addEventListener("volumechange", function () {
                volume = player.getVolume();
                setCookie("volume", volume, 90);
            });

            loadPage(currentid++).then(function () {
                loadNextMedia();
            });
        }
    });
});

function loadPage(pagenum) {
    loading.show();

    params = params.replace(/#.+/, ''); // remove hash, if any is set. prevents parameters otherwise

    return $.getJSON(apiurl + params + "?page=" + pagenum, function (data) {
        if (typeof data.posts === "undefined") {
            var message = 'No more posts!';
            if (pagenum === 1) message = 'No posts found!';
            loading.after($('<div>' + message + '</div><br><a href="/"><b>Back</b></a>'));
            loading.remove();
            return;
        }

        data.posts.data.forEach(function (post) {
            var artist = '';
            var name = '';

            if (post.hasOwnProperty('artist')) {
                artist = post.artist.toString();
            } else if(post.hasOwnProperty('source_title')) {
                artist = '@' + post.source_title.toString();
            }

            if (post.hasOwnProperty('track_name')) {
                name = post.track_name.toString();
            } else if (post.hasOwnProperty('summary')) {
                name = post.summary.toString();
            }

            createListItem('glyphicon-play', post.id, name, artist, post.audio_url, post.post_url, (post.album_art));
        });

        loading.hide();

        $('.glyphicon-play').on('click', function () {
            updateMedia('id', $( this ).parent().attr('id'));
        });

        if (data.posts.data.length === 0) {
            loading.remove();
        }

        total = data.posts.total;
    });
}

function createListItem(button, id, trackname, trackartist, trackurl, originalurl, trackimage) {
    if (typeof id === "undefined") return;
    if (typeof trackname === "undefined") trackname = '';
    if (typeof trackartist === "undefined") trackartist = '';

    var tablebody = $('#posts-table-body');
    var tempinfo = trackname + ((trackname.trim().length > 0 && trackartist.trim().length > 0) ? " - " : "") + trackartist;

    var row = "<tr>";
    row += '<td id="' + id + '"><span class="glyphicon ' + button + '" aria-hidden="true"></span></td>';
    row += '<td id="name_' + id + '">' + trackname + '</td>';
    row += '<td id="artist_' + id + '">' + trackartist + '</td>';

    //Image
    if (typeof trackimage !== "undefined") {
        row += '<td><img class="albumart" src="' + trackimage + '" class="img-responsive"></td>';
    } else {
        row += '<td></td>';
    }

    row += '<td id="submenu_' + id + '"><div style="position: relative;">';
    row += '<span role="button" id="dd_\' + id + \'" data-toggle="dropdown" class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span>';
    row += '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dd_' + id + '">';
    row += '<li><a href="' + originalurl + '" target="_blank">Open post</a></li>';
    row += '<li class="queue"><a role="button">Add to queue</a></li>';
    row += '</ul></div></td>';
    row += '<input type="hidden" id="source_' + id + '" value="' + trackurl + '">';
    row += '</tr>';

    tablebody.append(row);
    tablebody.find('#submenu_' + id + ' .queue').on('click', function () {
        player.addToPlaylist(player, trackurl, tempinfo, -1);
    });

}

function loadNextMedia() {
    var next;
    // if there's anything in the queue, skip this function so we don't overwrite it
    if (player.nextPlaylistTrack(player)) {
        return;
    }

    // if there are no songs, bail out
    if (total === 0) {
        artistinfo.text('');
        return;
    }

    if (shuffle[0].checked) {
        var offset = Math.floor(Math.random() * (total - 1));
        updateMedia('offset', offset);
    } else if (typeof lastid === "undefined") {
        updateMedia('offset', 0);
    } else {
        var current = $('#' + lastid);
        if (current.parent().nextAll('tr').length === 0) {
            loadPage(currentid++).then(function (data) {
                if (data.posts.data.length === 0) {
                    next = current.parent().parent().children().first().children().first();
                } else {
                    next = current.parent().next().children().first();
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
            var name = $('#name_' + id).text();
            var artist =  $('#artist_' + id).text();
            var info = name + ((name.trim().length > 0 && artist.trim().length > 0) ? " - " : "") + artist;
            player.artistinfo = info;
            player.setSrc($('#source_' + id).val());
            player.load();
            break;
        case 'offset':
            var split = params.split('/');
            $.getJSON(apiurl + "/" + source + "/" + split[1] + "/" + id + (split.length === 3 ? "/" + split[2] : ""), function (data) {
                if (typeof data.error === "undefined" && data.posts.length > 0) {
                    lastid = data.posts[0].id;
                    var artist = '';
                    var name = '';
                    var info;

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

                    info = name + ((name.trim().length > 0 && artist.trim().length > 0) ? " - " : "") + artist;
                    player.artistinfo = info;
                    player.setSrc(data.posts[0].audio_url);
                    player.load();
                } else {
                    if (tries <= maxRetries && !stopLoading) {
                        tries += 1;
                        updateMedia(source, id + 1);
                    } else {
                        tries = 0;
                        artistinfo.text('Couldn\'t load audio, please try again');
                    }
                }
            });
            break;
    }
}

shuffle.on('change', function() {
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