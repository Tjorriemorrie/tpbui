'use strict';

angular.module('app').service('scraper', function($http) {
    this.scrape = function(code, page) {
        var url = 'http://thepiratebay.se/browse/' + code + '/' + (page-1) + '/7';
        url = 'http://www.autotrader.co.za';
        console.info('scraper.scrape', url);
        //$http.jsonp(url + '?callback=JSON_CALLBACK').then(function(data, status, headers, config) {
        //$http.get(url).then(function(data, status, headers, config) {
          //  console.dir(status);
            //console.dir(data);
            //console.info('response', res);
//            console.info('html', res.data);
//            var doc = res.data;
//            var xpath = doc.data.evaluate('p', doc, null, XPathResult.ANY_TYPE, null);
//            console.info('xpath', xpath);
        //});
        var urlYql = "http://query.yahooapis.com/v1/public/yql?"+
            "q=select%20*%20from%20html%20where%20url%3D%22"+
            encodeURIComponent('http://www.google.com')+
            "%22&format=xml&callback=JSON_CALLBACK";
        console.info(urlYql);
        $http.jsonp(urlYql).then(function(res) {
                console.info(res);
            });
//                if(data.results[0]){
//                    var data = filterData(data.results[0]);
//                    container.html(data);
    };
});
