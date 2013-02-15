;(function($, Scraper, undefined) {
    var category = $('#category').data('category');
    var page = 1;

    Scraper.init = function() {
        Scraper.scrape();
    };

    Scraper.scrape = function() {
        var elPage = $('*[data-page="' + page + '"]');
        if (!elPage.length) {
            return;
        }

        elPage.children('*').css({opacity:0.33});
        $.getJSON(App.baseurl + '/scrape/' + category + '/' + page)
            .complete(function() {
                //elPage.children('*').css({opacity:0.33});
            })
            .success(function(response) {
                //console.log(response);
                elPage.replaceWith(response.html);
                page++;
                Scraper.scrape();
            });
    };
}(jQuery, window.Scraper = window.Scraper || {}));

Scraper.init();
