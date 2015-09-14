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

                function createUtente(
                $id, $password, $nome, $cognome, $sesso, $natoIl, $email, $facebookId, $telefono, $eAmministratore
                ) {
                    return [
                        'id' => $id,
                        'password' => $password,
                        'nome' => $nome,
                        'cognome' => $cognome,
                        'sesso' => $sesso,
                        'natoIl' => $natoIl,
                        'email' => $email,
                        'facebookId' => $facebookId,
                        'telefono' => $telefono,
                        'eAmministratore' => $eAmministratore
                    ];
                }

                function inserisciAmministratore($mysqlConf) {
                    //---- inserimento utente di test

                    $email = 'delete_email_amministratore@test.com';
                    $password = 'test';

                    $conn = new mysqli($mysqlConf['server'], $mysqlConf['username'], $mysqlConf['password'], $mysqlConf['database']);
                    if ($conn->connect_errno) {
                        throw new Exception("Connection error: $this->conn->connect_error");
                    }
                    $query = "INSERT INTO utente "
                            . "(password, nome, cognome, email, eAmministratore)"
                            . " VALUES "
                            . "('$password', 'nome ammnistratore test', 'cognome amministratore test', '$email', 1)";
                    $conn->query($query);
                    if ($conn->errno) {
                        throw new Exception($conn->error);
                    }
                    $id = $conn->insert_id;
                    $conn->close();
                    $r = [
                        'email' => $email,
                        'password' => $password,
                        'id' => $id
                    ];

                    return $r;
                }

//
                function rimuoviUtenteTest($mysqlConf, $id) {
                    $conn = new mysqli($mysqlConf['server'], $mysqlConf['username'], $mysqlConf['password'], $mysqlConf['database']);
                    if ($conn->connect_errno) {
                        throw new Exception("Connection error: $this->conn->connect_error");
                    }
                    $query = "DELETE FROM utente "
                            . " WHERE "
                            . " id = $id";
                    $conn->query($query);
                    if ($conn->errno) {
                        throw new Exception($conn->error);
                    }
                    $conn->close();
                }

                define("URL_BASE", "http://localhost/app-registrazione/rest-api/");
//
//                inserisciUtenteTest($mysqlConf);
//----------------------------------------------------------------------------

                $testCode = '00';
                // Chiedo l'elenco degli utenti
                $r = http_request(URL_BASE . 'Utente');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        if (count($body) === 50) {
                            testPassed($testCode);
                        } else {
                            $msg = 'Unexpected number of results: ' . count($body);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '01';
                // Aggiungo un utente con PUT
                /* Nota data di nascita: questo utente mi servirà in seguito per verificare
                 * rilevazione opportuna dei nati in 2014-06-29
                 */
  
                $u1_2014 = createUtente(null, 'ciccio', 'alessio', 'formaggio', 'M', '2014-06-29', 'delete_u1_2014@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente', null, 'PUT', 'application/json;charset=UTF-8', json_encode($u1_2014));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $u1_2014['id'] = $body['id'];
                        if ($body == $u1_2014) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '02';
                // Aggiungo un utente che ha gli stessi campi di altro utente con data di nasciata 2014-06-29.
                $u1_1978 = createUtente(null, 'ciccio', 'alessio', 'formaggio', 'M', '1978-04-17', 'delete_u1_1978@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente', null, 'PUT', 'application/json', json_encode($u1_1978));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $u1_1978['id'] = $body['id'];
                        //giuseppe($u1_1978, $body);
                        if ($body == $u1_1978) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------               

                $testCode = '03';
                // Aggiungo nuovamente lo stesso utente di prima
                $r = http_request(URL_BASE . 'Utente', null, 'PUT', 'application/json', json_encode($u1_1978));

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        $email = $u1_1978['email'];
                        if ($body->code === 50 && $body->message === "User exists with email $email") {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $email);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }


