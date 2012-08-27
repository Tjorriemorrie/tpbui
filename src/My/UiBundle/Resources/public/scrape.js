$(function() {
    $('.nav-tabs a:first').tab('show');
    scrape();
});


function scrape() {
    $.getJSON('/scrape', function(response) {
//        console.log(response.html);
        $('#log').prepend('<p>finished scraping ' + response.tab + ':' + response.category + ':' + response.page + '</p>');
        $('#' + response.tab).html(response.html);
        setTimeout(function() {
            scrape();
        }, 2000);
    })
    .error(function(jqXHR) {
        if (jqXHR.statusText == 'error') {
            setTimeout(function() {
                scrape();
            }, 2000);
        }
        console.log(jqXHR);
//        alert('error!');
        $('#log').prepend('<p>' + jqXHR.status + " " + jqXHR.statusText + "<br>" + jqXHR.responseText + '</p>');
    });
}
