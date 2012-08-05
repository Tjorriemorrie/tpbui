$(function() {
    $('.nav-tabs a:first').tab('show');
    scrape();
});


function scrape() {
    console.log('scraping');
    $.getJSON('/scrape', function(response) {
        console.log(response);
        $('#' + response.tab).html(response.content);
        setTimeout(function() {
            scrape();
        }, 2000);
    });
}
