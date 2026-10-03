/**
 * ===================================================================
 * main js
 *
 * -------------------------------------------------------------------
 */
//
//
// Javascrip sha256 hash function
let sha256 = function sha256(ascii) {
	function rightRotate(value, amount) {
		return (value>>>amount) | (value<<(32 - amount));
	};
	
	let mathPow = Math.pow;
	let maxWord = mathPow(2, 32);
	let lengthProperty = 'length'
	let i, j; // Used as a counter across the whole file
	let result = ''

	let words = [];
	let asciiBitLength = ascii[lengthProperty]*8;
	
	//* caching results is optional - remove/add slash from front of this line to toggle
	// Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
	// (we actually calculate the first 64, but extra values are just ignored)
	let hash = sha256.h = sha256.h || [];
	// Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
	let k = sha256.k = sha256.k || [];
	let primeCounter = k[lengthProperty];
	/*/
	let hash = [], k = [];
	let primeCounter = 0;
	//*/

	let isComposite = {};
	for (let candidate = 2; primeCounter < 64; candidate++) {
		if (!isComposite[candidate]) {
			for (i = 0; i < 313; i += candidate) {
				isComposite[i] = candidate;
			}
			hash[primeCounter] = (mathPow(candidate, .5)*maxWord)|0;
			k[primeCounter++] = (mathPow(candidate, 1/3)*maxWord)|0;
		}
	}
	
	ascii += '\x80' // Append Ƈ' bit (plus zero padding)
	while (ascii[lengthProperty]%64 - 56) ascii += '\x00' // More zero padding
	for (i = 0; i < ascii[lengthProperty]; i++) {
		j = ascii.charCodeAt(i);
		if (j>>8) return; // ASCII check: only accept characters in range 0-255
		words[i>>2] |= j << ((3 - i)%4)*8;
	}
	words[words[lengthProperty]] = ((asciiBitLength/maxWord)|0);
	words[words[lengthProperty]] = (asciiBitLength)
	
	// process each chunk
	for (j = 0; j < words[lengthProperty];) {
		let w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
		let oldHash = hash;
		// This is now the undefinedworking hash", often labelled as letiables a...g
		// (we have to truncate as well, otherwise extra entries at the end accumulate
		hash = hash.slice(0, 8);
		
		for (i = 0; i < 64; i++) {
			let i2 = i + j;
			// Expand the message into 64 words
			// Used below if 
			let w15 = w[i - 15], w2 = w[i - 2];

			// Iterate
			let a = hash[0], e = hash[4];
			let temp1 = hash[7]
				+ (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
				+ ((e&hash[5])^((~e)&hash[6])) // ch
				+ k[i]
				// Expand the message schedule if needed
				+ (w[i] = (i < 16) ? w[i] : (
						w[i - 16]
						+ (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15>>>3)) // s0
						+ w[i - 7]
						+ (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2>>>10)) // s1
					)|0
				);
			// This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
			let temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) // S0
				+ ((a&hash[1])^(a&hash[2])^(hash[1]&hash[2])); // maj
			
			hash = [(temp1 + temp2)|0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
			hash[4] = (hash[4] + temp1)|0;
		}
		
		for (i = 0; i < 8; i++) {
			hash[i] = (hash[i] + oldHash[i])|0;
		}
	}
	
	for (i = 0; i < 8; i++) {
		for (j = 3; j + 1; j--) {
			let b = (hash[i]>>(j*8))&255;
			result += ((b < 16) ? 0 : '') + b.toString(16);
		}
	}
	return result;
};

