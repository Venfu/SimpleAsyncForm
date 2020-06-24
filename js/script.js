$(document).ready(function () {
  $("#formInscription").submit(function (e) {
    e.preventDefault();

    var form = $(this);
    var url = form.attr("action");

    $.ajax({
      type: "POST",
      url: url,
      data: form.serialize(), // serializes the form's elements.
      success: function (data) {
        // form[0].reset();
      },
    });
  });
});
