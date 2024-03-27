<?php
/*
Crear un usuario                        -> POST   /api/user
Eliminar un usuario                     -> DELETE /api/user/{id}
Actualizar un usuario                   -> PUT    /api/user
Conseguir la información de un usuario  -> GET    /api/user/{id}
*Todos los usuarios                     -> GET    /api/users
*Login                                  -> POST   /api/login

Crear un comentario                     -> POST   /api/comment
Eliminar un comentario                  -> DELETE /api/comment/{id}
Actualizar un comentario                -> PUT    /api/comment
Conseguir la información de un comentario->GET    /api/comment/{id}
*Todos los comentarios                  -> GET    /api/comments
*Actualizar un like                     -> PUT   /api/like

*/
class ApiRouter
{
    private $id;
    private $method;
    public function __construct()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $urlParts = explode("/api/", $request_uri);
        $api = explode("/", $urlParts[1]);
        $this->method = $api[0];
        if (isset($api[1])) {
            $this->id = $api[1];
        }
    }
    public function handleApiRequest()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $method = $this->method;
        if ($method == 'user') {
            self::handleUserRoute($this->id);
        } elseif ($method == 'users') {
            self::handleUsersRoute();
        } elseif ($method == 'comment') {
            self::handleCommentRoute($this->id);
        } elseif ($method == 'comments') {
            self::handleCommentsRoute();
        } elseif ($method == 'like') {
            self::handleLikeRoute();
        } elseif ($method == 'login') {
            self::handleLoginRoute();
        } else {
            self::response(404, "Ruta no encontrada");
        }
    }

    private static function handleUserRoute($id = 0)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                self::handleUserCreation();
                break;
            case 'DELETE':
                self::handleUserDeletion($id);
                break;
            case 'PUT':
                self::handleUserUpdate();
                break;
            case 'GET':
                self::handleUserRetrieval($id);
                break;
            default:
                self::response(404, "Método no permitido");
                break;
        }
    }


    private static function handleUserCreation()
    {
        $requestData = json_decode(file_get_contents('php://input'), true);

        $fullname = $requestData['fullname'] ?? null;
        $email = $requestData['email'] ?? null;
        $pass = $requestData['pass'] ?? null;
        $openid = $requestData['openid'] ?? null;
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::response(400, "El correo electrónico no es válido");
        }
        if (!$openid || $openid == 'generate') {
            $openid = substr('xxxxx' . bin2hex(random_bytes(45)), 0, 50);
        }
        if (isset($fullname, $email, $pass, $openid)) {
            $fullname = htmlspecialchars($fullname);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $openid = htmlspecialchars($openid);

            $response = UserController::createUser($fullname, $email, $pass, $openid);
            if (isset($response['error']) && $response['error'] == 1) {
                self::response(400, $response['mensaje']);
            } else {
                self::response(200, ["mensaje" => $response['mensaje'], "id" => $response['id']]);
            }
        } else {
            self::response(400, "Faltan datos");
        }
    }
    private static function handleUserUpdate()
    {
        $requestData = json_decode(file_get_contents('php://input'), true);
        $id = $requestData['id'] ?? null;
        $fullname = $requestData['fullname'] ?? null;
        $email = $requestData['email'] ?? null;
        $pass = $requestData['pass'] ?? null;
        $openid = $requestData['openid'] ?? null;

        if (!isset($id) || !is_numeric($id)) {
            self::response(400, "Se requiere un ID de usuario válido y numérico");
        }
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::response(400, "El correo electrónico no es válido");
        }

        if (isset($id) && ($fullname !== null || $email !== null || $pass !== null || $openid !== null)) {
            $id = intval($id);
            if ($fullname) {
                $fullname = htmlspecialchars($fullname);
            }
            if ($email) {
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            }
            if ($pass) {
                $pass = password_hash($pass, PASSWORD_DEFAULT);
            }
            if ($openid) {
                $openid = htmlspecialchars($openid);
            }
            $response = UserController::updateUser($id, $fullname, $email, $pass, $openid);
            if (isset($response['error']) && $response['error'] == 1) {
                self::response(400, $response['mensaje']);
            } else {
                self::response(200, $response['mensaje']);
            }
        } else {
            self::response(400, "Faltan Datos");
        }
    }

    private static function handleUserDeletion($id)
    {

        if (!isset($id) || !is_numeric($id)) {
            self::response(400, "Se requiere un ID de usuario válido y numérico");
        }
        $response = UserController::deleteUser($id);
        if (isset($response['error']) && $response['error'] == 1) {
            self::response(400, $response['mensaje']);
        } else {
            self::response(200, $response['mensaje']);
        }
    }
    private static function handleUserRetrieval($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            self::response(400, "Se requiere un ID de usuario válido y numérico");
        }

        $users = UserController::getUser($id);
        self::response(200, $users);
    }

    private static function handleUsersRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $users = UserController::getAllUsers();
            self::response(200, $users);
        } else {
            self::response(404, "Método no permitido");
        }
    }
    private static function handleCommentsRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $comments = CommentController::getallComments();
            self::response(200, $comments);
        } else {
            self::response(404, "Método no permitido");
        }
    }
    private static function handleLikeRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $requestData = json_decode(file_get_contents('php://input'), true);
            $id = intval($requestData['id']);
            if (!isset($id) || !is_numeric($id)) {
                self::response(400, "Se requiere un ID de comentario válido y numérico");
            }

            $comments = CommentController::incrementLike($id);
            self::response(200, $comments);
        } else {
            self::response(404, "Método no permitido");
        }
    }
    private static function handleLoginRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestData = json_decode(file_get_contents('php://input'), true);
            $email = $requestData['email'] ?? null;
            $pass = $requestData['pass'] ?? null;
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                self::response(400, "El correo electrónico no es válido");
            }
            if (!$pass) {
                self::response(400, "Favor de enviar el password");
            }
            $user = UserController::getUserbyEmail($email);
            if (password_verify($pass, $user['pass'])) {
                self::response(200, ["fullname" => $user['fullname'], "id" => $user['id']]);
            } else {
                self::response(400, "Contraseña no válida");
            }
        } else {
            self::response(404, "Método no permitido");
        }
    }

    private static function handleCommentRoute($id = 0)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                self::handleCommentCreation();
                break;
            case 'DELETE':
                self::handleCommentDeletion($id);
                break;
            case 'PUT':
                self::handleCommentUpdate();
                break;
            case 'GET':
                self::handleCommentRetrieval($id);
                break; 
            default:
                self::response(404, "Método no permitido");
                break;
        }
    }
    private static function handleCommentCreation()
    {
        $requestData = json_decode(file_get_contents('php://input'), true);
        $user = $requestData['user'] ?? null;
        $coment_text = $requestData['coment_text'] ?? null;
        $likes = $requestData['likes'] ?? null;
        if (!$likes) {
            $likes = 0;
        }
        if (isset($user, $coment_text, $likes)) {
            $user = htmlspecialchars($user);
            $coment_text = htmlspecialchars($coment_text);
            $likes = htmlspecialchars($likes);

            $response = CommentController::createComment($user, $coment_text, $likes);
            if (isset($response['error']) && $response['error'] == 1) {
                self::response(400, $response['mensaje']);
            } else {
                self::response(200, ["mensaje" => $response['mensaje'], "id" => $response['id']]);
            }
        } else {
            self::response(400, "Faltan datos");
        }
    }
    private static function handleCommentDeletion($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            self::response(400, "Se requiere un ID de comentario válido y numérico");
        }
        $response = CommentController::deleteComment($id);
        if (isset($response['error']) && $response['error'] == 1) {
            self::response(400, $response['mensaje']);
        } else {
            self::response(200, $response['mensaje']);
        }
    }
    private static function handleCommentUpdate()
    {
        $requestData = json_decode(file_get_contents('php://input'), true);
        $id = $requestData['id'] ?? null;
        $user = $requestData['user'] ?? null;
        $coment_text = $requestData['coment_text'] ?? null;
        $likes = $requestData['likes'] ?? null;
        if (!isset($id) || !is_numeric($id)) {
            self::response(400, "Se requiere un ID de comentario válido y numérico");
        }
        if (isset($id) && ($user !== null || $coment_text !== null || $likes !== null)) {
            $id = intval($id);
            $user = intval($user);
            $coment_text = htmlspecialchars($coment_text);
            $likes = intval($likes);

            $response = CommentController::updateComment($id, $user, $coment_text, $likes);
            if (isset($response['error']) && $response['error'] == 1) {
                self::response(400, $response['mensaje']);
            } else {
                self::response(200, $response['mensaje']);
            }
        } else {
            self::response(400, "Faltan datos");
        }
    }
    private static function handleCommentRetrieval($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            self::response(400, "Se requiere un ID de comentario válido y numérico");
        }
        $users = CommentController::getComment($id);
        self::response(200, $users);
    }

    private static function response($code, $data)
    {
        http_response_code($code);
        if (is_string($data)) {
            echo json_encode(["mensaje" => $data]);
        } elseif (is_array($data)) {
            echo json_encode($data);
        }
        die();
    }
}

$router = new ApiRouter();
$router->handleApiRequest();
