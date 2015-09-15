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

                $testCode = '01 - elenco completo senza vista';
                $r = http_request(URL_BASE . 'TesseratiFitri');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $list = json_decode($r->response->body, true);
                    if ($list) {
                        $n = count($list);
                        if ($n === 50) {
                            if(isset($list[0]['CODICE_SS'])) {
                                testPassed($testCode);
                            } else {
                                $msg = var_export($list['0'], true);
                                testFailedMsg($testCode, $r, $msg);
                            }
                        } else {
                            $msg = "Unexpected number of result ($n)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                
//--------------------------------------------------------------------------------------

                $testCode = '02 - elenco completo senza vista';
                $r = http_request(URL_BASE . 'TesseratiFitri');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $list = json_decode($r->response->body, true);
                    if ($list) {
                        $n = count($list);
                        if ($n === 50) {
                            if ($list[0]['TESSERA'] === 23 &&
                                    $list[0]['COGNOME'] === 'Vason' &&
                                    $list[0]['NOME'] === 'Fabio' &&
                                    $list[0]['DATA_NASCITA'] === '1957-02-26'
                            ) {
                                testPassed($testCode);
                            } else {
                                $e = var_export($list[0], true);
                                $msg = "Unexpected element ($e)";
                                testFailedMsg($testCode, $r, $msg);
                            }
                        } else {
                            $msg = "Unexpected number of result ($n)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                
//--------------------------------------------------------------------------------------

                $testCode = '03 - Elemento singolo senza vista';
                $r = http_request(URL_BASE . 'TesseratiFitri/124');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $t = json_decode($r->response->body, true);
                    if ($t) {

                        if ($t['TESSERA'] === 124 &&
                                $t['COGNOME'] === 'Pilotti' &&
                                $t['NOME'] === 'Eugenio' &&
                                $t['DATA_NASCITA'] === '1958-11-03' &&
                                isset($t['CODICE_SS'])
                        ) {
                            testPassed($testCode);
                        } else {
                            $e = var_export($t, true);
                            $msg = "Unexpected element ($e)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                
//--------------------------------------------------------------------------------------

                $testCode = '04 - Elemento singolo non esistente';
                $r = http_request(URL_BASE . 'TesseratiFitri?ttt');
                if ($r->response->code === 422 && $r->response->contentType === 'application/json') {
                    $t = json_decode($r->response->body, true);
                    if ($t) {

                        if ($t['code'] === 1054 &&
                                strpos($t['message'], '1054 Unknown column \'ttt\' in \'where clause\'') >= 0
                        ) {
                            testPassed($testCode);
                        } else {
                            $e = var_export($t, true);
                            $msg = "Unexpected element ($e)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                
//--------------------------------------------------------------------------------------

                $testCode = '05 - Elemento singolo selezionato con query, senza vista';
                $r = http_request(URL_BASE . 'TesseratiFitri?{%22TESSERA%22:1291}');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $list = json_decode($r->response->body, true);
                    if ($list) {
                        $n = count($list);
                        if ($n === 1) {
                            $t = $list[0];
                            if ($t['TESSERA'] === 1291 &&
                                    $t['COGNOME'] === 'Montemurro' &&
                                    $t['NOME'] === 'Alessandro' &&
                                    $t['DATA_NASCITA'] === '1959-07-22'
                            ) {
                                testPassed($testCode);
                            } else {
                                $e = var_export($t, true);
                                $msg = "Unexpected element ($e)";
                                testFailedMsg($testCode, $r, $msg);
                            }
                        } else {
                            $msg = "Unexpected number of result ($n)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                
//--------------------------------------------------------------------------------------


                $testCode = '06 - Elemento singolo selezionato con query, senza vista';
                $r = http_request(URL_BASE . 'TesseratiFitri?{%22TESSERA%22:427,%22COGNOME%22:%22Cattania%22}');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $list = json_decode($r->response->body, true);
                    if ($list) {
                        $n = count($list);
                        if ($n === 1) {
                            $t = $list[0];
                            if ($t['TESSERA'] === 427 &&
                                    $t['COGNOME'] === 'Cattania' &&
                                    $t['NOME'] === 'Michele' &&
                                    $t['DATA_NASCITA'] === '1956-10-30'
                            ) {
                                testPassed($testCode);
                            } else {
                                $e = var_export($t, true);
                                $msg = "Unexpected element ($e)";
                                testFailedMsg($testCode, $r, $msg);
                            }
                        } else {
                            $msg = "Unexpected number of result ($n)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }
                
//--------------------------------------------------------------------------------------

                $testCode = '07 - Elementi selezionati in modo articolato, senza vista';
                $r = http_request(URL_BASE . 'TesseratiFitri?{%22sort%22:{%22TESSERA%22:-1},%22limit%22:3,%22skip%22:2,%22TESSERA%22:{%22le%22:94861}}');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $list = json_decode($r->response->body, true);
                    if ($list) {
                        $n = count($list);
                        if ($n === 3) {
                            $t = $list[0];
                            if ($t['TESSERA'] === 94859 &&
                                    $t['COGNOME'] === 'Shaw' &&
                                    $t['NOME'] === 'Catherine Emma' &&
                                    $t['DATA_NASCITA'] === '1977-08-06'
                            ) {
                                testPassed($testCode);
                            } else {
                                $e = var_export($t, true);
                                $msg = "Unexpected element ($e)";
                                testFailedMsg($testCode, $r, $msg);
                            }
                        } else {
                            $msg = "Unexpected number of result ($n)";
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unable to parse JSON body';
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

