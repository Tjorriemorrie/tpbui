angular.module('app').config(function($routeProvider) {

    $routeProvider

        .when('/', {
            templateUrl: 'js/view/categories/all.html',
            controller: 'categoryAllController',
            resolve: {
                categories: function(categoryManager) {
                    return categoryManager.findAll();
                }
            }
        })

        .when('/:code', {
            templateUrl: 'js/view/categories/browse.html',
            controller: 'categoryBrowseController',
            resolve: {
                category: function($route, categoryManager) {
                    return categoryManager.find($route.current.params.code);
                }
            }
        })
    ;
});
