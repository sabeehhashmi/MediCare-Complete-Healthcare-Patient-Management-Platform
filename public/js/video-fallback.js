// Mobile-Optimized Video Fallback
// Handles real mobile devices with stricter autoplay policies

$(document).ready(function() {
    console.log('Mobile-optimized video fallback script loaded');
    
    // Enhanced mobile detection for real devices
    function isRealMobileDevice() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent);
        var isSmallScreen = window.innerWidth <= 768;
        
        console.log('Mobile detection:', {
            userAgent: userAgent,
            isMobile: isMobile,
            isSmallScreen: isSmallScreen,
            windowWidth: window.innerWidth,
            isRealMobile: isMobile && (isMobile.Android() || isMobile.iOS())
        });
        
        return isMobile;
    }
    
    // Create mobile-optimized video
    function createMobileVideo() {
        console.log('Creating mobile-optimized video...');
        
        // Remove any existing video elements
        $('.video-background').remove();
        $('.mb_YTPlayer').remove();
        $('.mbYTP_wrapper').remove();
        
        var videoId = 'UMfCe4C2XBI'; // Mobile video ID
        
        console.log('Creating mobile video with ID:', videoId);
        
        // Create video container
        var videoContainer = $('<div class="video-background"></div>');
        
        // Create iframe with mobile-specific parameters
        var iframe = $('<iframe>', {
            src: 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&mute=1&loop=1&playlist=' + videoId + '&controls=0&showinfo=0&rel=0&modestbranding=1&enablejsapi=1&playsinline=1&origin=' + window.location.origin + '&iv_load_policy=3&fs=0',
            frameborder: '0',
            allowfullscreen: true,
            allow: 'autoplay; encrypted-media; picture-in-picture; fullscreen',
            style: 'position: fixed; top: 50%; left: 50%; min-width: 110%; min-height: 110%; width: auto; height: auto; z-index: -1000; transform: translateX(-50%) translateY(-50%) scale(1.1); pointer-events: none;'
        });
        
        // Add to container and body
        videoContainer.append(iframe);
        $('body').prepend(videoContainer);
        
        console.log('Mobile video iframe created and added to body');
        
        // Add overlay
        if (!$('.video-overlay').length) {
            var overlay = $('<div class="video-overlay"></div>');
            $('body').prepend(overlay);
            console.log('Video overlay added');
        }
        
        // Hide loaders
        $('#preload').fadeOut(500);
        $('.screen-loader').fadeOut(500);
        $('.pace').fadeOut(500);
        
        console.log('Mobile video creation complete');
    }
    
    // Create desktop video
    function createDesktopVideo() {
        console.log('Creating desktop video...');
        
        // Remove any existing video elements
        $('.video-background').remove();
        $('.mb_YTPlayer').remove();
        $('.mbYTP_wrapper').remove();
        
        var videoId = 'RnPazBlgdVA'; // Desktop video ID
        
        console.log('Creating desktop video with ID:', videoId);
        
        // Create video container
        var videoContainer = $('<div class="video-background"></div>');
        
        // Create iframe with desktop parameters
        var iframe = $('<iframe>', {
            src: 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&mute=1&loop=1&playlist=' + videoId + '&controls=0&showinfo=0&rel=0&modestbranding=1&enablejsapi=1&playsinline=1&origin=' + window.location.origin,
            frameborder: '0',
            allowfullscreen: true,
            allow: 'autoplay; encrypted-media; picture-in-picture; fullscreen',
            style: 'position: fixed; top: 50%; left: 50%; min-width: 110%; min-height: 110%; width: auto; height: auto; z-index: -1000; transform: translateX(-50%) translateY(-50%) scale(1.1); pointer-events: none;'
        });
        
        // Add to container and body
        videoContainer.append(iframe);
        $('body').prepend(videoContainer);
        
        console.log('Desktop video iframe created and added to body');
        
        // Add overlay
        if (!$('.video-overlay').length) {
            var overlay = $('<div class="video-overlay"></div>');
            $('body').prepend(overlay);
            console.log('Video overlay added');
        }
        
        // Hide loaders
        $('#preload').fadeOut(500);
        $('.screen-loader').fadeOut(500);
        $('.pace').fadeOut(500);
        
        console.log('Desktop video creation complete');
    }
    
    // Main video creation function
    function createVideo() {
        var isMobile = isRealMobileDevice();
        console.log('Device type:', isMobile ? 'Mobile' : 'Desktop');
        
        if (isMobile) {
            createMobileVideo();
        } else {
            createDesktopVideo();
        }
    }
    
    // Create video immediately
    console.log('Creating video immediately...');
    createVideo();
    
    // For real mobile devices, create video after user interaction
    if (isRealMobileDevice()) {
        console.log('Real mobile device detected, setting up interaction handlers...');
        
        // Create video on any user interaction
        $(document).on('touchstart touchmove touchend click scroll', function() {
            console.log('Mobile interaction detected, ensuring video exists...');
            setTimeout(function() {
                if (!$('.video-background').length) {
                    console.log('No video found after interaction, creating mobile video...');
                    createMobileVideo();
                }
            }, 100);
        });
        
        // Also try to create video after a delay
        setTimeout(function() {
            if (!$('.video-background').length) {
                console.log('Mobile fallback triggered after delay...');
                createMobileVideo();
            }
        }, 2000);
        
        // Additional mobile-specific fallback
        setTimeout(function() {
            if (!$('.video-background').length) {
                console.log('Final mobile fallback triggered...');
                createMobileVideo();
            }
        }, 5000);
    }
    
    // Also create video after page load
    $(window).on('load', function() {
        console.log('Page loaded, ensuring video exists...');
        setTimeout(function() {
            if (!$('.video-background').length) {
                console.log('No video found after page load, creating video...');
                createVideo();
            }
        }, 1000);
    });
    
    // Fallback for all devices
    setTimeout(function() {
        if (!$('.video-background').length) {
            console.log('General fallback triggered, creating video...');
            createVideo();
        }
    }, 3000);
    
    // Force hide all loaders
    setTimeout(function() {
        $('#preload').fadeOut(300);
        $('.screen-loader').fadeOut(300);
        $('.pace').fadeOut(300);
        $('body').addClass('page-loaded');
    }, 5000);
}); 