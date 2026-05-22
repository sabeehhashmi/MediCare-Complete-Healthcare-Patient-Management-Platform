// JavaScript Document

// mobile-detect
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

// Fix for supersized loader issue on hosting environments
function fixSupersizedLoader() {
    // Check if supersized loader exists and is visible
    var loader = $('#supersized-loader');
    if (loader.length > 0) {
        // Remove loader after 3 seconds if it's still there
        setTimeout(function() {
            if (loader.length > 0) {
                console.log('Removing stuck supersized loader');
                loader.remove();
            }
        }, 3000);
        
        // Also try to remove it when supersized is ready
        $(document).on('supersized:ready', function() {
            if (loader.length > 0) {
                console.log('Removing supersized loader on ready event');
                loader.remove();
            }
        });
    }
}

// Responsive YTPlayer - Optimized for faster loading
$(function(){
    var videoInitialized = false;
    var currentPlayer = null;
    
    // Function to determine if device is mobile based on screen width
    function isMobileDevice() {
        return window.innerWidth <= 768 || isMobile.any();
    }
    
    // Function to initialize the appropriate video player
    function initializeVideoPlayer() {
        if (videoInitialized) {
            console.log('Video already initialized, skipping...');
            return;
        }
        
        var isMobile = isMobileDevice();
        var desktopPlayer = $('#bgndVideo-desktop');
        var mobilePlayer = $('#bgndVideo-mobile');
        
        console.log('Screen width:', window.innerWidth);
        console.log('Is mobile device:', isMobile);
        console.log('YTPlayer available:', typeof $.fn.mb_YTPlayer !== 'undefined');
        
        // Hide both players initially
        desktopPlayer.hide();
        mobilePlayer.hide();
        
        // Check if YTPlayer is available
        if (typeof $.fn.mb_YTPlayer === 'undefined') {
            console.error('YTPlayer library not loaded!');
            return;
        }
        
        // Initialize the appropriate player based on screen size
        if (isMobile) {
            console.log('Initializing mobile video player');
            mobilePlayer.show();
            currentPlayer = mobilePlayer;
            
            try {
                // Force mobile video to be visible and properly positioned
                mobilePlayer.css({
                    'display': 'block !important',
                    'visibility': 'visible !important',
                    'opacity': '1 !important',
                    'position': 'fixed !important',
                    'top': '0 !important',
                    'left': '0 !important',
                    'width': '100% !important',
                    'height': '100% !important',
                    'z-index': '-1000 !important'
                });
                
                mobilePlayer.mb_YTPlayer();
                console.log('Mobile video player initialized successfully');
                videoInitialized = true;
                
                // Additional mobile video fixes
                setTimeout(function() {
                    var mobileWrapper = $('.mbYTP_wrapper');
                    if (mobileWrapper.length > 0) {
                        mobileWrapper.css({
                            'position': 'fixed !important',
                            'top': '0 !important',
                            'left': '0 !important',
                            'width': '100% !important',
                            'height': '100% !important',
                            'z-index': '-1000 !important',
                            'opacity': '1 !important'
                        });
                    }
                }, 500);
                
            } catch(e) {
                console.error('Error initializing mobile video player:', e);
            }
        } else {
            console.log('Initializing desktop video player');
            desktopPlayer.show();
            currentPlayer = desktopPlayer;
            
            try {
                desktopPlayer.mb_YTPlayer();
                console.log('Desktop video player initialized successfully');
                videoInitialized = true;
            } catch(e) {
                console.error('Error initializing desktop video player:', e);
            }
        }
    }
    
    // Wait for YTPlayer to be available with shorter timeout
    function waitForYTPlayer() {
        if (typeof $.fn.mb_YTPlayer !== 'undefined') {
            console.log('YTPlayer library loaded, initializing video players');
            initializeVideoPlayer();
        } else {
            console.log('Waiting for YTPlayer library to load...');
            setTimeout(waitForYTPlayer, 50); // Reduced from 100ms to 50ms
        }
    }
    
    // Initialize video player on page load with shorter timeout
    setTimeout(function() {
        waitForYTPlayer();
    }, 100); // Start after 100ms instead of immediately
    
    // Set a timeout for fallback if YTPlayer doesn't load within 3 seconds (reduced from 5)
    setTimeout(function() {
        if (!videoInitialized && typeof $.fn.mb_YTPlayer === 'undefined') {
            console.log('YTPlayer failed to load, using fallback video');
            initializeFallbackVideo();
        }
    }, 3000);
    
    // Handle window resize events with debouncing
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (videoInitialized) {
                // Only destroy and reinitialize if screen size category changed
                var wasMobile = currentPlayer.attr('id') === 'bgndVideo-mobile';
                var isNowMobile = isMobileDevice();
                
                if (wasMobile !== isNowMobile) {
                    console.log('Screen size category changed, reinitializing video');
                    videoInitialized = false;
                    
                    // Destroy existing players
                    if (typeof $.fn.mb_YTPlayer !== 'undefined') {
                        try {
                            $('#bgndVideo-desktop').mb_YTPlayer('destroy');
                            $('#bgndVideo-mobile').mb_YTPlayer('destroy');
                            console.log('Destroyed existing video players');
                        } catch(e) {
                            console.log('Error destroying players:', e);
                        }
                    }
                    
                    // Reinitialize with appropriate player
                    initializeVideoPlayer();
                }
            }
        }, 500); // Increased debounce time to 500ms
    });
    
    // Fallback function if YTPlayer fails
    function initializeFallbackVideo() {
        if (videoInitialized) return;
        
        var isMobile = isMobileDevice();
        var videoUrl = isMobile ? 
            'https://www.youtube.com/embed/UMfCe4C2XBI?autoplay=1&mute=1&loop=1&playlist=UMfCe4C2XBI&controls=0&showinfo=0&rel=0&modestbranding=1' :
            'https://www.youtube.com/embed/RnPazBlgdVA?autoplay=1&mute=1&loop=1&playlist=RnPazBlgdVA&controls=0&showinfo=0&rel=0&modestbranding=1';
        
        var iframe = $('<iframe>', {
            src: videoUrl,
            frameborder: '0',
            allowfullscreen: true,
            style: 'position: fixed; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; z-index: -1000; transform: translateX(-50%) translateY(-50%);'
        });
        
        $('body').prepend(iframe);
        console.log('Fallback video initialized');
        videoInitialized = true;
    }
});


