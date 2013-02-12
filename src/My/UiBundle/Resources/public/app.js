;(function($, App, undefined) {
    App.baseurl = $('body').data('base-url');

    App.init = function() {
        jQuery.ajaxSetup({
            error: function(jqXHR, textStatus, errorThrown) {
                //alert('[' + jqXHR.status + ' ' + jqXHR.statusText + '] ' + jqXHR.responseText);
                Scraper.scrape();
            }
        });
    };

}(jQuery, window.App = window.App || {}));

App.init();
