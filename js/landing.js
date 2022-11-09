var openModalLinks = document.getElementsByClassName("open-modal");
var closeModalLinks = document.getElementsByClassName("form-modal-close");
var overlay = document.getElementsByClassName("overlay")[0];

for (var i = 0; i < openModalLinks.length; i++) {
  var modalLink = openModalLinks[i];

  modalLink.addEventListener("click", function (event) {
    var modalId = event.currentTarget.getAttribute("data-for");

    var modal = document.getElementById(modalId);
    modal.setAttribute("style", "display: block");
    overlay.setAttribute("style", "display: block");

  });
}

function closeModal(event) {
  var modal = event.currentTarget.parentElement;

  modal.removeAttribute("style");
  overlay.removeAttribute("style");
}

for (var j = 0; j < closeModalLinks.length; j++) {
  var closeModalLink = closeModalLinks[j];

  closeModalLink.addEventListener("click", closeModal)
}