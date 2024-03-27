<?php
date_default_timezone_set('America/Mexico_City');
$request_uri = $_SERVER['REQUEST_URI'];
require_once 'config/config.php';
require_once 'helpers/DbClass.php'; 
require_once 'controllers/CommentController.php'; 
require_once 'controllers/UserController.php'; 


if (strpos($request_uri, '/api/') === 0) {
    $urlParts = explode("/api/", $request_uri);
    require_once 'controllers/ApiRouter.php';
} elseif (strpos($request_uri, '/callback') === 0) {
    require_once 'callback.php';
} elseif (strpos($request_uri, '/users') === 0) {
    $content = 'views/users.php';
    require_once 'views/layout.php';
} elseif (strpos($request_uri, '/comments') === 0) {
    $content= 'views/comments.php';
    require_once 'views/layout.php';
} else {
    require_once 'views/register.php';
}
