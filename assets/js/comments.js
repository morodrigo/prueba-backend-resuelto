var commentId=0;
document.addEventListener("DOMContentLoaded", () => {
  Comments();
  users();

   var openModalButton = document.getElementById("openModalButton");
   var userModal = document.getElementById("commentModal");
   var cancelButton = document.getElementById("cancelButton");

   openModalButton.addEventListener("click", function () {
     userId = 0;
     const modalTitle = document.getElementById("modalTitle");
     const saveUserButton = document.getElementById("addComment");
     modalTitle.textContent = "Agregar Usuario";
     saveUserButton.textContent = "Crear Usuario";

     userModal.classList.add("is-active");
   });

   cancelButton.addEventListener("click", function () {
     userModal.classList.remove("is-active");
   });


});



function editComment(commentdata) {
  var data = JSON.parse(b64_to_utf8(commentdata));
 const userModal = document.getElementById("commentModal");
 const modalTitle = document.getElementById("modalTitle");
 const saveUserButton = document.getElementById("addComment");
  modalTitle.textContent = "Editar Comentario";
  saveUserButton.textContent = "Guardar";
  commentId = data.id;

  userModal.classList.add("is-active");
  document.querySelector(
    "#userSelect option[value='" + data.user + "']"
  ).selected = true;
  document.getElementById("commentText").value = data.coment_text;
  document.getElementById("inputLikes").value = data.likes;


}
function Comments() {
  fetch("/api/comments")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al obtener los datos de los comentarios.");
      }
      return response.json();
    })
    .then((data) => {
      fillComments(data);
    })
    .catch((error) => {
      showMessage("Error:" + error, "error");
    });
}
function fillComments(data) {
  console.log(data);
  document.getElementById("commentscontainer").innerHTML = "";
  data.forEach((comment) => {
    var dataedit = JSON.stringify({
      id: comment.id,
      coment_text: comment.coment_text,
      user: comment.user,
      likes: comment.likes,
    });
    const card = document.createElement("div");
    card.classList.add("card");
    card.innerHTML = `<div class="card">
    <div class="card-content">
        <div class="media">
            <div class="media-content">
                <p class="title is-4">${comment.fullname}</p>
                <p class="subtitle is-6">${comment.email}</p>
            </div>
            <div class="level-right">
                <a class="level-item" onclick="viewComment(\'${comment.id}\')">
                    <span class="icon is-small mr-2"><i class="fa-solid fa-eye"></i></span>
                </a>
                <a class="level-item" onclick="editComment(\'${utf8_to_b64(
                  dataedit
                )}\')">
                    <span class="icon is-small mr-2"><i class="fas fa-edit"></i></span>
                </a>
                <a class="level-item" onclick="confirmdeletecomment(${
                  comment.id
                })">
                    <span class="icon is-small"><i class="fas fa-trash has-text-danger"></i></span>
                </a>
            </div>
        </div>
        <div class="content">
            ${comment.coment_text}
        </div>
        <nav class="level is-mobile">
            <div class="level-left">
                <a class="level-item" onclick="updateLike(${comment.id})">
                    <span class="icon is-small mr-2"><i class="fas fa-thumbs-up"></i></span>
                    <span>${comment.likes}</span>
                </a>
                <p class="level-item is-size-7">${comment.creation_date}</p>
            </div>
        </nav>
    </div>
</div>
                `;
    document.getElementById("commentscontainer").appendChild(card);
  });
}
function updateLike(id) {
  var commentData = {
    like: 1,
    id: id,
  };
  fetch("/api/like", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(commentData),
  })
    .then((response) => {
      return response.json().then((data) => {
        if (response.ok) {
          showMessage(data.mensaje, "success");

          Comments();
        } else {
          let errorMessage =
            data.message ||
            "Error al dar like al comentario.Por favor, inténtalo de nuevo más tarde.";
          showMessage(errorMessage, "error");
        }
      });
    })
    .catch((error) => {
      showMessage(
        "Error de red. Por favor, verifica tu conexión a internet.",
        "error"
      );
    });
}
function viewComment(id) {
const modal = document.getElementById("viwModal");
const userDataContainer = document.getElementById("viewData");

document.getElementById("viwModaltitle").textContent = "Ver Comentario";
const saveUserButton = document.getElementById("addComment");


fetch(`/api/comment/${id}`, {
  method: "GET",
})
  .then((response) => {
    return response.json().then((data) => {
      if (response.ok) {
        const userDataHtml = `
            <p><strong>ID:</strong> ${data.id}</p>
            <p><strong>Id usuario:</strong> ${data.user}</p>
            <p><strong>Comentario:</strong> ${data.coment_text}</p>
            <p><strong>Likes:</strong> ${data.likes}</p>
            <p><strong>Fecha de creación:</strong> ${data.creation_date}</p>
            <p><strong>Fecha de actualización:</strong> ${data.update_date}</p>
          `;
        // Mostrar los datos del usuario en el contenedor
        userDataContainer.innerHTML = userDataHtml;
        // Mostrar la modal
        modal.classList.add("is-active");
      } else {
        let errorMessage =
          data.mensaje ||
          "Error al obtener el comentario. Por favor, inténtalo de nuevo más tarde.";
        showMessage(errorMessage, "error");
      }
    });
  })
  .catch((error) => {
    showMessage(
      "Error de red. Por favor, verifica tu conexión a internet.",
      "error"
    );
  });
}
function closeModal() {
  const modal = document.getElementById("viwModal");
  modal.classList.remove("is-active");
}
function addComment() {
  var commentText = document.getElementById("commentText").value;
  var userSelect = document.getElementById("userSelect").value;
  var inputLikes = document.getElementById("inputLikes").value;
  const addCommentButton = document.getElementById("addComment");
  if (!userSelect) {
    showMessage("Favor de completar el campo usuario", "error");
    document.getElementById("userSelect").focus();
    return false;
  }
  if (!commentText) {
    showMessage("Favor de completar el campo comentario", "error");
    document.getElementById("commentText").focus();
    return false;
  }
  if (!inputLikes) {
    showMessage("Favor de completar el campo likes", "error");
    document.getElementById("inputLikes").focus();
    return false;
  }
  addCommentButton.disabled = true;
  addCommentButton.classList.add("is-loading");

  var commentData = {
    user: userSelect,
    coment_text: commentText,
    likes: inputLikes,
  };
  var method = "POST";

  if (commentId > 0) {
    commentData.id = commentId;
    method = "PUT";
  }

  fetch("/api/comment", {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(commentData),
  })
    .then((response) => {
      return response.json().then((data) => {
        if (response.ok) {
          showMessage(data.mensaje, "success");
          document.getElementById("commentModal").classList.remove("is-active");
          document.getElementById("commentText").value = "";
          document.getElementById("userSelect").value = "";
          document.getElementById("inputLikes").value = "";
          Comments();
        } else {
          let errorMessage =
            data.message ||
            "Error al guardar el comentario.Por favor, inténtalo de nuevo más tarde.";
          showMessage(errorMessage, "error");
        }
      });
    })
    .catch((error) => {
      showMessage(
        "Error de red. Por favor, verifica tu conexión a internet.",
        "error"
      );
    });
  addCommentButton.disabled = false;
  addCommentButton.classList.remove("is-loading");
}

