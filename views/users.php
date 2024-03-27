<section class="section">
    <div class="container">
        <button id="openModalButton" class="button is-primary">
            <span class="icon">
                <i class="fa-solid fa-square-plus"></i>
            </span>
            <span>Agregar Usuario</span>
        </button>
        <h2 class="title">Tabla de Usuarios</h2>
        <div class="table-container">
            <table class="table is-bordered is-fullwidth is-striped is-hoverable " id="tableusers">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Open id</th>
                        <th>Fecha de Creación</th>
                        <th>Fecha de Actualización</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div id="userModal" class="modal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <h2 class="title" id='modalTitle'>Agregar Usuario</h2>
                    <div class="field">
                        <label class="label">Nombre Completo</label>
                        <div class="control has-icons-left">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                            <input id="inputName" class="input" type="text" placeholder="Nombre Completo">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="inputEmail" class="input" type="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Contraseña</label>
                        <div class="control has-icons-left">
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="inputPassword" class="input" type="password" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button id="createUserButton" class="button is-primary">Crear Usuario</button>
                        </div>
                        <div class="control">
                            <button id="cancelButton" class="button">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
        </div>
        <div id="deleteModal" class="modal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                    <button id="confirmDeleteButton" class="button is-danger">Confirmar</button>
                    <button id="cancelDeleteButton" class="button">Cancelar</button>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
        </div>
    </div>
    <div id="viwuserModal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Detalles del usuario</p>
                <button class="delete" aria-label="close" onclick="closeModal()"></button>
            </header>
            <section class="modal-card-body">
                <div id="userData">
                </div>
            </section>
        </div>
    </div>
</section>

<script src="assets/js/users.js"></script>