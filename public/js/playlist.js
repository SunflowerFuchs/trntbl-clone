'use strict';

// Feature configuration
Object.assign(mejs.MepDefaults, {
    // Any variable that can be configured by the end user belongs here.
    // Make sure is unique by checking API and Configuration file.
    // Add comments about the nature of each of these variables.
});


Object.assign(MediaElementPlayer.prototype, {

    valuesInitialized: false,
    historyPos: -1,
    playHistory: [],
    playHistoryData: [],

    /*
     * Here cometh the feature constructors
     */

    /**
     * @param {MediaElementPlayer} player
     */
    buildprevtrack: function(player) {
        const prevTitle = 'Previous';
        player.prevButton = document.createElement('div');
        player.prevButton.className = player.options.classPrefix + 'button ' + player.options.classPrefix + 'prev-button';
        player.prevButton.innerHTML = '<button type="button" aria-controls="' + player.id + '" title="' + prevTitle + '" aria-label="' + prevTitle + '" tabindex="0"></button>';

        player.prevButton.addEventListener('click', function() {
            player.prevPlaylistTrack(player);
        });
        player.addControlElement(player.prevButton, 'prevtrack');
    },

    /**
     * @param {MediaElementPlayer} player
     */
    buildnexttrack: function(player) {
        const nextTitle = 'Next';
        player.nextButton = document.createElement('div');
        player.nextButton.className = player.options.classPrefix + 'button ' + player.options.classPrefix + 'next-button';
        player.nextButton.innerHTML = '<button type="button" aria-controls="' + player.id + '" title="' + nextTitle + '" aria-label="' + nextTitle + '" tabindex="0"></button>';

        player.nextButton.addEventListener('click', function() {
            player.nextPlaylistTrack(player);
        });
        player.addControlElement(player.nextButton, 'nexttrack');
    },

    /*
     * Let the actual functions begin
     */

    /**
     * @param {MediaElementPlayer} player
     * @param {String} source
     * @param {String} artistinfo
     */
    addToHistory: function(player, source, artistinfo) {
        if (player.playHistory[player.historyPos] !== source) {
            player.historyPos = this.addToPlaylist(player, source, artistinfo);
        }
    },

    /**
     * @param {MediaElementPlayer} player
     */
    prevPlaylistTrack: function(player) {
        if ((player.duration > 10 && player.currentTime > (player.duration / 10)) || player.historyPos === 0) {
            player.currentTime = 0;
            return true;
        } else if (player.playHistory[player.historyPos - 1]) {
            player.historyPos--;
            player.setSrc(player.playHistory[player.historyPos]);
            player.artistinfo = player.playHistoryData[player.historyPos];
            player.load();
            return true;
        }

        return false;
    },

    /**
     * @param {MediaElementPlayer} player
     */
    nextPlaylistTrack: function(player) {
        if (player.playHistory[++player.historyPos]) {
            player.artistinfo = player.playHistoryData[player.historyPos];
            player.setSrc(player.playHistory[player.historyPos]);
            player.load();
            return true;
        } else {
            --player.historyPos;
            if (player.duration > 0.10) {
                player.currentTime = player.duration - 0.10;
            }
            return false;
        }
    },

    /**
     * @param {MediaElementPlayer} player
     * @param {String} source
     * @param {String} artistinfo
     * @param {number} [position]
     */
    addToPlaylist: function(player, source, artistinfo, position) {
        if (typeof position === "undefined") position = player.historyPos + 1;
        else if (position <= -1) position = player.playHistory.length - (++position);

        player.playHistory.splice(position, player.playHistory.length - position);
        player.playHistoryData.splice(position, player.playHistoryData.length - position);
        player.playHistoryData.push(artistinfo);
        return player.playHistory.push(source) - 1;
    }
});