// screen loader
	$(window).load(function() {
		// $('body').css('overflow', 'hidden');
		$('.screen-loader').fadeOut('slow');
	});


// preload
$(document).ready(function() {
	$('#preload').css({display: 'block'});
	
	// Fix for supersized loader issue
	fixSupersizedLoader();
});


// preload function
$(window).load(preLoader);
function preLoader() {
	setTimeout(function() {
		$('#preload').delay(250).fadeOut(1500);
		$('.screen-loader').delay(250).fadeOut(1500);
	});
};

// Additional loader cleanup
$(document).ready(function() {
	// Hide any remaining loaders after a timeout
	setTimeout(function() {
		$('#preload').fadeOut(500);
		$('.screen-loader').fadeOut(500);
		$('.pace').fadeOut(500);
		$('#supersized-loader').fadeOut(500);
		$('body').addClass('page-loaded');
	}, 3000);
	
	// Hide loaders when video is ready
	$(window).on('load', function() {
		setTimeout(function() {
			$('#preload').fadeOut(500);
			$('.screen-loader').fadeOut(500);
			$('.pace').fadeOut(500);
			$('#supersized-loader').fadeOut(500);
			$('body').addClass('page-loaded');
		}, 1000);
	});
	
	// Force hide loaders after 10 seconds
	setTimeout(function() {
		$('#preload').fadeOut(300);
		$('.screen-loader').fadeOut(300);
		$('.pace').fadeOut(300);
		$('#supersized-loader').fadeOut(300);
		$('body').addClass('page-loaded');
	}, 10000);
});


// preloader
  paceOptions = {
    ajax: false,     // disabled
    document: false, // disabled
    eventLag: false, // disabled
    elements: {
    selectors: ['body']
    }
  };


// Cycle2
$(document).ready( function(){
	
	$('#slider').cycle({
		fx: 'fade',
		timeout: 4000,
		speed: 2000,
		slides: '.slide'
	});
	
});


// fire home
	$("#fire-home").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$(".upper-page").fadeIn("slow");      
			$(".current").removeClass("current");
			$(".upper-page").addClass("current");
		});
	});
	
	
// fire about
	$("#fire-about").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#about").fadeIn("slow");
			$(".current").removeClass("current");
			$("#about").addClass("current");
		});
	});
	
	
// fire services
	$("#fire-services").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#services").fadeIn("slow");
			$(".current").removeClass("current");
			$("#services").addClass("current");
		});
	});
	
	
// fire contact
	$("#fire-contact").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#contact").fadeIn("slow");
			$(".current").removeClass("current");
			$("#contact").addClass("current");
		});
	});


// fire Find Pharmacy
	$(".fire-find-pharmacy").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#find-pharmacy").fadeIn("slow");
			$(".current").removeClass("current");
			$("#find-pharmacy").addClass("current");
		});
	});
	
	