function users() {
  fetch("/api/users")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al obtener los datos de los usuarios.");
      }
      return response.json();
    })
    .then((data) => {
      fillSelectWithData(data);
    })
    .catch((error) => {
      showMessage("Error:" + error, "error");
    });
}
function fillSelectWithData(users) {
  const userSelect = document.getElementById("userSelect");
  userSelect.innerHTML = "";
  users.forEach((user) => {
    const option = document.createElement("option");
    option.value = user.id;
    option.textContent = user.fullname + " - " + user.email;
    userSelect.appendChild(option);
  });
}

var deleteModal = document.getElementById("deleteModal");
var confirmDeleteButton = document.getElementById("confirmDeleteButton");
var cancelDeleteButton = document.getElementById("cancelDeleteButton");

function showDeleteModal() {
  deleteModal.classList.add("is-active");
}

function hideDeleteModal() {
  deleteModal.classList.remove("is-active");
}

var deleteButtons = document.querySelectorAll(".deletecomment");
deleteButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    showDeleteModal();
  });
});

cancelDeleteButton.addEventListener("click", function () {
  hideDeleteModal();
});

confirmDeleteButton.addEventListener("click", function () {
  hideDeleteModal();
});
function confirmdeletecomment(commentId) {
  showDeleteModal();
  function deleteHandler() {
    fetch(`/api/comment/${commentId}`, {
      method: "DELETE",
    })
      .then((response) => {
        return response.json().then((data) => {
          if (response.ok) {
            showMessage(data.mensaje, "success");
            document
              .getElementById("deleteModal")
              .classList.remove("is-active");
            Comments();
          } else {
            let errorMessage =
              data.mensaje ||
              "Error al eliminar el comentario. Por favor, inténtalo de nuevo más tarde.";
            showMessage(errorMessage, "error");
          }
        });
      })
      .catch((error) => {
        showMessage(
          "Error de red. Por favor, verifica tu conexión a internet.",
          "error"
        );
      });

    hideDeleteModal();

    confirmDeleteButton.removeEventListener("click", deleteHandler);
  }

  confirmDeleteButton.addEventListener("click", deleteHandler);
}
