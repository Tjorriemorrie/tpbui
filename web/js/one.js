$(function()
{
	status('page loaded');
	loadAll();
});


$('.linkTorrent').live('click', function()
{
	var $torrentId = $(this).parents('li').attr('id')
	showTorrentDetails($torrentId);
});
function showTorrentDetails($torrentId)
{
	status('showing torrent details for id: ' + $torrentId);
	$('li#' + $torrentId + ' dl').slideToggle(200);
	if ($('li#' + $torrentId + ' dl dt.similar').text() == 'loading...') {
		$('li#' + $torrentId + ' dl dt.similar').load('/similar/' + $torrentId);
	}
}


$('.linkUnwanted').live('click', function()
{
	status('torrent unwanted');
	var li = $(this).parents('li');
	var categoryId = $(this).parents('div').attr('id');
	li.css({opacity:0.5}).siblings().css({opacity:0.5});
	li.slideUp(600, function()
	{
		$.get('/status/unwanted/' + li.attr('id'), function()
		{
			li.remove();
			loadCategory(categoryId);
			reload('bad');
		});
	});
});


$('.linkDownload').live('click', function(e)
{
	e.preventDefault();
	status('downloading torrent');
	var href = $(this).attr('href');
	var categoryId = $(this).parents('div').attr('id');
	var li = $(this).parents('li');
	li.css({opacity:0.5}).siblings().css({opacity:0.5});
	li.slideUp(600, function()
	{
		$.get('/torrent/busy/' + li.attr('id'), function()
		{
			li.remove();
			status('removed, reloading busy');
			loadCategory(categoryId);
			reload('busy');
			//window.open(href, 'download', 'width=10,height=10');
			window.location = href;
		});
	});
	return false;
});


$('.linkBusy').live('click', function()
{
	var $status = $(this).attr('title');
	var li = $(this).parents('li');
	li.css({opacity:0.5}).siblings().css({opacity:0.5});
	li.slideUp(600, function()
	{
		$.get('/status/' + $status + '/' + li.attr('id'), function()
		{
			li.remove();
			if ($status == 'cancelled' || $status == 'normal') {
				loadCategories();
			}
			if ($status == 'done' || $status == 'cancelled') {
				reload('busy');
			}
			if ($status == 'bad' || $status == 'normal') {
				reload('bad');
			}
		});
	});
});


var statusTimeout;
function status(text)
{
	$('#status').text(text).show();
	if (statusTimeout != null) clearTimeout(statusTimeout);
	statusTimeout = setTimeout(function()
	{
		$('#status').fadeOut(1000, function()
		{
			$('#status').html('&nbsp;').show();
		});
	}, 2000);
}


function loadAll()
{
	loadCategories();
	reload('busy');
	reload('done');
	reload('bad');
}


function loadCategories()
{
	status('loading all categories');
	$('.category').each(function()
	{
		loadCategory($(this).attr('id'));
	});
}


function loadCategory(categoryId)
{
	//$('#' + categoryId).css({opacity:0.5});
	var categoryName = $('#' + categoryId + ' .title').text();
	$('#' + categoryId + '.category ul').load('/load/category/' + categoryId, function()
	{
		status('loaded category ' + categoryId + ': ' + categoryName);
		var listCount = $('#' + categoryId + '.category li').length;
		if (listCount < 4) {
			scrapeCategory(categoryId);
		} else if (listCount < 5) {
			$('#' + categoryId + ' ul').append('<li>list finished</li>');
		}
	});
}


function scrapeCategory(categoryId)
{
	$('#' + categoryId + ' ul').append('<li>scraping...</li>');
	var categoryName = $('#' + categoryId + ' .title').text();
	$.get('/scrape/category/' + categoryId, function(data)
	{
		status(data.msg);
		$('#' + categoryId + ' .title').text(data.text);
		loadCategory(categoryId);
	}, 'json');
}


function reload($box)
{
	//$('#' + $box).css({opacity:0.5});
	$('#' + $box + ' ul').load('/load/' + $box, function()
	{
		status($box + ' loaded');
	});
}

