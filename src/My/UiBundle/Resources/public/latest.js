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

        //console.log(torrentName);
        $('.list li').each(function(index, value) {
            var listName = $(value).text();
            //console.log(listName);
            var score = 0;
            for (var i=0; i<=torrentName.length; i++) {
                var letterTwo = torrentName[i] + torrentName[i+1];
                var letterThree = torrentName[i] + torrentName[i+1] + torrentName[i+2];
                //console.log(letter);
                if (listName.indexOf(letterTwo) !== -1) {
                    score += 0.50;
                }
                if (listName.indexOf(letterThree) !== -1) {
                    score += 0.50;
                }
            }
            var perc = score / torrentName.length;
            //console.log(index + ' scored ' + score / torrentName.length);
            var bold = (perc > 0.67) ? 'bold' : 'normal';
            $(value).css({opacity:perc, fontWeight:bold});
        });
    };

}(jQuery, window.Latest = window.Latest || {}));

Latest.init();
