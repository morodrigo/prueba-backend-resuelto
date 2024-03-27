var userId = 0;
document.addEventListener("DOMContentLoaded", () => {
  var openModalButton = document.getElementById("openModalButton");
  var userModal = document.getElementById("userModal");
  var cancelButton = document.getElementById("cancelButton");

  openModalButton.addEventListener("click", function () {
    userId = 0;
    const modalTitle = document.getElementById("modalTitle");
    const saveUserButton = document.getElementById("createUserButton");
    modalTitle.textContent = "Agregar Usuario";
    saveUserButton.textContent = "Crear Usuario";
    userModal.classList.add("is-active");
  });

  cancelButton.addEventListener("click", function () {
    userModal.classList.remove("is-active");
  });

  var createUserButton = document.getElementById("createUserButton");
  //var userForm = document.getElementById("userForm");

  createUserButton.addEventListener("click", function () {
    var fullname = document.getElementById("inputName").value;
    var email = document.getElementById("inputEmail").value;
    var pass = document.getElementById("inputPassword").value;
    var openid = "generate";

    if (!fullname) {
      showMessage("Favor de completar el campo nombre completo", "error");
      document.getElementById("inputName").focus();
      return false;
    }
    if (!email) {
      showMessage("Favor de completar el campo email", "error");
      document.getElementById("inputEmail").focus();
      return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showMessage("El campo de email no es valido", "error");
      document.getElementById("inputEmail").focus();
      return false;
    }
    if (!pass) {
      showMessage("Favor de completar el campo contraseña", "error");
      document.getElementById("inputPassword").focus();
      return false;
    }

    var userData = {
      fullname: fullname,
      email: email,
      pass: pass,
      openid: openid,
    };
    var method = "POST";

    if (userId > 0) {
      userData.id = userId;
      method = "PUT";
      delete userData.openid;
    }

    fetch("/api/user", {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(userData),
    })
      .then((response) => {
        return response.json().then((data) => {
          if (response.ok) {
            console.log(data.mensaje);
            showMessage(data.mensaje, "success");
            document.getElementById("userModal").classList.remove("is-active");

            document.getElementById("inputName").value = "";
            document.getElementById("inputEmail").value = "";
            document.getElementById("inputPassword").value = "";

            usersTable();
          } else {
            let errorMessage =
              data.mensaje ||
              "Error al crear el usuario. Por favor, inténtalo de nuevo más tarde.";
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
  });

  usersTable();
});

function usersTable() {
  fetch("/api/users")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al obtener los datos de los usuarios.");
      }
      return response.json();
    })
    .then((data) => {
      fillTableWithData(data);
    })
    .catch((error) => {
      showMessage("Error:" + error, "error");
    });
}

function fillTableWithData(data) {
  const tableBody = document
    .getElementById("tableusers")
    .getElementsByTagName("tbody")[0];

  tableBody.innerHTML = "";

  data.forEach((user) => {
    const row = document.createElement("tr");

    const ActionsCell = document.createElement("td");
    var dataedit = JSON.stringify({
      id: user.id,
      fullname: user.fullname,
      email: user.email,
    });

    ActionsCell.innerHTML = `<a href="#" class="mr-3 " ><i onclick="viewUser(\'${
      user.id
    }\')" class="viewuser fa-solid fa-eye has-text-primary"></i></a><a href="#" class="mr-3 " ><i onclick="editUser(\'${utf8_to_b64(
      dataedit
    )}\')" class="edituser fa-solid fa-pen-to-square has-text-info"></i></a><a class="mr-3 "  href="#"><i onclick="confirmDeleteUser(${
      user.id
    })" class="deleteuser  fa-solid fa-trash-can has-text-danger"></i></a>`;

    const idCell = document.createElement("td");
    idCell.textContent = user.id;
    const fullnameCell = document.createElement("td");
    fullnameCell.textContent = user.fullname;
    const emailCell = document.createElement("td");
    emailCell.textContent = user.email;
    const openidCell = document.createElement("td");
    openidCell.textContent = user.openid;
    const creation_dateCell = document.createElement("td");
    creation_dateCell.textContent = user.creation_date;
    const update_datedateCell = document.createElement("td");
    update_datedateCell.textContent = user.update_date;
    row.appendChild(ActionsCell);
    row.appendChild(idCell);
    row.appendChild(fullnameCell);
    row.appendChild(emailCell);
    row.appendChild(openidCell);
    row.appendChild(creation_dateCell);
    row.appendChild(update_datedateCell);

    tableBody.appendChild(row);
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

var deleteButtons = document.querySelectorAll(".deleteuser");
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
function confirmDeleteUser(userId) {
  showDeleteModal();
  function deleteHandler() {
    fetch(`/api/user/${userId}`, {
      method: "DELETE",
    })
      .then((response) => {
        return response.json().then((data) => {
          if (response.ok) {
            //console.log(data.mensaje);
            showMessage(data.mensaje, "success");
            document.getElementById("userModal").classList.remove("is-active");
            usersTable();
          } else {
            let errorMessage =
              data.mensaje ||
              "Error al eliminar el usuario. Por favor, inténtalo de nuevo más tarde.";
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

function viewUser(id) {
  const modal = document.getElementById("viwModal");
  const userDataContainer = document.getElementById("viewData");
  document.getElementById("viwModaltitle").textContent = "Ver Usuario";

  fetch(`/api/user/${id}`, {
    method: "GET",
  })
    .then((response) => {
      return response.json().then((data) => {
        if (response.ok) {
          const userDataHtml = `
            <p><strong>ID:</strong> ${data.id}</p>
            <p><strong>Nombre:</strong> ${data.fullname}</p>
            <p><strong>Email:</strong> ${data.email}</p>
            <p><strong>Contraseña:</strong> ${data.pass}</p>
            <p><strong>Openid:</strong> ${data.openid}</p>
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
            "Error al obtener el usuario. Por favor, inténtalo de nuevo más tarde.";
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

function editUser(userData) {
  var data = JSON.parse(b64_to_utf8(userData));
  const userModal = document.getElementById("userModal");
  const modalTitle = document.getElementById("modalTitle");
  const saveUserButton = document.getElementById("createUserButton");
  modalTitle.textContent = "Editar Usuario";
  saveUserButton.textContent = "Guardar";
  userId = data.id;
  document.getElementById("inputName").value = data.fullname;
  document.getElementById("inputEmail").value = data.email;
  userModal.classList.add("is-active");
}
