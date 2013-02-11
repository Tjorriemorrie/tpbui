;(function($, Scraper, undefined) {
    var category = $('#category').data('category');
    var page = 0;
    Scraper.init = function() {
        scrape();
    };

    var scrape = function() {
        $.getJSON(App.baseurl + '/scrape/' + category + '/' + page)
            .success(response) {
            $('#' + response.tab).html(response.html);
            setTimeout(function() {
                scrape();
            }, 2000);
        });
    };
}(jQuery, window.Scraper = window.Scraper || {}));
