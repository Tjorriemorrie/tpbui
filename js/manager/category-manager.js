app.factory('categoryManager', function(DB, $q, Category) {
    var categoryManager = {
        _pool: {},
        _retrieveInstance: function(categoryId, categoryData) {
            var instance = this._pool[categoryId];

            if (instance) {
                instance.setData(categoryData);
            } else {
                instance = new Category(categoryData);
                this._pool[categoryId] = instance;
            }

            return instance;
        },
        _search: function(categoryId) {
            return this._pool[+categoryId];
        },
        _load: function(categoryId, deferred) {
            var scope = this;

            var store = DB.transaction('category', 'readonly').objectStore('category');
            var req = store.get(+categoryId);
            req.onsuccess = function(event) {
                if (event.target.result == null) {
                    deferred.reject();
                } else {
                    var category = scope._retrieveInstance(event.target.result.code, event.target.result);
                    deferred.resolve(category);
                }
            };
            req.onerror = function(event) {
                deferred.reject(event);
            };
        },

        /* Public Methods */
        /* Use this function in order to get a category instance by it's id */
        find: function(categoryId) {
            var deferred = $q.defer();
            var category = this._search(categoryId);
            if (category) {
                deferred.resolve(category);
            } else {
                this._load(categoryId, deferred);
            }
            return deferred.promise;
        },

        /* Use this function in order to get instances of all the categories */
        findAll: function() {
            var deferred = $q.defer();
            var scope = this;
            var categories = [];
            var cursor = DB.transaction('category', 'readonly').objectStore('category').openCursor();
            cursor.onsuccess = function(event) {
                var cursor = event.target.result;
                if (cursor) {
                    var category = scope._retrieveInstance(cursor.value.code, cursor.value);
                    categories.push(category);
                    cursor.continue();
                } else {
                    deferred.resolve(categories);
                }
            };
            cursor.onerror = function() {
                deferred.reject();
            };
            return deferred.promise;
        },

        /*  This function is useful when we got somehow the category data and we wish to store it or update the pool and get a category instance in return */
        setCategory: function(categoryData) {
            var scope = this;
            var category = this._search(categoryData.id);
            if (category) {
                category.setData(categoryData);
            } else {
                category = scope._retrieveInstance(categoryData);
            }
            return category;
        }

    };

    return categoryManager;

});
