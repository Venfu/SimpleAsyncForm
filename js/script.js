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
        if (data.Error) {
          $.each(data.Error, function (index, value) {
            $('*[name="' + index + '"]')
              .parent()
              .append("<strong style=\"color:red\">" + value + "</strong>");
          });
        } else if (data === "Success") {
          form[0].reset();
          alert("Vos données ont été enregistrées. Nous vous contacterons.")
        }
      },
    });
  });
});