//////////////////////////////////////////////////////////////////////////////////////////////////
(function($) {

	"use strict";

	/*---------------------------------------------------- */
	/* Preloader
	------------------------------------------------------ */
	$(window).on('load', function() {

		// will first fade out the loading animation
		$("#loader").fadeOut("slow", function() {

			// will fade out the whole DIV that covers the website.
			$("#preloader").delay(300).fadeOut("slow");

		});

	})

	/*----------------------------------------------------*/
	/*	Sticky Navigation
	------------------------------------------------------*/
	$(window).on('scroll', function() {

		var y = $(window).scrollTop(),
			topBar = $('header');

		if (y > 1) {
			topBar.addClass('sticky');
		}
		else {
			topBar.removeClass('sticky');
		}

	});


	/*-----------------------------------------------------*/
	/* Mobile Menu
 ------------------------------------------------------ */
	var toggleButton = $('.menu-toggle'),
		nav = $('.main-navigation');

	toggleButton.on('click', function(event) {
		event.preventDefault();

		toggleButton.toggleClass('is-clicked');
		nav.slideToggle();
	});

	if (toggleButton.is(':visible')) nav.addClass('mobile');

	$(window).resize(function() {
		if (toggleButton.is(':visible')) nav.addClass('mobile');
		else nav.removeClass('mobile');
	});

	$('#main-nav-wrap li a').on("click", function() {

		if (nav.hasClass('mobile')) {
			toggleButton.toggleClass('is-clicked');
			nav.fadeOut();
		}
	});


	/*----------------------------------------------------*/
	/* Highlight the current section in the navigation bar
	------------------------------------------------------*/
	var sections = $("section"),
		navigation_links = $("#main-nav-wrap li a");

	sections.waypoint({

		handler: function(direction) {

			var active_section;

			active_section = $('section#' + this.element.id);

			if (direction === "up") active_section = active_section.prev();

			var active_link = $('#main-nav-wrap a[href="#' + active_section.attr("id") + '"]');

			navigation_links.parent().removeClass("current");
			active_link.parent().addClass("current");

		},

		offset: '25%'

	});

/* Price selection */

	// Calculate milliseconds in a year
	const date = new Date();

	let day = date.getDate();
	let month = date.getMonth() + 1;
	let year = date.getFullYear();
	
	// This arrangement can be altered based on how we want the date's format to appear.
	let currentDate = `${day}${month}${year}`;
	// console.log(currentDate);

	const basic = sha256("0e35f6e9742e074dfd62e874de3c242c6d9b64c21bf9afbaf3d11f05579b4495" + currentDate);
	const standard = sha256("ef6691545d2c5523efed00424407cb261aeb0037d165ca5792f7f8bac3381362" + currentDate);
	const prof = sha256("19c73a5cdf346d967e544a2838600bcc16a9fd39a52a4ba39f7351fcc6a65d4e" + currentDate);

	// fake sha256sum hashes for Professional, Standard, and Basic accounts
	document.getElementById("basic").onclick = function() {
		window.location.href = "./registr.php?reg=" + basic;
	}
	document.getElementById("standard").onclick = function() {
		window.location.href = "./registr.php?reg=" + standard;
	}
	document.getElementById("professional").onclick = function(){
	  window.location.href = "./registr.php?reg=" + prof;
	}
	
	/*----------------------------------------------------*/
	/* Flexslider
	/*----------------------------------------------------*/
	//   	$(window).on('load', function() {

	// 	   $('#testimonial-slider').flexslider({
	// 	   	namespace: "flex-",
	// 	      controlsContainer: "",
	// 	      animation: 'slide',
	// 	      controlNav: true,
	// 	      directionNav: true,
	// 	      smoothHeight: true,
	// 	      slideshowSpeed: 7000,
	// 	      animationSpeed: 600,
	// 	      randomize: false,
	// 	      touch: true,
	// 	   });

	//    });


	/*----------------------------------------------------*/
	/* Smooth Scrolling
	------------------------------------------------------*/
	$('.smoothscroll').on('click', function(e) {

		e.preventDefault();

		var target = this.hash,
			$target = $(target);

		$('html, body').stop().animate({
			'scrollTop': $target.offset().top
		}, 800, 'swing', function() {
			window.location.hash = target;
		});

	});


	/*---------------------------------------------------- */
	/* FitVids
	------------------------------------------------------ */
	$(".fluid-video-wrapper").fitVids();


	/*---------------------------------------------------- */
	/*	Modal Popup
	------------------------------------------------------ */
	
	$('.video-link a').magnificPopup({
	
	   type:'inline',
	   fixedContentPos: false,
	   removalDelay: 200, 
	   showCloseBtn: false,
	   mainClass: 'mfp-fade'
	
	});
	
	$(document).on('click', '.close-popup', function (e) {
			e.preventDefault();
			$.magnificPopup.close();
	});



	/*----------------------------------------------------- */
	/* Back to top
 ------------------------------------------------------- */
	var pxShow = 300; // height on which the button will show
	var fadeInTime = 400; // how slow/fast you want the button to show
	var fadeOutTime = 400; // how slow/fast you want the button to hide
	// var scrollSpeed = slow; // how slow/fast you want the button to scroll to top. can be a value, 'slow', 'normal' or 'fast'

	// Show or hide the sticky footer button
	jQuery(window).scroll(function() {

		if (!($("#header-search").hasClass('is-visible'))) {

			if (jQuery(window).scrollTop() >= pxShow) {
				jQuery("#go-top").fadeIn(fadeInTime);
			} else {
				jQuery("#go-top").fadeOut(fadeOutTime);
			}

		}

	});

})(jQuery);
