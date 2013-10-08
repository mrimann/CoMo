$(document).ready(function() {
	// reload the whole page every 10min
	window.setTimeout('reloadWholePage()', 600000);

	$(".topicAwardsSlider").bxSlider({
		pager: false,
		controls: false,
		auto: true,
		minSlides: 3,
		maxSlides: 3,
		moveSlides: 1,
		slideWidth: 350
	});
});

/**
 * Fades the page out a bit, then reloads the whole page
 */
function reloadWholePage() {
	$('body div, body p').fadeTo('fast', 0.5);
	location.reload();
}