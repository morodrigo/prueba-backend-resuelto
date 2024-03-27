document.addEventListener("DOMContentLoaded", () => {
  const $navbarBurgers = Array.prototype.slice.call(
    document.querySelectorAll(".navbar-burger"),
    0
  );
  if ($navbarBurgers.length > 0) {
    $navbarBurgers.forEach((el) => {
      el.addEventListener("click", () => {
        const target = el.dataset.target;
        const $target = document.getElementById(target);
        el.classList.toggle("is-active");
        $target.classList.toggle("is-active");
      });
    });
  }

  const urlopenid = new URL(window.location.href);
  const openid = urlopenid.searchParams.get("openid");
  if(openid){
    var dataTemp = {
      fullname: urlopenid.searchParams.get("fullname"),
      id: urlopenid.searchParams.get("id"),
    };
    localStorage.setItem("userData", JSON.stringify(dataTemp));
  }

  const userDataString = localStorage.getItem("userData");
  if (!userDataString) {
    window.location.href = "/";
    return false;
  }
  const userData = JSON.parse(userDataString);
  document.getElementById("userloggeddata").textContent = userData.fullname;
});

function logout() {
  localStorage.removeItem("userData");
  window.location.href = "/";
}
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
function utf8_to_b64(str) {
  return window.btoa(
    encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function (match, p1) {
      return String.fromCharCode(parseInt(p1, 16));
    })
  );
}

function b64_to_utf8(str) {
  return decodeURIComponent(
    Array.prototype.map
      .call(window.atob(str), function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );
}