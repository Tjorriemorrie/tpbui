;(function($, Latest, undefined) {

    Latest.init = function() {
        $('body').on('click', '.icon-download, .icon-remove', Latest.update);
        $(window).scroll(Latest.keepTop);
        Latest.update();
        Latest.keepTop();
    };

    Latest.update = function(event) {
        $('#latest').html('<ol class="list"></ol>');
        $('.torrent.alert-success').each(function(i) {
            $('#latest ol').append('<li>' + $(this).children('dt').text() + '</li>');
        });
    };

    Latest.keepTop = function() {
        var st = $(window).scrollTop();
        var ot = $("#latest-anchor").offset().top;
        var s = $("#latest");
        if (st > ot) {
            s.css({
                position: "fixed",
                top: "0px"
            });
        } else {
            if(st <= ot) {
                s.css({
                    position: "relative",
                    top: ""
                });
            }
        }
    };

}(jQuery, window.Latest = window.Latest || {}));

Latest.init();
