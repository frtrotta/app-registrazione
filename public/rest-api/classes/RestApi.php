<?php

/**
 * Based on http://coreymaynard.com/blog/creating-a-restful-api-with-php/
 */
abstract class RestApi {

    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';

    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
    protected $endpoint = '';

    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    protected $verb = '';

    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();

    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;

    /**
     * Constructor: __construct
     * Assemble and pre-process the data
     */
    public function __construct($request) {
        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);

        /* array_shift() shifts the first value of the array off and returns it,
         * shortening the array by one element and moving everything down. All 
         * numerical array keys will be modified to start counting from zero 
         * while literal keys won't be touched.
         */

        if (isset($this->args[0]) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }

        /* TODO non funzionerebbe come Deployed perchè assume che il verb sia
         * non numerico. Ad esempio, però, uid di Deployd sono alfanumerici
         */

        /* HTTP Verb Tunneling
         * https://dev.onedrive.com/misc/verb-tunneling.htm
         */
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && isset($_SERVER['HTTP_X_HTTP_METHOD'])) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        $this->request = null;
        $this->contentType = null;
        $this->body = null;
        switch ($this->method) {
            case 'POST':
                $this->_parseRequestBody();
                break;
            case 'PUT':
                $this->_parseRequestBody();
            case 'GET':
            case 'DELETE':
                $this->request = $this->_parseQueryString();
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        }

        if (isset($this->contentType)) {
            
        }
    }

    public function processAPI() {
        if (method_exists($this, $this->endpoint)) {
            $this->_response($this->{$this->endpoint}());
        } else {
            //$this->response("No Endpoint: $this->endpoint", 404);
            throw new NotFoundException("No Endpoint: $this->endpoint");
        }
    }

    private function _parseNameValuePairs($string) {
        /* Must use direct access to "raw" query string or body to avoid char replacement
         * performed by PHP.
         * Refer to http://stackoverflow.com/questions/68651/get-php-to-stop-replacing-characters-in-get-or-post-arrays
         */
        $list = explode('&', $string);
        $data = [];
        foreach ($list as $e) {
            $temp = explode('=', $e);
            if (count($temp) == 2) {
                $data[urldecode($temp[0])] = urldecode($temp[1]);
            } else {
                $data[urldecode($temp[0])] = null;
            }
        }
        return $data;
    }

    /**
     * This is needed for two reasons: (1) because of URL modification to handle requests
     * (2) to handle both JSON and NVP query strings.
     * @return type
     */
    private function _parseQueryString() {
        $data = $this->_parseNameValuePairs($_SERVER['QUERY_STRING']);
        array_shift($data); // because of URL modification

        $r = false;
        if (isset(array_keys($data)[0])) {
            $r = json_decode(array_keys($data)[0], true);
        }
        if (!$r) {
            $r = $data;
        }
        return $r;
    }

    private function _parseRequestBody() {
        $this->contentType = filter_input(INPUT_SERVER, 'CONTENT_TYPE');
        $this->body = file_get_contents('php://input');
        switch ($this->contentType) {
            case 'application/json':
            case 'application/json;charset=UTF-8';
                $this->request = json_decode(file_get_contents('php://input'), true);
                break;
            case 'application/x-www-form-urlencoded':
            case 'multipart/form-data':
                $this->request = $this->_parseNameValuePairs($this->body);
                break;
            default:
                throw new BadRequestException('Unsupported content type: ' . $this->contentType);
        }
    }

    protected function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        header("Content-Type: application/json");
        $r = json_encode($data);
        if (!$r) {
            throw new Exception('Error in JSON encoding: (' . json_last_error() . ') "');
            //TOD throw new Exception('Error in JSON encoding: (' . json_last_error() . ') "' . json_last_error_msg()) . '"';
        }
        echo $r;
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            //$clean_input = trim(strip_tags($data));
            $clean_input = filter_var($data, FILTER_SANITIZE_STRING);
        }
        return $clean_input;
    }

    /**
     * Complete list at http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     * 
     * @param type $code
     * @return type
     */
    private function _requestStatus($code) {
        $status = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

}
