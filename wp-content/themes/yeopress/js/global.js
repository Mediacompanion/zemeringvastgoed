require.config({
	"baseUrl": "wp-content/themes/yeopress/js",
	"paths": {
		"jquery": "vendor/jquery/jquery"
	}
});

// Shows / hides navigation on click #hamburger 
$('#hamburger').click(function(){
	if( $('#main-navigation').hasClass('visible') ) {
		$('#main-navigation').removeClass('visible');
	} else {
		$('#main-navigation').addClass('visible');
	}
});

// Activates the banner slider
$(document).ready(function(){
	$('#banner .slider').slick({
		autoplay: true,
		infinite: true,
		slidesToShow: 1,
		prevArrow: $('#banner .slick-prev'),
		nextArrow: $('#banner .slick-next'),
	});
});

// Activates the project slider
$(document).ready(function(){
	$('#project-single .slider').slick({
		autoplay: true,
		infinite: true,
		centerMode: true,
		slidesToShow: 3,
		centerPadding: '0px',
		prevArrow: $('#project-single .slick-prev'),
		nextArrow: $('#project-single .slick-next'),
	});
});

//Replace banner slider img src with background(url:)
$('#banner .slider .slide img').each(function() {  
	imgsrc = this.src;
	$(this).hide();
	$(this).parent().css("background", "url(" + imgsrc + ") center center no-repeat");
	$(this).parent().css("background-size", "cover");
});

// Activates the banner slider
$(document).ready(function(){
	$('#home-projecten .projecten-slider').slick({
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		centerMode: true,
		centerPadding: '0px',
		prevArrow: $('#home-projecten-banner .slick-prev'),
		nextArrow: $('#home-projecten-banner .slick-next'),
		responsive: 
		[{
		breakpoint: 1024,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				adaptiveHeight: true,
			}
		}]
	});
});

$(document).ready(function() {
	$(".fancybox").fancybox();
});

//Replace projecten slider img src with background(url:)
$('#home-projecten .projecten-slider .slide .projecten-image img').each(function() {  
	imgsrc = this.src;
	$(this).hide();
	$(this).parent().css("background", "url(" + imgsrc + ") center center no-repeat");
	$(this).parent().css("background-size", "cover");
});

//Replace page-banner img src with background(url:)
$('#page-banner img').each(function() {  
	imgsrc = this.src;
	$(this).hide();
	$(this).parent().css("background", "url(" + imgsrc + ") center center no-repeat");
	$(this).parent().css("background-size", "cover");
});

//Replace page-banner img src with background(url:)
$('#projecten-overview img').each(function() {  
	imgsrc = this.src;
	$(this).hide();
	$(this).parent().css("background", "url(" + imgsrc + ") center center no-repeat");
	$(this).parent().css("background-size", "cover");
});

//Replace page-banner img src with background(url:)
$('.project-single .slider .slide img').each(function() {  
	imgsrc = this.src;
	$(this).hide();
	$(this).parent().css("background", "url(" + imgsrc + ") center center no-repeat");
	$(this).parent().css("background-size", "cover");
});