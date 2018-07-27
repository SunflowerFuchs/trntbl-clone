'use strict';

// Feature configuration
Object.assign(mejs.MepDefaults, {
    // Any variable that can be configured by the end user belongs here.
    // Make sure is unique by checking API and Configuration file.
    // Add comments about the nature of each of these variables.
});


Object.assign(MediaElementPlayer.prototype, {

    /**
     * Feature constructor.
     *
     * @param {MediaElementPlayer} player
     * @param {HTMLElement} controls
     * @param {HTMLElement} layers
     * @param {HTMLElement} media
     */
    buildplaylist(player, controls, layers, media) {
        // This allows us to access options and other useful elements already set.
        // Adding variables to the object is a good idea if you plan to reuse
        // those variables in further operations.
        const t = this;
        player.historyPos = 0;
        player.playHistory = [];
        player.playHistoryData = [];

        player.progressCallback = () => {
            var newSource = player.getSrc();
            if (player.playHistory[player.historyPos] !== newSource) {
                player.playHistory.splice(++player.historyPos, player.playHistory.length - player.historyPos);
                player.playHistoryData.splice(player.historyPos, player.playHistoryData.length - player.historyPos);
                player.historyPos = player.playHistory.push(newSource) - 1;
                player.playHistoryData.push(player.artistinfo);
            }
        };

        // add to playlist once its loaded
        media.addEventListener("progress", player.progressCallback);

        player.endedCallback = (e, f) => {
            if (player.historyPos < (player.playHistory.length - 1)) {
                player.setSrc(player.playHistory[player.historyPos]);
                player.artistinfo = player.playHistoryData[player.historyPos];
                player.load();
                e.returnValue = false;
            }
        };

        // Once current element has ended, proceed to play next one
        media.addEventListener('ended', player.endedCallback);
    },

    buildprevtrack(player) {
        const prevTitle = 'Previous';
        player.prevButton = document.createElement('div');
        player.prevButton.className = `${player.options.classPrefix}button ${player.options.classPrefix}prev-button`;
        player.prevButton.innerHTML = `<button type="button" aria-controls="${player.id}" title="${prevTitle}" aria-label="${prevTitle}" tabindex="0"></button>`;

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

        player.prevButton.addEventListener('click', player.prevPlaylistCallback);
        player.addControlElement(player.prevButton, 'prevtrack');
    },

    buildnexttrack(player) {
        const nextTitle = 'Next';
        player.nextButton = document.createElement('div');
        player.nextButton.className = `${player.options.classPrefix}button ${player.options.classPrefix}next-button`;
        player.nextButton.innerHTML = `<button type="button" aria-controls="${player.id}" title="${nextTitle}" aria-label="${nextTitle}" tabindex="0"></button>`;

        player.nextPlaylistCallback = () => {
            ;
            if (player.playHistory[++player.historyPos]) {
                player.setSrc(player.playHistory[player.historyPos]);
                player.artistinfo = player.playHistoryData[player.historyPos];
                player.load();
            } else {
                --player.historyPos;
                player.currentTime = player.duration - 0.10;
            }
        };

        player.nextButton.addEventListener('click', player.nextPlaylistCallback);
        player.addControlElement(player.nextButton, 'nexttrack');
    },
});