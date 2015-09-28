<?php

function errorResponse($httpResponseBody, $httpResponseStatusCode) {
    $status = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error',
    );

    header("HTTP/1.1 " . $httpResponseStatusCode . " " . $status[$httpResponseStatusCode]);
    header("Content-Type: application/json");
    echo json_encode($httpResponseBody);
}

spl_autoload_register(function ($class) {
//    $dl = explode('\\', $class);
//    $path = null;
//    $first = true;
//    foreach ($dl as $d) {
//        if ($first) {
//            $first = false;
//            $path .= $d;
//        } else {
//            $path .= DIRECTORY_SEPARATOR . $d;
//        }
//    }
//    include '..' . DIRECTORY_SEPARATOR . 'php-classes' . DIRECTORY_SEPARATOR . $path . '.php';
    if(file_exists($class.'.php')) {
        require_once $class.'.php';
    }
    else {
        $path = '..' . DIRECTORY_SEPARATOR . 'php-classes' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        require_once $path;
    }
});

try {
    $conf = parse_ini_file('config.ini', true);
    $mysqlConf = $conf['mysql'];
    $authConf = $conf['auth'];
    $API = new RegistrazioneApi($_GET['request'], $mysqlConf, $authConf);
    $API->processAPI();
} catch (restapi\BadRequestException $e) {
    $error = new restapi\Error(400, $e->getMessage());
    errorResponse($error, 400);
} catch (restapi\UnauthorizedException $e) {
    $error = new restapi\Error(401, $e->getMessage());
    errorResponse($error, 401);
} catch (restapi\NotFoundException $e) {
    $error = new restapi\Error(404, $e->getMessage());
    errorResponse($error, 404);
} catch (restapi\MethodNotAllowedException $e) {
    $error = new restapi\Error(405, $e->getMessage());
    errorResponse($error, 405);
} catch (restapi\UnprocessableEntityException $e) {
    $error = new restapi\Error($e->getCode(), $e->getMessage());
    errorResponse($error, 422);
} catch (dbproxy\ClientRequestException $e) {
    $error = new restapi\Error($e->getCode(), $e->getMessage());
    errorResponse($error, 422);
} catch (modules\login\ClientRequestException $e) {
    $error = new restapi\Error($e->getCode(), $e->getMessage());
    errorResponse($error, 422);
}
catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    header("Content-Type: text/html");
    echo get_class($e) . ': ' . $e->getMessage() . ' [Error code: ' . $e->getCode() . ']';
}