function spinButton(buttonID, cond) {
  var button = $(buttonID); // Use jQuery selector to select the button element
  var btnSpinner = button.find('.spinner-border'); // Find the spinner element within the button
  var btnText = button.find('.button-text'); // Find the button text element within the button

  if (cond) {
    btnText.addClass('d-none'); // Hide the button text
    btnSpinner.removeClass('d-none'); // Show the spinner
    button.prop('disabled', true); // Disable the button
  } else {
    btnText.removeClass('d-none'); // Show the button text
    btnSpinner.addClass('d-none'); // Hide the spinner
    button.prop('disabled', false); // Enable the button
  }
}