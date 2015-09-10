<?php

//preg_match("/(\d{4})-(\d{2})-(\d{2})/", "2012-10-20", $results);
//var_dump($results);

class cUrlResult {

    public $request;
    public $response;

    public function __construct() {
        $this->request = new httpRequest();
        $this->response = new httpResponse();
    }

}

class httpRequest {

    public $header;

}

class httpResponse {

    public $code;
    public $header;
    public $contentType;
    public $content;
    public $cookies;

}

function http_request($url, $cookies = NULL, $method = 'GET', $contentType = null, $body = null) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => true, // return headers
        CURLINFO_HEADER_OUT => true,
        CURLOPT_FOLLOWLOCATION => false, // follow redirects
        CURLOPT_ENCODING => "", // handle compressed
        CURLOPT_USERAGENT => "test", // name of client
        CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
        CURLOPT_TIMEOUT => 120, // time-out on response
    );

    switch ($method) {
        case 'GET':
            break;
        case 'POST':
        case 'PUT':
        case 'DELETE':
            switch ($contentType) {
                case 'application/json':
                case 'application/x-www-form-urlencoded':
                    $options[CURLOPT_CUSTOMREQUEST] = $method;
                    $options[CURLOPT_POST] = 1;
                    $options[CURLOPT_POSTFIELDS] = $body;
                    $options[CURLOPT_HTTPHEADER] = array(
                        'Content-Type: ' . $contentType,
                        'Content-Length: ' . strlen($body));
                    break;
                default:
                    throw new Exception('Unsupported content type ' . $contentType);
            }
            break;
        default:
            throw new Exception('Unsupported method ' . $method);
    }

    if (isset($cookies)) {
        foreach ($cookies as $name => $value) {
            $options[CURLOPT_HTTPHEADER][] = "Cookie: $name=$value";
        }
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $r = new cUrlResult();
    $r->response->content = curl_exec($ch);

    $matches = null;
    preg_match('![\n\r]{4}(.*)!mi', $r->response->content, $matches);
    if (isset($matches[0])) {
        $r->response->body = $matches[0];
    }

    $matches = null;
    preg_match_all('!^Set-Cookie: (\w*)=(\w*)!mi', $r->response->content, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        if (isset($m[1]) && isset($m[2])) {
            $r->response->cookies[$m[1]] = $m[2];
        } else {
            throw new Exception('No cookie matched');
        }
    }

    $r->response->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $r->response->contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $r->request->header = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    curl_close($ch);

    return $r;
}

function testPassed($testCode, $msg = null) {
    echo "<tr class=\"success\">"
    . "<td colspan=\"2\">Test&nbsp;$testCode&nbsp;OK"
    . (isset($msg)?" <small>($msg)</small>":'')
    ."</td>"
    . "</tr>";
}

function testFailed($testCode, $cUrlResult) {
    echo "<tr class=\"danger\">"
    . "<td><strong>Test&nbsp;$testCode&nbsp;failed</strong></td>"
    . "<td>"
    . "<pre class=\"small\">" . $cUrlResult->request->header . "</pre>"
    . "<pre class=\"small\">" . $cUrlResult->response->content . "</pre>"
    . "</td>"
    . "</tr>";
}

function testFailedMsg($testCode, $cUrlResult, $msg) {
    echo "<tr class=\"danger\">"
    . "<td><strong>Test&nbsp;$testCode&nbsp;failed</strong></td>"
    . "<td>"
    . "<pre>" . $msg . "</pre>"
    . "<pre class=\"small\">" . $cUrlResult->request->header . "</pre>"
    . "<pre class=\"small\">" . $cUrlResult->response->content . "</pre>"
    . "</td>"
    . "</tr>";
}

$conf = parse_ini_file(__DIR__ . '\..\..\rest-api\config.ini', true);
$mysqlConf = $conf['mysql'];
$authConf = $conf['auth'];
?>

