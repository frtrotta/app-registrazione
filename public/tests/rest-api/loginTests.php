<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    </head>
    <body>
        <table class="table">
            <tbody>
                <?php
                include 'testBase.php';

                define("URL_BASE", "http://localhost/app-registrazione/rest-api/");

                $testCode = '01';
                $r = http_request(URL_BASE . 'Login');
                if ($r->response->code === 400 && $r->response->contentType === 'application/json') {
                    testPassed($testCode);
                } else {
                    testFailed($testCode, $r);
                }

                $testCode = '02';
                $r = http_request(URL_BASE . 'Login?username=test&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie1 = $r->response->cookies[$authConf['cookie-name']];
                    testPassed($testCode);
                } else {
                    testFailed($testCode, $r);
                }

                $testCode = '03';
                $r = http_request(URL_BASE . 'Login?username=test&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie2 = $r->response->cookies[$authConf['cookie-name']];
                    if ($cookie1 !== $cookie2) {
                        testPassed($testCode);
                    } else {
                        $msg = "cookie1: $cookie1\ncookie2: $cookie2";
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

                //---------------------------------------------------------
                $testCode = '04';
                $r = http_request(URL_BASE . 'Me', array($authConf['cookie-name'] => $cookie2));
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = (array) json_decode($r->response->body);
                    if ($body ['username'] === 'test' && $body ['nome'] === 'Francesco' && $body ['cognome'] === 'Trotta' && $body ['email'] === 'fratrotta@gmail.com' && $body ['natoIl'] === '1978-04-17' && $body['eAmministratore'] && $body ['telefono'] === NULL
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = 'username: ' . $body ['username']
                                . "\nnome: " . $body ['nome']
                                . "\ncognome: " . $body ['cognome']
                                . "\nemail: " . $body ['email']
                                . "\nnatoIl: " . $body ['natoIl']
                                . "\ntelefono: " . $body ['telefono']
                                . "\neAmministratore: " . $body['eAmministratore'];
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

                //---------------------------------------------------------
                $testCode = '05a';
                $r = http_request(URL_BASE . 'Logout', array($authConf['cookie-name'] => $cookie1));
                if ($r->response->code === 400
                        //&& isset($r->response->cookies[$authConf['cookie-name']])                
                        && $r->response->contentType === 'application/json'
                ) {
                    $body = (array) json_decode($r->response->body);
                    if (isset($body ['code']) && isset($body ['message']) && $body ['code'] === 400 && $body ['message'] === 'No valid auth cookie') {
                        testPassed($testCode);
                    } else {
                        $msg = 'isset($body[\'code\']) ' . isset($body ['code'])
                                . "\nisset(\$body['message']) " . isset($body ['message'])
                                . "\n\$body['code']" . $body ['code']
                                . "\n\$body['message']" . $body['message'];

                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

                //---------------------------------------------------------
                $testCode = '05b';
                $r = http_request(URL_BASE . 'Logout', array($authConf['cookie-name'] => $cookie2));
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json'
                ) {
                    if ($r->response->cookies[$authConf['cookie-name']] === 'deleted') {
                        testPassed($testCode);
                    } else {
                        $msg = $r->response->cookies[$authConf['cookie-name']];
                        testFailed($testCode, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

                //---------------------------------------------------------
                $testCode = '05c';
                $r = http_request(URL_BASE . 'Logout', array($authConf['cookie-name'] => $cookie1));
                if ($r->response->code === 400
                        //&& isset($r->response->cookies[$authConf['cookie-name']])                
                        && $r->response->contentType === 'application/json'
                ) {
                    $body = (array) json_decode($r->response->body);
                    if (isset($body ['code']) && isset($body ['message']) && $body ['code'] === 400 && $body ['message'] === 'No valid auth cookie') {
                        testPassed($testCode);
                    } else {
                        $msg = 'isset($body[\'code\']) ' . isset($body ['code'])
                                . "\nisset(\$body['message']) " . isset($body ['message'])
                                . "\n\$body['code']" . $body ['code']
                                . "\n\$body['message']" . $body['message'];

                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }


                unset($cookie1);
                unset($cookie2);

                //---------------------------------------------------------
                $testCode = '06';
                $r = http_request(URL_BASE . 'Me');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = (array) json_decode($r->response->body);
                    if ($body === []
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = $body;
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

                $testCode = '07a';
                $r = http_request(URL_BASE . 'Login?username=test&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie = $r->response->cookies[$authConf['cookie-name']];
                    testPassed($testCode);
                } else {
                    testFailed($testCode, $r);
                }

                //---------------------------------------------------------
                $testCode = '07b';
                $r = http_request(URL_BASE . 'Me', array($authConf['cookie-name'] => $cookie));
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = (array) json_decode($r->response->body);
                    if ($body !== []
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = $body;
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                ?>
            </tbody>
        </table>
    </body>

</html>
