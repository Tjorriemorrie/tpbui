angular.module('app').factory('categories', function(idb, $q) {

    var categories = {

        findAll: function() {
            var dfd = $q.defer();
            var categories = [];
            idb.storeReadCategory.openCursor().onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    categories.push(cursor.value);
                    cursor.continue();
                } else {
                    console.info('categories findAll', categories);
                    dfd.resolve(categories);
                }
            };
            return dfd.promise;
        },

        find: function(code) {
            var dfd = $q.defer();
            var req = idb.db.transaction('category', 'readonly').objectStore('category').get(code);
            req.onsuccess = function(event) {
                if (event.target.result == null) {
                    console.error('no result');
                    dfd.reject('empty');
                } else {
                    console.info('categories.find', code, event.target.result);
                    dfd.resolve(event.target.result);
                }
            };
            req.onerror = function(event) {
                alert('error');
                console.error(event);
                dfd.reject(event);
            };
            return dfd.promise;
        }
    };

    return categories;
});
