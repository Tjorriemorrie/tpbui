;(function($, Latest, undefined) {

    Latest.init = function() {
        $('body').on('click', '.icon-download, .icon-remove', Latest.update);
        $('body').on('mouseover', '.torrent', Latest.highlight);

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

    Latest.highlight = function(event) {
        var el = $(event.currentTarget);
        var torrentName = el.find('dt').text();

        console.log(torrentName);
        $('.list li').each(function(index, value) {
            var listName = $(value).text();
            //console.log(listName);
            var score = 0;
            for (var i=0; i<=torrentName.length; i++) {
                var letter = torrentName[i] + torrentName[i+1];
                //console.log(letter);
                if (listName.indexOf(letter) !== -1) {
                    score++;
                }
            }
            var perc = score / torrentName.length;
            //console.log(index + ' scored ' + score / torrentName.length);
            $(value).css({opacity:perc});
        });
    };

}(jQuery, window.Latest = window.Latest || {}));

Latest.init();
