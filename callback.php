<?php
if(!isset($_GET['code'])){
    echo 'Acceso Denegado';
    die();
}


$gdata = Config::getGoogleConfig();
$code = $_GET['code'];
$client_id = $gdata['web']['client_id'];
$client_secret = $gdata['web']['client_secret'];
$redirect_uri = $gdata['web']['redirect_uris'][0];

$token_url = 'https://oauth2.googleapis.com/token';
//$token_url = 'https://accounts.google.com/o/oauth2/token';
$token_data = array(
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code'
);

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$token_response = curl_exec($ch);
//var_dump($token_response);

if ($token_response === false) {
    echo 'Error de cURL: ' . curl_error($ch).'<br>';
    echo 'Presione <a href="/">aqui</a> para regresar';
} else {
    $token_info = json_decode($token_response, true);
    if (isset($token_info['access_token'])) {
        $access_token = $token_info['access_token'];
        $userinfo_url = 'https://www.googleapis.com/oauth2/v3/userinfo';
        $userinfo_headers = array(
            'Authorization: Bearer ' . $access_token
        );
        $ch2 = curl_init($userinfo_url);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $userinfo_headers);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $userinfo_response = curl_exec($ch2);
        //var_dump($userinfo_response);
        if ($userinfo_response === false) {
            echo 'Error de cURL: ' . curl_error($ch2) . '<br>';
            echo 'Presione <a href="/">aqui</a> para regresar';
        } else {
            $userinfo = json_decode($userinfo_response, true);
            //*var_dump($userinfo);
            $openid = $userinfo['sub'];
            $email = $userinfo['email'];
            $fullname = $userinfo['name'];
            $pass = substr('xxxxx' . bin2hex(random_bytes(45)), 0, 50);
            $response = UserController::RegisterOpenId($openid, $email, $fullname, $pass);
            //var_dump($response);
            if ($response['error'] === 0) {
                header('Location: /users?openid='. $openid. '&fullname='. $fullname.'&id='. $response['id']);
                exit();
            }
        }
        curl_close($ch2);
    } else {
        echo 'Error al obtener el token de acceso<br>';
        echo 'Presione <a href="/">aqui</a> para regresar';
    }
}
curl_close($ch);
