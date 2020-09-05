$(document).ready(function () {
  $("#activite").on("change", function (e) {
    var heureRequired = ["Yoga et Yoga Senior", "Aquagym", "Gym Douce", "Arts plastiques", "Batterie et percussion", "Guitare et basse", "Piano", "Saxophone"];
    var certifRequired = ["Aïkido", "Aïki Taïso", "Kenjutsu", "Karaté", "Body Karaté", "Self défense", "Taichi", "Yoga et Yoga Senior", "Gym Douce", "Body strech", "Aquagym", "Marche Nordique"];
    $("#divSeace").empty();
    if (heureRequired.indexOf(e.target.value) != -1) {
      $("#divSeace").append(
        '<div class="col12"><div class="inputWrapper"><label for="seance_desiree">Heure désirée</label><input type="text" name="seance_desiree" id="seance_desiree" placeholder="Ex : lundi 12:30" /></div><p style="color: #FF8C00">Pour savoir les heures disponible, rendez-vous dans la rubrique de l\'activité.</p></div>'
      );
    }
    if (certifRequired.indexOf(e.target.value) != -1) {
      $("#divSeace").append('<div class="col12"><p style="color: #FF8C00">Un certificat médical vous sera demandé lors du paiement.</p></div>');
    }
  });

  $("#date_naissance").bind("keyup", "keydown", function (event) {
    var inputLength = event.target.value.length;
    if (inputLength === 2 || inputLength === 5) {
      var thisVal = event.target.value;
      thisVal += "/";
      $(event.target).val(thisVal);
    }
  });

  // Restricts input for the given textbox to the given inputFilter function.
  function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
      textbox.addEventListener(event, function () {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    });
  }

  setInputFilter(document.getElementById("date_naissance"), function(value) {
    return /^\d{0,2}\/?\d{0,2}\/?\d{0,4}$/.test(value);
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
        $(".msg-error").remove();
        if (data.Error) {
          $.each(data.Error, function (index, value) {
            $('*[name="' + index + '"]')
              .parent()
              .append('<strong class="msg-error" style="color:red">' + value + "</strong>");
          });
        } else if (data === "Success") {
          form[0].reset();
          alert("Votre pré-inscription est terminée. Surveillez votre boîte mail pour la finaliser. (Verifiez vos spams)");
        }
      },
    });
  });
});
