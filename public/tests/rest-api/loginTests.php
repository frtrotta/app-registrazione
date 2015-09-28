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

                function inserisciUtenteTest($mysqlConf) {
                    //---- inserimento utente di test

                    $conn = new mysqli($mysqlConf['server'], $mysqlConf['username'], $mysqlConf['password'], $mysqlConf['database']);
                    if ($conn->connect_errno) {
                        throw new Exception("Connection error: $this->conn->connect_error");
                    }
                    $query = "INSERT INTO utente "
                            . "(password, nome, cognome, email, eAmministratore)"
                            . " VALUES "
                            . "('test', 'nome test', 'cognome test', 'email@test.com', 0)";
                    $conn->query($query);
                    if ($conn->errno) {
                        throw new Exception($conn->error);
                    }
                    $conn->close();
                }

                function rimuoviUtenteTest($mysqlConf) {
                    $conn = new mysqli($mysqlConf['server'], $mysqlConf['username'], $mysqlConf['password'], $mysqlConf['database']);
                    if ($conn->connect_errno) {
                        throw new Exception("Connection error: $this->conn->connect_error");
                    }
                    $query = "DELETE FROM utente "
                            . " WHERE "
                            . " email = 'email@test.com'";
                    $conn->query($query);
                    if ($conn->errno) {
                        throw new Exception($conn->error);
                    }
                    $conn->close();
                }

                define("URL_BASE", "http://localhost/app-registrazione/rest-api/");

                inserisciUtenteTest($mysqlConf);

//----------------------------------------------------------------------------

                $testCode = '00a - Login senza fornire estremi autenticazione';
                $r = http_request(URL_BASE . 'Login');
                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if (isset($body ['code']) && isset($body ['message']) && $body ['code'] === 1 && $body ['message'] === 'No email and/or password provided') {
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
                
                $testCode = '00b - Login fornendo estremi autenticazione non corretti (email vuota) in query string, NVP';
                $r = http_request(URL_BASE . 'Login?email=&password=test');
                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if (isset($body ['code']) && isset($body ['message']) && $body ['code'] === 1 && $body ['message'] === 'No email and/or password provided') {
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
                
                
                $testCode = '00c - Login fornendo estremi autenticazione corretti in query string, NVP';
                $r = http_request(URL_BASE . 'Login?email=email%40test.com&password=');
                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if (isset($body ['code']) && isset($body ['message']) && $body ['code'] === 1 && $body ['message'] === 'No email and/or password provided') {
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

//----------------------------------------------------------------------------
                
                $testCode = '01a - Login fornendo estremi autenticazione corretti in query string, JSON';
                $r = http_request(URL_BASE . 'Login?'.urlencode('{"email":"email@test.com","password":"test"}'));
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    testPassed($testCode);
                } else {
                    testFailed($testCode, $r);
                }
                
                $testCode = '01b - Login fornendo estremi autenticazione corretti in corpo, JSON';
                $r = http_request(URL_BASE . 'Login', null, 'POST', 'application/json', '{"email":"email@test.com","password":"test"}');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    testPassed($testCode);
                } else {
                    testFailed($testCode, $r);
                }
                
                $testCode = '01c - Login fornendo estremi autenticazione corretti in corpo, NVP';
                $r = http_request(URL_BASE . 'Login', null, 'POST', 'application/x-www-form-urlencoded', 'email=email%40test.com&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    testPassed($testCode);
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '02a';
                $r = http_request(URL_BASE . 'Login?email=email%40test.com&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie1 = $r->response->cookies[$authConf['cookie-name']];
                    testPassed($testCode, "Cookie: $cookie1");
                } else {
                    testFailed($testCode, $r);
                }
                
                //----------------------------------------------------------------------------

                $testCode = '02b';
                $r = http_request(URL_BASE . 'Login?email=email%40test.com&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie1 = $r->response->cookies[$authConf['cookie-name']];
                    testPassed($testCode, "Cookie: $cookie1");
                } else {
                    testFailed($testCode, $r);
                }
//----------------------------------------------------------------------------

                $testCode = '02c';
                $r = http_request(URL_BASE . 'Login?email=email%40test.com&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie1 = $r->response->cookies[$authConf['cookie-name']];
                    testPassed($testCode, "Cookie: $cookie1");
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '03';
                $r = http_request(URL_BASE . 'Login?email=email%40test.com&password=test');
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie2 = $r->response->cookies[$authConf['cookie-name']];
                    if ($cookie1 !== $cookie2) {
                        testPassed($testCode, "Cookie: $cookie2");
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
                    $body = json_decode($r->response->body, true);
                    if ($body ['nome'] === 'nome test' 
                            && $body ['cognome'] === 'cognome test' && $body ['email'] === 'email@test.com' && !$body['eAmministratore'] && $body ['telefono'] === NULL
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = 'username: ' . $body ['username']
                                . "\nnome: " . $body ['nome']
                                . "\ncognome: " . $body ['cognome']
                                . "\nemail: " . $body ['email']
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
                if ($r->response->code === 200
                        //&& isset($r->response->cookies[$authConf['cookie-name']])                
                        && $r->response->contentType === 'application/json'
                ) {
                    $body = json_decode($r->response->body);
                    if($body  === 'No user to log out') {
                        testPassed($testCode);
                    } else {
                        $msg = '$body ' . $body;
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
                if ($r->response->code === 200
                        //&& isset($r->response->cookies[$authConf['cookie-name']])                
                        && $r->response->contentType === 'application/json'
                ) {
                    $body = json_decode($r->response->body);
                    if($body  === 'No user to log out') {
                        testPassed($testCode);
                    } else {
                        $msg = '$body ' . $body;
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
                    $body = json_decode($r->response->body);
                    if ($body === null
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = $body;
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

//---------------------------------------------------------             

                $testCode = '07a';
                $r = http_request(URL_BASE . 'Login?email=email%40test.com&password=test');
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
                    $body = json_decode($r->response->body, true);
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

                rimuoviUtenteTest($mysqlConf);
                ?>
            </tbody>
        </table>
    </body>

</html>

