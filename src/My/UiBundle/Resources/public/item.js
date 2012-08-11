$(function() {
    $(document).on('click', '.remove', function() {
        $('#log').prepend('<p>unwanted: ' + $(this).parents('dt').text() + '</p>');
        $.getJSON('/unwanted', {id: $(this).parents('dl').attr('id')}, function(response) {
            $('#' + response.tab).html(response.html);
        })
        .error(function(jqXHR) {
            $('#log').prepend('<p>' + jqXHR.status + " " + jqXHR.statusText + "<br>" + jqXHR.responseText + '</p>');
        });
    });

    $(document).on('click', '.download', function() {
        $('#log').prepend('<p>downloaded: ' + $(this).parents('dt').text() + '</p>');
        var magnet = $(this).attr('href');
        $.getJSON('/downloaded', {id: $(this).parents('dl').attr('id')}, function(response) {
            $('#' + response.tab).html(response.html);
            window.location = magnet;
        })
        .error(function(jqXHR) {
            $('#log').prepend('<p>' + jqXHR.status + " " + jqXHR.statusText + "<br>" + jqXHR.responseText + '</p>');
        });
        return false;
    });
});
