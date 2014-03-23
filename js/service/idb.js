angular.module('app').factory('idb', function(DB) {

    var idb = {
        db: DB,
        storeReadCategory: null,
        storeWriteCategory: null,

        init: function() {
            console.info('idb.init');
            idb.storeReadCategory = idb.db.transaction('category', 'readonly').objectStore('category');
            idb.storeWriteCategory = idb.db.transaction('category', 'readwrite').objectStore('category');
        }
    };

    idb.init();

    return idb;
});
