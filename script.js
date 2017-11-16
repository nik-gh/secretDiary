$('.toggleForm').click(() => {
  $('#signupForm').toggle();
  $('#loginForm').toggle();
});

$('#diary').on('input propertychange', () => {
  $.ajax({
    method: 'POST',
    url: 'updateDiary.php',
    data: { content: $('#diary').val() },
  });
});
