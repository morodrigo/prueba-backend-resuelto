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
    <section id="login-view" class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="box">
                        <h1 class="title has-text-centered">Inicia sesión</h1>
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input id="inputEmail" class=" input" type="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Contraseña</label>
                            <div class="control">
                                <input id="inputPassword" class="input" type="password" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button id="login" class="button is-primary is-fullwidth">ENTRAR</button>
                            </div>
                        </div>
                        <hr>
                        <div class="field">
                            <p class="has-text-centered">o</p>
                        </div>
                        <div class="field">
                            <div class="control">
                                <a href="#" onclick="signInWithGoogle()" class="button is-fullwidth">
                                    <i class="fa-brands fa-google"></i>
                                    Entrar con Google</a>
                            </div>
                        </div>
                        <hr>
                        <div class="field">
                            <p class="has-text-centered">No tienes una cuenta? <a id="register-link" href="#">Registrate</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="register-view" class="section is-hidden">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="box">
                        <h1 class="title has-text-centered">Registro</h1>
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input id="registerName" class="input" type="text" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input id="registerEmail" class="input" type="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Contraseña</label>
                            <div class="control">
                                <input id="registerPassword" class="input" type="password" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button id="register" class="button is-primary is-fullwidth">REGISTRAR</button>
                            </div>
                        </div>
                        <p class="has-text-centered">Ya tienes una cuenta? <a id="login-link" href="#">Inicia sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/js/register.js"></script>
</body>
</html>