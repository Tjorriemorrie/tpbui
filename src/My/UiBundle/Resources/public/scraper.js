;(function($, Scraper, undefined) {
    var category = $('#category').data('category');
    var page = 1;
    Scraper.init = function() {
        scrape();
    };

    var scrape = function() {
        $.getJSON(App.baseurl + '/scrape/' + category + '/' + page)
            .success(function(response) {
                $('#' + response.tab).html(response.html);
                page++;
                scrape();
            });
    };
}(jQuery, window.Scraper = window.Scraper || {}));
