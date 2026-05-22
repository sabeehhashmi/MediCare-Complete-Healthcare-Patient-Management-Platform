// JavaScript Document

// newsletter form
// $(document).ready(function() {
// 	$('form#subscribe').submit(function() {
// 	$('form#subscribe .subscribeerror').remove();
// 	var hasError = false;
// 	$('.subscriberequiredField').each(function() {
// 	if(jQuery.trim($(this).val()) == '') {
//     var labelText = $(this).prev('label').text();
//     $(this).parent().append('<span class="subscribeerror">Please enter your'+labelText+'</span>');
//     $(this).addClass('inputError');
//     hasError = true;
//     } else if($(this).hasClass('subscribeemail')) {
//     var subscribeemailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
//     if(!subscribeemailReg.test(jQuery.trim($(this).val()))) {
//     var labelText = $(this).prev('label').text();
//     $(this).parent().append('<span class="subscribeerror">Please enter a valid'+labelText+'</span>');
//     $(this).addClass('inputError');
//     hasError = true;
//     }
//     }
//     });
//     if(!hasError) {
//     // Clear input field immediately
//     $('#subscribeemail').val('');
    
//     $('form#subscribe input.submit').fadeOut('normal', function() {
//     $(this).parent().append('');
//     });
//     var formInput = $(this).serialize();
//     $.post($(this).attr('action'),formInput, function(data){
//     // Show popup success message
//     showSuccessPopup('Thank you for subscribing! You have been successfully added to our newsletter.');
//     }).fail(function(xhr, status, error) {
//     // Handle Formspree errors - still show success message
//     showSuccessPopup('Thank you for subscribing! You have been successfully added to our newsletter.');
//     });
//     }
//     return false;
//     });
// });

// Function to show popup success message
function showSuccessPopup(message) {
    // Remove existing popup if any
    $('.success-popup').remove();
    
    // Create popup
    var popup = $('<div class="success-popup">' +
        '<div class="success-popup-content">' +
        '<div class="success-popup-icon">✓</div>' +
        '<div class="success-popup-message">' + message + '</div>' +
        '<button class="success-popup-close">OK</button>' +
        '</div>' +
        '</div>');
    
    // Add to body
    $('body').append(popup);
    
    // Show popup with animation
    popup.fadeIn(300);
    
    // Close popup on button click or after 5 seconds
    popup.find('.success-popup-close').click(function() {
        popup.fadeOut(300, function() {
            popup.remove();
        });
    });
    
    // Auto close after 5 seconds
    setTimeout(function() {
        if (popup.is(':visible')) {
            popup.fadeOut(300, function() {
                popup.remove();
            });
        }
    }, 5000);
}