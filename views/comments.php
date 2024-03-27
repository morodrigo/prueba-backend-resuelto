<section class="section">
    <div class="container">
        <button id="openModalButton" class="button is-primary">
            <span class="icon">
                <i class="fa-solid fa-square-plus"></i>
            </span>
            <span>Agregar Comentario</span>
        </button>
        <h2 class="subtitle">Lista de comentarios</h2>
        <div id="commentscontainer"></div>
    </div>
</section>
<div id="commentModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box">
            <h2 class="title" id='modalTitle'>Agregar Comentario</h2>
            <div class="field">
                <label class="label">Seleccionar usuario</label>
                <p class="control has-icons-left">
                    <span class="select is-fullwidth">
                        <select id="userSelect">
                        </select>
                    </span>
                    <span class="icon is-small is-left">
                        <i class="fas fa-user"></i>
                    </span>
                </p>
            </div>
            <div class="field">
                <label class="label">Agregar comentario</label>
                <div class="control">
                    <textarea class="textarea" id="commentText" placeholder="Escribe tu comentario aquí"></textarea>
                </div>
            </div>
            <div class="field">
                <label class="label">Likes</label>
                <div class="control has-icons-left">
                    <span class="icon is-small is-left">
                        <i class="fa-solid fa-thumbs-up"></i>
                    </span>
                    <input id="inputLikes" class="input" type="number" placeholder="Escriba la cantidad de likes">
                </div>
            </div>
            <div class="field is-grouped">
                <div class="control ">
                    <button id="addComment" onclick="addComment()" class=" button is-primary">Crear Usuario</button>
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
            <p>¿Estás seguro de que deseas eliminar este comentario?</p>
            <button id="confirmDeleteButton" class="button is-danger">Confirmar</button>
            <button id="cancelDeleteButton" class="button">Cancelar</button>
        </div>
    </div>
    <button class="modal-close is-large" aria-label="close"></button>
</div>
<script src="assets/js/comments.js"></script>