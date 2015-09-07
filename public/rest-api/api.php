<?php

function errorResponse($httpResponseBody, $httpResponseStatusCode) {
    $status = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    );

    header("HTTP/1.1 " . $httpResponseStatusCode . " " . $status[$httpResponseStatusCode]);
    header("Content-Type: application/json");
    echo json_encode($httpResponseBody);
}

spl_autoload_register(function ($class) {
    $dl = explode('\\', $class);
    $path = null;
    $first = true;
    foreach ($dl as $d) {
        if ($first) {
            $first = false;
            $path .= $d;
        } else {
            $path .= DIRECTORY_SEPARATOR . $d;
        }
    }
    include 'classes' . DIRECTORY_SEPARATOR . $path . '.php';
});

try {
    $conf = parse_ini_file('config.ini', true);
    $mysqlConf = $conf['mysql'];
    $authConf = $conf['auth'];
    $API = new RegistrazioneApi($_REQUEST['request'], $mysqlConf, $authConf);
    $API->processAPI();
} catch (BadRequestException $e) {
    $error = new Error(400, $e->getMessage());
    errorResponse($error, 400);
} catch (UnauthorizedException $e) {
    $error = new Error(401, $e->getMessage());
    errorResponse($error, 401);
} catch (NotFoundException $e) {
    $error = new Error(404, $e->getMessage());
    errorResponse($error, 404);
} catch (MethodNotAllowedException $e) {
    $error = new Error(405, $e->getMessage());
    errorResponse($error, 405);
} catch (InconsistentDataException $e) {
    $error = new Error(500, $e->getMessage());
    errorResponse($error, 500);
} catch (dbproxy\MysqlProxyBaseException $e) {
    $error = new Error(500, $e->getMessage());
    errorResponse($error, 500);
} catch (RegistrazioneApiException $e) {
    $error = new Error(500, $e->getMessage());
    errorResponse($error, 500);
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    header("Content-Type: text/html");
    echo $e->getMessage();
}