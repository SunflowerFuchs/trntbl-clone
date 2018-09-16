'use strict';

// Feature configuration
Object.assign(mejs.MepDefaults, {
    // Any variable that can be configured by the end user belongs here.
    // Make sure is unique by checking API and Configuration file.
    // Add comments about the nature of each of these variables.
});


Object.assign(MediaElementPlayer.prototype, {

    playbacksExist: false,

    /**
     * Feature constructor.
     *
     * @param {MediaElementPlayer} player
     * @param {HTMLElement} controls
     * @param {HTMLElement} layers
     * @param {HTMLElement} media
     */
    buildplaylist(player, controls, layers, media) {
        this.createPlaybacks(player);

        // add to playlist once its loaded
        media.addEventListener("progress", player.progressCallback);

        // Once current element has ended, proceed to play next one
        media.addEventListener('ended', player.nextPlaylistCallback);
    },

    buildprevtrack(player) {
        this.createPlaybacks(player);

        const prevTitle = 'Previous';
        player.prevButton = document.createElement('div');
        player.prevButton.className = `${player.options.classPrefix}button ${player.options.classPrefix}prev-button`;
        player.prevButton.innerHTML = `<button type="button" aria-controls="${player.id}" title="${prevTitle}" aria-label="${prevTitle}" tabindex="0"></button>`;

        player.prevButton.addEventListener('click', player.prevPlaylistCallback);
        player.addControlElement(player.prevButton, 'prevtrack');
    },

    buildnexttrack(player) {
        this.createPlaybacks(player);

        const nextTitle = 'Next';
        player.nextButton = document.createElement('div');
        player.nextButton.className = `${player.options.classPrefix}button ${player.options.classPrefix}next-button`;
        player.nextButton.innerHTML = `<button type="button" aria-controls="${player.id}" title="${nextTitle}" aria-label="${nextTitle}" tabindex="0"></button>`;

        player.nextButton.addEventListener('click', player.nextPlaylistCallback);
        player.addControlElement(player.nextButton, 'nexttrack');
    },

    createPlaybacks(player) {
        if (!this.playbacksExist) {
            this.playbacksExist = true;

            player.historyPos = -1;
            player.playHistory = [];
            player.playHistoryData = [];
            player.loadNext = true;

            player.progressCallback = () => {
                var newSource = player.getSrc();
                var artistinfo = player.artistinfo;
                if (player.playHistory[player.historyPos] !== newSource) {
                    player.historyPos = this.addToPlaylist(newSource, artistinfo);
                }
            };

            player.prevPlaylistCallback = () => {
                if ((player.duration > 10 && player.currentTime > (player.duration / 10)) || player.historyPos === 0) {
                    player.currentTime = 0;
                } else if (player.playHistory[--player.historyPos]) {
                    player.setSrc(player.playHistory[player.historyPos]);
                    player.artistinfo = player.playHistoryData[player.historyPos];
                    player.load();
                } else {
                    ++player.historyPos;
                }
            };

            player.nextPlaylistCallback = () => {
                player.loadNext = true;
                if (player.playHistory[++player.historyPos]) {
                    player.artistinfo = player.playHistoryData[player.historyPos];
                    player.loadNext = false;
                    player.setSrc(player.playHistory[player.historyPos]);
                    player.load();
                } else {
                    --player.historyPos;
                    if (player.duration > 0.10) {
                        player.currentTime = player.duration - 0.10;
                    }
                }
            };

            player.addToQueue = (source, artistinfo) => {
                this.addToPlaylist(source, artistinfo, -1);
            }
        }
    },

    addToPlaylist(source, artistinfo, position) {
        if (typeof position === "undefined") position = player.historyPos + 1;
        else if (position <= -1) position = player.playHistory.length - (++position);

        player.playHistory.splice(position, player.playHistory.length - position);
        player.playHistoryData.splice(position, player.playHistoryData.length - position);
        player.playHistoryData.push(artistinfo);
        return player.playHistory.push(source) - 1;
    }
});