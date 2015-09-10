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

                $testCode = '00';
                $r = http_request(URL_BASE . 'Gara');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if (count($body) === 3) {
                        testPassed($testCode);
                    } else {
                        $msg = 'Unexpected count ' . count($body);

                        testFailedMsg($testCode, $r, $msg);
                    }
                } else {
                    testFailed($testCode, $r);
                }

//----------------------------------------------------------------------------

                $testCode = '01';
                $r = http_request(URL_BASE . 'Gara/2');
                if ($r->response->code === 200 && $r->response->contentType === 'application/json') {
                    $body = json_decode($r->response->body, true);
                    if ($body ['id'] === 2 
                            && $body['nome'] === 'TdM AISLA Olimpico MTB Individuale' 
                            && $body['descrizione_it'] === null 
                            && $body['descrizione_en'] === NULL
                            && $body['disputataIl'] === '2014-06-29 00:00:00' 
                            && $body['tipo']['id'] === 1
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

