angular.module('app').controller('categoryBrowseController', function($scope, category, scraper) {

    $scope.category = category;

    scraper.scrape(category.code, 1);

});
