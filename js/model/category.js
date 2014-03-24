'use strict';

angular.module('app').factory('Category', function(torrentManager) {

    function Category(categoryData) {
        if (categoryData) {
            this.setData(categoryData);
        }
    }

    Category.prototype = {
        setData: function(categoryData) {
            angular.extend(this, categoryData);
        },
        delete: function() {
            console.info('Category.delete');
            //$http.delete('ourserver/categorys/' + categoryId);
        },
        update: function() {
            console.info('Category.update');
            //$http.put('ourserver/categorys/' + categoryId, this);
        },
        // associations
        getTorrents: function() {
            console.info('Category.getTorrents');
            return torrentManager.findByCategory(this.code);
        }
    };

    return Category;

});
