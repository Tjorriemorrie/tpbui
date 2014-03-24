'use strict';

angular.module('app').factory('Torrent', function() {

    function Torrent(torrentData) {
        if (torrentData) {
            this.setData(torrentData);
        }
        // Some other initializations related to torrent
    }

    Torrent.prototype = {
        setData: function(torrentData) {
            angular.extend(this, torrentData);
        },
        delete: function() {
            console.info('Torrent.delete');
            //$http.delete('ourserver/torrents/' + torrentId);
        },
        update: function() {
            console.info('Torrent.update');
            //$http.put('ourserver/torrents/' + torrentId, this);
        },
        getTorrents: function() {
            console.info('Torrent.getTorrents');
            return [];
        }
        /*
        isAvailable: function() {
            if (!this.torrent.stores || this.torrent.stores.length === 0) {
                return false;
            }
            return this.torrent.stores.some(function(store) {
                return store.quantity > 0;
            });
        }
        */
    };

    return Torrent;

});
