// JavaScript Document

// contact form
$(document).ready(function() {
    $("form#form").submit(function(e) {
		e.preventDefault();
		

        

        $("form#form .error").remove();
        var hasError = false;

        $(".requiredField").each(function() {
            if ($.trim($(this).val()) == "") {
                var labelText = $(this).prev("label").text();
                $(this).parent().append(
                    '<span class="error">You forgot to enter your ' + labelText + "</span>"
                );
                $(this).addClass("inputError");
                hasError = true;
            } 
            else if ($(this).hasClass("email")) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (!emailReg.test($.trim($(this).val()))) {
                    var labelText = $(this).prev("label").text();
                    $(this).parent().append(
                        '<span class="error">You entered an invalid ' + labelText + "</span>"
                    );
                    $(this).addClass("inputError");
                    hasError = true;
                }
            }
        });

        // OPTIONAL: file validation
        var fileInput = $('input[type="file"]')[0];
        if (fileInput && fileInput.files.length === 0) {
            alert("Please select an image");
            hasError = true;
        }

        if (!hasError) {

            var $form = $(this);

            $("form#form input.submit").fadeOut("normal");

            // ✅ Use FormData instead of serialize
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: $form.attr("action"),
                data: formData,
                processData: false,   // required for file upload
                contentType: false,   // required for file upload

                success: function(response) {
                    showSuccessPopup("Thank you! Your message has been sent successfully.");
                    $form[0].reset();
                    $('input.submit').hide();
                },

                error: function(err) {
                    showErrorPopup("Oops! Something went wrong.");
                },

                complete: function() {
                    $form.find("input.submit").fadeIn("normal"); 
                }
            });
        }

        return false;
    });
});

// Function to show popup success message
function showSuccessPopup(message) {
	// Remove existing popup if any
	$(".success-popup").remove();

	// Create popup
	var popup = $(
		'<div class="success-popup">' +
			'<div class="success-popup-content">' +
			'<div class="success-popup-icon">✓</div>' +
			'<div class="success-popup-message">' +
			message +
			"</div>" +
			'<button class="success-popup-close">OK</button>' +
			"</div>" +
			"</div>"
	);

	// Add to body
	$("body").append(popup);

	// Show popup with animation
	popup.fadeIn(300);

	// Close popup on button click or after 5 seconds
	popup.find(".success-popup-close").click(function() {
		popup.fadeOut(300, function() {
			popup.remove();
		});
	});

	// Auto close after 5 seconds
	setTimeout(function() {
		if (popup.is(":visible")) {
			popup.fadeOut(300, function() {
				popup.remove();
			});
		}
	}, 5000);
}
