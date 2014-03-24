app.factory('torrentManager', function(DB, $q, Torrent) {
    var torrentManager = {
        _pool: {},
        _retrieveInstance: function(torrentId, torrentData) {
            var instance = this._pool[torrentId];

            if (instance) {
                instance.setData(torrentData);
            } else {
                instance = new Torrent(torrentData);
                this._pool[torrentId] = instance;
            }

            return instance;
        },
        _search: function(torrentId) {
            return this._pool[+torrentId];
        },
        _load: function(torrentId, deferred) {
            var scope = this;

            var store = DB.transaction('torrent', 'readonly').objectStore('torrent');
            var req = store.get(+torrentId);
            req.onsuccess = function(event) {
                if (event.target.result == null) {
                    deferred.reject();
                } else {
                    var torrent = scope._retrieveInstance(event.target.result.id, event.target.result);
                    deferred.resolve(torrent);
                }
            };
            req.onerror = function(event) {
                deferred.reject(event);
            };
        },

        /* Public Methods */
        /* Use this function in order to get a torrent instance by it's id */
        find: function(torrentId) {
            var deferred = $q.defer();
            var torrent = this._search(torrentId);
            if (torrent) {
                deferred.resolve(torrent);
            } else {
                this._load(torrentId, deferred);
            }
            return deferred.promise;
        },

        /* Use this function in order to get instances of all the torrents */
        findAll: function() {
            var deferred = $q.defer();
            var scope = this;
            var torrents = [];
            var cursor = DB.transaction('torrent', 'readonly').objectStore('torrent').openCursor();
            cursor.onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var torrent = scope._retrieveInstance(cursor.value.id, cursor.value);
                    torrents.push(torrent);
                    cursor.continue();
                } else {
                    deferred.resolve(torrents);
                }
            };
            cursor.onerror = function() {
                deferred.reject();
            };
            return deferred.promise;
        },

        /*  This function is useful when we got somehow the torrent data and we wish to store it or update the pool and get a torrent instance in return */
        setTorrent: function(torrentData) {
            var scope = this;
            var torrent = this._search(torrentData.id);
            if (torrent) {
                torrent.setData(torrentData);
            } else {
                torrent = scope._retrieveInstance(torrentData);
            }
            return torrent;
        },

        findByCategory: function(categoryCode) {
            var deferred = $q.defer();
            var scope = this;
            var torrents = [];
            var store = DB.transaction('torrent', 'readonly').objectStore('torrent');
            var range = IDBKeyRange.only(+categoryCode);
            var oc = store.index('category').openCursor(range);
            oc.onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var torrent = scope._retrieveInstance(cursor.value.id, cursor.value);
                    torrents.push(torrent);
                    cursor.continue();
                } else {
                    console.info('torrentManager.findByCategory', categoryCode, torrents);
                    deferred.resolve(torrents);
                }
            };
            oc.onerror = function() {
                deferred.reject();
            };
            return deferred.promise;
        }

    };

    return torrentManager;

});
