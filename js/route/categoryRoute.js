angular.module('app').config(function($routeProvider) {

    $routeProvider

        .when('/', {
            templateUrl: 'js/view/categories/all.html',
            controller: 'categoryAllController',
            resolve: {
                categories: function(categories) {
                    return categories.findAll();
                }
            }
        })

        .when('/:code', {
            templateUrl: 'js/view/categories/browse.html',
            controller: 'categoryBrowseController',
            resolve: {
                category: function(categories, $route) {
                    return categories.find($route.current.params.code);
                }
            }
        })
    ;
});
