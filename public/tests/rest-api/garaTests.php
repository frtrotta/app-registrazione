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

                $testCode = '00 - Richiesta di tutti gli elementi senza vista';
                $r = http_request(URL_BASE . 'Gara');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if (count($body) === 3) {
                        if (isset($body[0]['idTipoGara'])) {
                            testPassed($testCode);
                        } else {
                            $msg = var_export($body[0], true);
                            testFailedMsg($testCode, $r, $msg);
                        }
                    } else {
                        $msg = 'Unexpected count ' . count($body);

                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '01 - Richiesta di un elemento senza vista';
                $r = http_request(URL_BASE . 'Gara/2');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body ['id'] === 2 && $body['nome'] === 'TdM AISLA Olimpico MTB Individuale' && $body['descrizione_it'] === null && $body['descrizione_en'] === NULL && $body['disputataIl'] === '2014-06-29 00:00:00' && $body['idTipoGara'] === 1
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = 'id: ' . $body['id']
                                . "\nnome: " . $body['nome']
                                . "\ndescrizione_it: " . $body['descrizione_it']
                                . "\ndescrizione_en: " . $body['descrizione_en']
                                . "\ndisputataIl: " . $body['disputataIl']
                                . "\ntipo-id: " . $body['tipo']['id'];
                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

               $testCode = '02 - Richiesta di un elemento con vista default';

                $r = http_request(URL_BASE . 'Gara/2/default');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body ['id'] === 2 && $body['nome'] === 'TdM AISLA Olimpico MTB Individuale' && $body['descrizione_it'] === null && $body['descrizione_en'] === NULL && $body['disputataIl'] === '2014-06-29 00:00:00' && $body['tipo']['id'] === 1
                    ) {
                        testPassed($testCode);
                    } else {
                        $msg = 'id: ' . $body['id']
                                . "\nnome: " . $body['nome']
                                . "\ndescrizione_it: " . $body['descrizione_it']
                                . "\ndescrizione_en: " . $body['descrizione_en']
                                . "\ndisputataIl: " . $body['disputataIl']
                                . "\ntipo-id: " . $body['tipo']['id'];
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

