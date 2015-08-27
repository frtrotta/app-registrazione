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

        switch ($this->method) {
            case 'DELETE':
            case 'POST':
                $this->request = $this->_cleanInputs($_POST);
                break;
            case 'GET':
                $this->request = $this->_cleanInputs($_GET);
                break;
            case 'PUT':
                $this->request = $this->_cleanInputs($_GET);
                $this->file = file_get_contents("php://input");
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        }
    }

    public function processAPI() {
        if (method_exists($this, $this->endpoint)) {
            $this->_response($this->{$this->endpoint}());
        }
        else {
            //$this->response("No Endpoint: $this->endpoint", 404);
            throw new NotFoundException("No Endpoint: $this->endpoint");
        }
    }

    protected function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        header("Content-Type: application/json");
        echo json_encode($data);
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
