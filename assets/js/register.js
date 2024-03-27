
document.addEventListener("DOMContentLoaded", () => {
  const loginView = document.getElementById("login-view");
  const registerView = document.getElementById("register-view");
  const registerLink = document.getElementById("register-link");
  const loginLink = document.getElementById("login-link");

  registerLink.addEventListener("click", () => {
    loginView.classList.add("is-hidden");
    registerView.classList.remove("is-hidden");
  });
  loginLink.addEventListener("click", () => {
    registerView.classList.add("is-hidden");
    loginView.classList.remove("is-hidden");
  });

  document.getElementById("login").addEventListener("click", () => {
    getUser();
    return false;
  });
  document.getElementById("register").addEventListener("click", () => {
    registerUser();
    return false;
  });
  checkAuthentication();
});
function showMessage(message, type) {
  const messageElement = document.getElementById("message");
  messageElement.style.display = "block";
  messageElement.classList.remove("is-hidden");
  messageElement.classList.remove("is-danger", "is-success");
  messageElement.classList.add(type === "success" ? "is-success" : "is-danger");
  messageElement.querySelector(".message-body").innerHTML = message;

  setTimeout(() => {
    messageElement.style.display = "none";
  }, 4000);
}

function hideMessage() {
  const messageElement = document.getElementById("message");
  messageElement.classList.add("is-hidden");
}

function checkAuthentication() {
  const userData = localStorage.getItem("userData");
  if (userData) {
    window.location.href = "/users";
  }
}

function signInWithGoogle() {
  window.location.href =
    "https://accounts.google.com/o/oauth2/v2/auth?scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&access_type=offline&include_granted_scopes=true&response_type=code&state=state_parameter_passthrough_value&redirect_uri=http://localhost:8080/callback&client_id=454381194323-5mbbtpk6ui2slk3fgbfrdob2od4hcvvd.apps.googleusercontent.com";
}

function registerUser() {
  var fullname = document.getElementById("registerName").value;
  var email = document.getElementById("registerEmail").value;
  var pass = document.getElementById("registerPassword").value;
  var openid = "generate";

  if (!fullname) {
    showMessage("Favor de completar el campo nombre completo", "error");
    document.getElementById("registerName").focus();
    return false;
  }
  if (!email) {
    showMessage("Favor de completar el campo email", "error");
    document.getElementById("registerEmail").focus();
    return false;
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    showMessage("El campo de email no es valido", "error");
    document.getElementById("registerEmail").focus();
    return false;
  }
  if (!pass) {
    showMessage("Favor de completar el campo contraseña", "error");
    document.getElementById("registerPassword").focus();
    return false;
  }

  var userData = {
    fullname: fullname,
    email: email,
    pass: pass,
    openid: openid,
  };

  fetch("/api/user", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(userData),
  })
    .then((response) => {
      return response.json().then((data) => {
        if (response.ok) {
          console.log(data.mensaje);
          //showMessage(data.mensaje, "success");
          alert(data.mensaje);
          localStorage.setItem("userData", JSON.stringify(data));
          window.location.href = "/users";
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
}
function getUser() {
  var email = document.getElementById("inputEmail").value;
  var pass = document.getElementById("inputPassword").value;

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
    email: email,
    pass: pass,
  };

  fetch("/api/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(userData),
  })
    .then((response) => {
      return response.json().then((data) => {
        if (response.ok) {
          localStorage.setItem("userData", JSON.stringify(data));
          window.location.href = "/users";
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