// fire Find Doctor
	$(".fire-find-doctor").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#find-doctor").fadeIn("slow");
			$(".current").removeClass("current");
			$("#find-doctor").addClass("current");
		});
	});


// fire home mobile
	$("#fire-home-mobile").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$(".upper-page").fadeIn("slow");      
			$(".current").removeClass("current");
			$(".upper-page").addClass("current");
		});
	});
	
	
// fire about mobile
	$("#fire-about-mobile").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#about").fadeIn("slow");
			$(".current").removeClass("current");
			$("#about").addClass("current");
		});
	});
	
	
// fire services mobile
	$("#fire-services-mobile").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#services").fadeIn("slow");
			$(".current").removeClass("current");
			$("#services").addClass("current");
		});
	});
	
	
// fire contact mobile
	$("#fire-contact-mobile").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$("#contact").fadeIn("slow");
			$(".current").removeClass("current");
			$("#contact").addClass("current");
		});
	});
	

// fire closer
	$("#fire-about-closer, #fire-services-closer, #fire-contact-closer").click(function(e) {
		e.preventDefault();
		$(".current").fadeOut("slow", function() {
			$(".upper-page").fadeIn("slow");      
			$(".current").removeClass("current");
			$(".upper-page").addClass("current");
		});
	});


// menu active state
$('a.menu-state').click(function() {
    $('a.menu-state').removeClass("active");
    $(this).addClass("active");
});

// mobile menu functionality
$(document).ready(function() {
    // Mobile menu toggle
    $('.menu-mobile-trigger').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        var menuList = $('.menu-mobile-list');
        menuList.toggleClass('show');
    });
    
    // Hide menu when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('.menu-mobile').length) {
            $('.menu-mobile-list').removeClass('show');
        }
    });
    
    // Hide menu when clicking on menu items
    $('.menu-mobile-list a').click(function() {
        $('.menu-mobile-list').removeClass('show');
    });
    
    // Hide menu when navigating to other pages
    $('.menu-mobile-list a[href^="#"]').click(function() {
        $('.menu-mobile-list').removeClass('show');
    });
});


// niceScroll
// $(document).ready(function () {
//     // Only enable niceScroll on desktop devices
//     if (!isMobile.any()) {
//         $("body").niceScroll({
// 	       cursorcolor: "#fff",				 
// 	       cursorwidth: "5px",				 
// 	       cursorborder: "1px solid #fff",
// 	       cursorborderradius: "0px",
// 	       zindex: "9999",
// 	       scrollspeed: "70",
// 	       mousescrollstep: "50",
// 	       // background: "rgba(255, 255, 255, 0.1)"
//         });
//     }
// });


// niceScroll || scrollbars resize
// if (!isMobile.any() && $("body").getNiceScroll()) {
//     $("body").getNiceScroll().resize();
// }


// twitter ticker
      jQuery(function($){
        $("#ticker").tweet({
          username: "enihilo",
          page: 1,
          avatar_size: 0,
          count: 20,
          loading_text: ""
        }).bind("loaded", function() {
          var ul = $(this).find(".tweet_list");
          var ticker = function() {
            setTimeout(function() {
              ul.find('li:first').animate( {marginTop: '-75px'}, 500, function() {
                $(this).detach().appendTo(ul).removeAttr('style');
              });
              ticker();
            }, 8000);
          };
          ticker();
        });
      });
	  
	  
// twitter ticker settings
$(document).ready(function(){
        $(this).find(".tweet_list").list_ticker({
                speed: 8000,
                effect: 'fade' // fade, slide
        })             
})
			

// mobile-detect
	var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };


// Countdown Timer
// $(document).ready(function() {
//     // Get current date and time
//     var now = new Date();
    
//     // Set target date to November 15t5h    , 2025
//     var targetDate = new Date('2025-11-15T00:00:00');
    
//     var secondsLeft = Math.floor((targetDate.getTime() - now.getTime()) / 1000);
    
//     function updateTimer() {
//         var days = Math.floor(secondsLeft / (3600 * 24));
//         var hours = Math.floor((secondsLeft % (3600 * 24)) / 3600);
//         var minutes = Math.floor((secondsLeft % 3600) / 60);
//         var seconds = secondsLeft % 60;

//         $("#countdown .days").text(days);
//         $("#countdown .hours").text(hours);
//         $("#countdown .minutes").text(minutes);
//         $("#countdown .seconds").text(seconds);

//         if (secondsLeft > 0) {
//             secondsLeft--;
//         } else {
//             clearInterval(timerInterval);
//             $("#countdown").html('<li><span>Time\'s Up!</span></li>');
//         }
//     }

//     updateTimer();
//     var timerInterval = setInterval(updateTimer, 1000);
// });