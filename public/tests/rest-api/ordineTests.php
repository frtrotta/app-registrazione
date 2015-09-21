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

//----------------------------------------------------------------------------
                $testCode = '00 - Richiesta ordini (non ammessa)';
                // Chiedo l'elenco degli utenti
                $r = http_request(URL_BASE . 'Ordine');
                if ($r->response->code === 405 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        if ($body['code'] === 405 && $body['message'] === 'Method GET is not allowed') {
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

                $testCode = '01 - Aggiornamento ordine (non ammesso)';

                $r = http_request(URL_BASE . 'Ordine/1', null, 'PUT', 'application/json;charset=UTF-8', null);

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        if ($body['code'] === 110 && $body['message'] === 'Update not supported') {
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
                function creaOrdine($idCliente, $idModalitaPagamento, $arrayIscrizioni) {
                    $o = [];
                    $o['ricevutoIl'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $o['totale'] = 100.23;
                    $o['pagato'] = false;
                    $o['ricevutaInviata'] = false;
                    $o['ricevutaInviataIl'] = null;
                    $o['note'] = 'Ordine del ' . $o['ricevutoIl'];
                    $o['clienteIndirizzoCap'] = substr('CAP ' . $o['ricevutoIl'], 0, 10);
                    $o['clienteIndirizzoCitta'] = 'Citta ' . $o['ricevutoIl'];
                    $o['clienteIndirizzoPaese'] = 'Paese ' . $o['ricevutoIl'];
                    $o['idModalitaPagamento'] = $idModalitaPagamento;
                    $o['idCliente'] = $idCliente;
                    $o['iscrizioni'] = $arrayIscrizioni;
                    return $o;
                }

                function creaIscrizione($idGara, $adesionePersonale, $squadra, $arrayInviti) {
                    $i = [];
                    $i ['idGara'] = $idGara;
                    $i['eseguitaIl'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $i['motto'] = 'Motto delle ' . $i['eseguitaIl'];
                    $i['haImmagine'] = false;
                    if (isset($adesionePersonale)) {
                        $i['adesionePersonale'] = $adesionePersonale;
                    }
                    if (isset($squadra)) {
                        $i['squadra'] = $squadra;
                    }
                    if (isset($arrayInviti)) {
                        $i['inviti'] = $arrayInviti;
                    }
                    return $i;
                }

                function creaInvito() {
                    $ciccio = 0;
                    for ($i = 0; $i < 1000000; $i++) {
                        $ciccio += $i;
                    }
                    $temp = substr(sha1(microtime()), 0, 10);
                    $inv = [];
                    $inv['nome'] = 'nome ' . $temp;
                    $inv['cognome'] = 'cognome ' . $temp;
                    $inv['email'] = 'delete_' . $temp . '@gmail.com';
                    return $inv;
                }

                function creaAdesionePersonale($idUtente, $richiestaTesseramento) {
                    $ciccio = 0;
                    for ($i = 0; $i < 10000; $i++) {
                        $ciccio += $i;
                    }
                    $temp = substr(sha1((new \DateTime())->format('Ymhsi')), 0, 20);
                    $ap = [];
                    $ap['categoriaFitri'] = 'S3';
                    $ap['indirizzoCap'] = substr('CAP ' . $temp, 0, 10);
                    $ap['indirizzoCitta'] = 'Citta ' . $temp;
                    $ap['indirizzoPaese'] = 'Paese ' . $temp;
                    $ap['richiestaTesseramento'] = $richiestaTesseramento;
                    $ap['idUtente'] = $idUtente;
                    return $ap;
                }

                function creaSquadra($arrayAdesioniPersonali) {
                    $ciccio = 0;
                    for ($i = 0; $i < 10000; $i++) {
                        $ciccio += $i;
                    }
                    $temp = substr(sha1((new \DateTime())->format('Y-m-d H:i:s')), 0, 20);
                    $s = [];
                    $s['nome'] = 'Nome ' . $temp;
                    $s['motto'] = 'Motto ' . $temp;
                    $s['adesioniPersonali'] = $arrayAdesioniPersonali;
                    return $s;
                }

                define('TRT_GIORNATA', 1);
                define('TRT_FITRI', 2);

                define('TESSERAMENTO_GIORNATA', 1);
                define('TESSERAMENTO_FITRI', 2);

                define('PAGAMENTO_VIA_PAYPAL', 1);
                define('PAGAMENTO_VIA_BONIFICO_BANCARIO', 2);

                define('ID_GARA', 1);

                function creaRichiestaTesseramento($idTipoRichiestaTesseramento, $tesseramento) {
                    $rt = [];
                    $rt['eseguitaIl'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $rt['verificata'] = false;
                    $rt['idTipoRichiestaTesseramento'] = $idTipoRichiestaTesseramento;
                    $rt['tesseramento'] = $tesseramento;
                    return $rt;
                }

                function creaTesseramento($matricola, $codiceSocietaFitri, $stranieroSocieta, $idTipoTesseramento) {
                    $r = [];
                    $r['finoAl'] = (new \DateTime())->format('Y-m-d H:i:s');
                    $r['matricola'] = $matricola;
                    $r['codiceSocietaFitri'] = $codiceSocietaFitri;
                    $r['stranieroSocieta'] = $stranieroSocieta;
                    $r['idTipoTesseramento'] = $idTipoTesseramento;
                    return $r;
                }

                function inserisciUtenteTest($mysqlConf) {

                    $conn = new mysqli($mysqlConf['server'], $mysqlConf['username'], $mysqlConf['password'], $mysqlConf['database']);
                    if ($conn->connect_errno) {
                        throw new Exception("Connection error: $this->conn->connect_error");
                    }
                    $query = "INSERT INTO utente "
                            . "(password, nome, cognome, email, eAmministratore)"
                            . " VALUES "
                            . "('test', 'nome test', 'cognome test', 'delete_test@gmail.com', 0)";
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
                            . " email = 'delete_test@gmail.com'";
                    $conn->query($query);
                    if ($conn->errno) {
                        throw new Exception($conn->error);
                    }
                    $conn->close();
                }

                function controllaSeSecondoHaUgualiCampiPrimo($primo, $secondo) {
                    $r = true;
                    foreach ($primo as $key => $value) {
                        if (array_key_exists($key, $secondo)) {
                            if (is_array($value)) {
                                $r = controllaSeSecondoHaUgualiCampiPrimo($primo[$key], $secondo[$key]);
                                if ($r !== true) {
                                    break;
                                }
                            } else {
                                if ($primo[$key] !== $secondo[$key]) {
                                    $r = 'Chiave ' . $key . ' ha valore differente';
                                    break;
                                }
                            }
                        } else {
                            $r = 'Secondo non ha chiave ' . $key;
                            break;
                        }
                    }
                    return $r;
                }

//----------------------------------------------------------------------------
                //inserisciUtenteTest($mysqlConf);
//----------------------------------------------------------------------------
                $testCode = 'login';
                $xe = urlencode('delete_test@gmail.com');
                $yp = urlencode('test');
                $r = http_request(URL_BASE . "Login?email=$xe&password=$yp");
                if ($r->response->code === 200 && isset($r->response->cookies[$authConf['cookie-name']]) && $r->response->contentType === 'application/json') {
                    $cookie = $r->response->cookies[$authConf['cookie-name']];

                    testPassed($testCode, "Cookie: $cookie");
                } else {
                    testFailed($testCode, $r);
                }

                $testCode = 'me';
                $r = http_request(URL_BASE . 'Me', array($authConf['cookie-name'] => $cookie));
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    $idUtente = $body['id'];
                    testPassed($testCode, "idUtente: $idUtente");
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------
                $testCode = '10 - aggiunge solo ordine CONFERMARE'; // TODO va bene poter aggiungere solo ordine?
                $idCliente = $idUtente;
                $o = creaOrdine(
                        $idCliente, PAGAMENTO_VIA_BONIFICO_BANCARIO, [ creaIscrizione(ID_GARA, null, null, [creaInvito()])]
                );
                $r = http_request(URL_BASE . 'Ordine', array($authConf['cookie-name'] => $cookie), 'PUT', 'application/json;charset=UTF-8', json_encode($o));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        testPassed($testCode);
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------
                $testCode = '11 - aggiunge ordine con solo inviti, con vista ordine';
                $r = http_request(URL_BASE . 'Ordine/ordine', array($authConf['cookie-name'] => $cookie), 'PUT', 'application/json;charset=UTF-8', json_encode($o));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $check = controllaSeSecondoHaUgualiCampiPrimo($o, $body);
                        if ($check === true) {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $check);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------


                $o_singolo_errato = creaOrdine(
                        $idCliente, PAGAMENTO_VIA_BONIFICO_BANCARIO, [ 
                            creaIscrizione(ID_GARA, creaAdesionePersonale($idUtente, creaRichiestaTesseramento(TESSERAMENTO_GIORNATA, null)), null, [creaInvito()])]
                );

                $testCode = '12 - aggiunge ordine con adesione personale e inviti, con vista ordine';
                $r = http_request(URL_BASE . 'Ordine/ordine', array($authConf['cookie-name'] => $cookie), 'PUT', 'application/json;charset=UTF-8', json_encode($o_singolo_errato));

                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        if($body['code'] === 93 && $body['message'] === 'Incoherent data for dbproxy\Iscrizione. adesionePersonale and inviti cannot be both set.') {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $check);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------


                $o_singolo = creaOrdine(
                        $idCliente, PAGAMENTO_VIA_BONIFICO_BANCARIO, [ 
                            creaIscrizione(ID_GARA, creaAdesionePersonale($idUtente, creaRichiestaTesseramento(TESSERAMENTO_GIORNATA, null)), null, null)]
                );

                $testCode = '13 - aggiunge ordine con adesione personale e inviti, con vista ordine';
                $r = http_request(URL_BASE . 'Ordine/ordine', array($authConf['cookie-name'] => $cookie), 'PUT', 'application/json;charset=UTF-8', json_encode($o_singolo));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $check = controllaSeSecondoHaUgualiCampiPrimo($o_singolo, $body);
                        if ($check === true) {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $check);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------


                $o_squadra = creaOrdine(
                        $idCliente, PAGAMENTO_VIA_BONIFICO_BANCARIO, [ 
                            creaIscrizione(ID_GARA, null, creaSquadra([creaAdesionePersonale($idUtente, creaRichiestaTesseramento(TESSERAMENTO_GIORNATA, null))]), [creaInvito(), creaInvito()])]
                );

                $testCode = '14 - aggiunge ordine con squadra, con vista ordine';
                $r = http_request(URL_BASE . 'Ordine/ordine', array($authConf['cookie-name'] => $cookie), 'PUT', 'application/json;charset=UTF-8', json_encode($o_squadra));

                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body) {
                        $check = controllaSeSecondoHaUgualiCampiPrimo($o_squadra, $body);
                        if ($check === true) {
                            testPassed($testCode);
                        } else {
                            testFailedMsg($testCode, $r, $check);
                        }
                    } else {
                        testFailedMsg($testCode, $r, 'Error in decoding JSON');
                    }
                } else {
                    testFailed($testCode, $r);
                }
//----------------------------------------------------------------------------                
                //rimuoviUtenteTest($mysqlConf);
//----------------------------------------------------------------------------
                ?>
            </tbody>
        </table>
    </body>

</html>

