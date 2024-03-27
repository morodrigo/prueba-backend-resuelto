<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba técnica para Backend o Fullstack</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <article id="message" class="message is-success is-hidden">
        <div class="message-body"></div>
    </article>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="#">
                <img src="https://bulma.io/assets/images/placeholders/256x256.png" width="112" height="28">
            </a>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/users">
                    Usuarios
                </a>
                <a class="navbar-item" href="/comments">
                    Comentarios
                </a>
                <a class="navbar-item" href="#" >
                    Bienvenido: <b id="userloggeddata"></b>
                </a>
                <a class="navbar-item" href="#" onclick="logout()">
                    Cerrar sesión
                </a>
            </div>
        </div>
    </nav>
    <div class="content">
        <?php include($content); ?>
    </div>
    <div id="viwModal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title" id="viwModaltitle">Detalles del comentario</p>
                <button class="delete" aria-label="close" onclick="closeModal()"></button>
            </header>
            <section class="modal-card-body">
                <div id="viewData">
                </div>
            </section>
        </div>
    </div>
    <script src="assets/js/layout.js"></script>
</body>
</html>