$(document).ready(function () {
  $('#activite').on("change", function(e) {
    var heureRequired = ["Yoga et Yoga Senior", "Aquagym", "Gym Douce", "Arts plastiques", "Batterie et percussion", "Guitare et basse", "Piano", "Saxophone"];
    $("#divHeure").empty();
    console.log(heureRequired);
    console.log(e.target.value);
    console.log(heureRequired.indexOf(e.target.value));
    if (heureRequired.indexOf(e.target.value) != -1) {
      $("#divHeure").append('<div class="col12"><div class="inputWrapper"><label for="heure_desiree">Heure désirée</label><input type="text" name="heure_desiree" id="heure_desiree" placeholder="12:30" /></div><p>Pour savoir les heures disponible, rendez-vous dans la rubrique de l\'activité</p></div>');
    }
  });

  $("#formInscription").submit(function (e) {
    e.preventDefault();

    var form = $(this);
    var url = form.attr("action");

    $.ajax({
      type: "POST",
      url: url,
      data: form.serialize(), // serializes the form's elements.
      success: function (data) {
        $('.msg-error').remove();
        if (data.Error) {
          $.each(data.Error, function (index, value) {
            $('*[name="' + index + '"]')
              .parent()
              .append("<strong class=\"msg-error\" style=\"color:red\">" + value + "</strong>");
          });
        } else if (data === "Success") {
          form[0].reset();
          alert("Vos données ont été enregistrées. Nous vous contacterons.")
        }
      },
    });
  });
});
