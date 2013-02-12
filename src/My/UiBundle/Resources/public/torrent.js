;(function($, Torrent, undefined) {

    Torrent.init = function() {
        $('body').on('click', '.icon-remove', unwanted);
        $('body').on('click', '.icon-download', download);
    };

    var unwanted = function(event) {
        var dl = $(event.target).parents('dl');
        dl.removeClass('alert-success');
        if (dl.hasClass('alert-danger')) {
            dl.removeClass('alert-danger');
        } else {
            dl.addClass('alert-danger');
        }
        $.getJSON(App.baseurl + '/torrent/unwanted/' + dl.data('torrentId'));
    };

    var download = function(event) {
        event.preventDefault();
        var magnet = $(event.target).parents('a').attr('href');
        var dl = $(event.target).parents('dl');
        dl.removeClass('alert-danger');
        if (dl.hasClass('alert-success')) {
            dl.removeClass('alert-success');
        } else {
            dl.addClass('alert-success');
        }
        window.location = magnet;
        $.getJSON(App.baseurl + '/torrent/download/' + dl.data('torrentId'));
    };
}(jQuery, window.Torrent = window.Torrent || {}));

Torrent.init();