//----------------------------------------------------------------------------               

                $testCode = '04';
                // Richiedo l'utente aggiunto prima e ne verifico i dati
                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], null, 'GET', null, null);

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $p = $u1_1978;
                        unset($p['password']);
                        if ($body == $p) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true) . var_export($p, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------               

                $testCode = '05';
                // Eseguo una richiesta di utente, impiegando il filtro per email
                $pars = ['email' => $u1_1978['email']];
                $r = http_request(URL_BASE . 'Utente?' . urlencode(json_encode($pars)), null, 'GET', null, null);

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $n = count($body);
                        if ($n === 1) {
                            $u = $body[0];
                            $p = $u1_1978;
                            unset($p['password']);
                            if ($u == $p) {
                                testPassed($testCode);
                            } else {
                                $msg = var_export($u, true) . var_export($p, true);
                                testFailedMsg($testCode, $r, $msg);
                            }
                        } else {
                            testFailedMsg($testCode, $r, "Unexpected number of results: $n rather than 1");
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }


//----------------------------------------------------------------------------               

                $testCode = '10-preliminare';

                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], null, 'POST', 'application/json', null);

                if ($r->response->code === 401 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code === 401 && $body->message === 'User must be Amministratore to update users') {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------               

                $testCode = '10a';
                // Cerco di aggiornare un utente
                $previous_email = $u1_1978['email'];
                $u1_1978['email'] = 'delete_fracazzodavelletri@gmail.com';
                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], null, 'POST', 'application/json', json_encode($u1_1978));

                if ($r->response->code === 401 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code === 401 && $body->message === 'User must be Amministratore to update users') {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

                $testCode = '10b';
                // mi assicuro che l'utente letto non sia stato aggiornato
                $u1_1978['email'] = $previous_email;
                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], null, 'GET', null, null);

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $p = $u1_1978;
                        unset($p['password']);
                        if ($body == $p) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true) . var_export($p, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------               

                $testCode = '11';

                // Cerco di creare un utente amministrazione
                $u2 = createUtente(null, 'ciccio', 'amministratore', 'che amministra', 'M', '1980-05-23', 'delete_u2@gmail.com', null, null, true);

                $r = http_request(URL_BASE . 'Utente', null, 'PUT', 'application/json', json_encode($u2));

                if ($r->response->code === 401 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code === 401 && $body->message === 'User must be Amministratore to create an Amministratore') {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------               

                $amministratore = \inserisciAmministratore($mysqlConf);
                $testCode = 'login';
                $xe = urlencode($amministratore['email']);
                $yp = urlencode($amministratore['password']);
                $r = http_request(URL_BASE . "Login?email=$xe&password=$yp");
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie1 = $r->response->cookies[$authConf['cookie-name']];
                    testPassed($testCode, "Cookie: $cookie1");
                } else {
                    testFailed($testCode, $r);
                }

                //----------------------------------------------------------------------------    
                $testCode = '20';

                $r = http_request(URL_BASE . 'Utente', array($authConf['cookie-name'] => $cookie1), 'PUT', 'application/json', json_encode($u2));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $u2['id'] = $body['id'];
                        if ($body == $u2) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '30';
                // provo a creare un utente con password di lunghezza zero
                $u3 = createUtente(null, '', 'ttttt', 'garibaldi', 'M', '1978-04-17', 'delete_u3@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente', null, 'PUT', 'application/json', json_encode($u3));

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code >= 90 && $body->code <= 99) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '31a';
                // provo ad aggiornare un utente con nome di lunghezza zero
                // leggo un utente
                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], null, 'GET', null, null);
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $u_temp = $body;
                        testPassed($testCode);
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

                $testCode = '31b';
                // modifico il nome impostandolo a lunghezza 0
                // opero come amministratore
                $u_temp['nome'] = '';
                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], array($authConf['cookie-name'] => $cookie1), 'POST', 'application/json', json_encode($u_temp));

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code >= 90 && $body->code <= 99) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

                // verifico che non sia stato aggiornato
                $testCode = '31c';
                $r = http_request(URL_BASE . 'Utente/' . $u1_1978['id'], null, 'GET', null, null);

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $body['password'] = $u1_1978['password'];
                        if ($body == $u1_1978) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true) . "\n" . var_export($u1_1978, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '40';
                // Aggiungo un utente con metodo POST
                $u_trotta = createUtente(null, 'ciccio', 'francesco', 'trotta', 'M', '1978-04-17', 'delete_u_trotta@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente', null, 'POST', 'application/json', json_encode($u_trotta));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $u_trotta['id'] = $body['id'];
                        $lastId = $body['id'];
                        if ($body == $u_trotta) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '41';
                // Aggiorno un utente con metodo PUT
                $u_trotta = createUtente($lastId, 'ciccio', 'francesco è grande', 'trotta', 'M', '1978-04-17', 'delete_u_trotta@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente/'.$u_trotta['id'], array($authConf['cookie-name'] => $cookie1), 'PUT', 'application/json', json_encode($u_trotta));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        if ($body == $u_trotta) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body, true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '50';
                // Aggiungo un utente con metodo POST, ma con password e facebookid nulli
                $u_gazzella = createUtente(null, null, 'lorenzo', 'gazzella', 'M', '1978-04-17', 'delete_u_gazzella@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente', null, 'POST', 'application/json', json_encode($u_gazzella));

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code === 90) {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $email);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '51';
                // Aggiungo un utente con metodo POST, ma con password e facebookid nulli
                $u_gazzella = createUtente(null, '', 'lorenzo', 'gazzella', 'M', '1978-04-17', 'delete_u_gazzella@gmail.com', null, null, false);

                $r = http_request(URL_BASE . 'Utente', null, 'POST', 'application/json', json_encode($u_gazzella));

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body);
                    if ($body) {
                        if ($body->code === 90) {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $email);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }


                rimuoviUtenteTest($mysqlConf, $u1_1978['id']);
                rimuoviUtenteTest($mysqlConf, $u1_2014['id']);
                rimuoviUtenteTest($mysqlConf, $u2['id']);
                rimuoviUtenteTest($mysqlConf, $amministratore['id']);
                rimuoviUtenteTest($mysqlConf, $u_trotta['id']);
                
                //rimuoviUtenteTest($mysqlConf, $u3['id']);
                ?>
            </tbody>
        </table>
    </body>

</html>

