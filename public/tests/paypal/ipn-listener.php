<?php

$logText = null;

function logFileName() {
    return (new DateTime())->format('Y-m-dTHis') . '.log';
}

function addToLog($line) {
    global $logText;
    $logText .= (new DateTime())->format('Y-m-d H:i:s') . ' -> ' . $line . "\n";
    return;
}

function writeLog() {
    global $logText;
    file_put_contents(logFileName(), $logText);
    return;
}

function IPNIsValid() {
    $r = false;
    $IPNVAlidationUrl = null;
    $requestBody = 'cmd=_notify-validate&' . $raw_post_data = file_get_contents('php://input');
    if (strpos($requestBody, 'test_ipn=1') === false) {
        $IPNVAlidationUrl = 'https://www.paypal.com/cgi-bin/webscr';
    } else {
        $IPNVAlidationUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }

    $ch = curl_init($IPNVAlidationUrl);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    // In wamp-like environments that do not come bundled with root authority certificates,
    // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set 
    // the directory path of the certificate as shown below:
    // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
    $res = curl_exec($ch);
    if ($res) {
        switch ($res) {
            case 'VERIFIED':
                $r = true;
                break;
            case 'INVALID':
                break;
            default:
                // TODO
                file_put_contents(logFileName(), 'Unexpected result: ' . $res);
                throw new \Exception('Unexpected result: ' . $res);
        }
    } else {
        // TODO
        file_put_contents(logFileName(), 'Unsuccesful requesto to ' . $IPNVAlidationUrl . '. Got error: ' . curl_error($ch));
        throw new \Exception('Unexpected result: ' . $res);
    }
    curl_close($ch);
    return $r;
}

// IPN validation
if (IPNIsValid()) {
    addToLog('Verified IPN');

//    // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
//    // Instead, read raw POST data from the input stream. 
//    $raw_post_data = file_get_contents('php://input');
//    $raw_post_array = explode('&', $raw_post_data);
//
//    $myPost = array();
//    foreach ($raw_post_array as $keyval) {
//        $keyval = explode('=', $keyval);
//        if (count($keyval) == 2) {
//            $myPost[$keyval[0]] = urldecode($keyval[1]);
//        }
//    }
//
//    // Summary of the operations
//    // 0 - Preliminar transaction type verification
//    //1) Check that the payment_status is Completed.
//    //2) If the payment_status is Completed, check the txn_id against the previous PayPal transaction that you processed to ensure the IPN message is not a duplicate.
//    //3) Check that the receiver_email is an email address registered in your PayPal account.
//    //4) Check that the price (carried in mc_gross) and the currency (carried in mc_currency) are correct for the item (carried in item_name or item_number).
//    //--------------------------------------------------------------------------------------
//    // 0 - Preliminar transaction type verification
//    if (!isset($myPost['txn_type'])) {
//        // TODO
//    }
//    if ($myPost['txn_type'] !== 'cart') {
//        // TODO only cart is supported
//    }
//
//    //--------------------------------------------------------------------------------------
//    //1) Check that the payment_status is Completed.
//    if (!isset($myPost['payment_status'])) {
//        // TODO
//    }
//
//    if ($myPost['payment_status'] !== 'Completed') {
//        // TODO
//    }
//
//    //--------------------------------------------------------------------------------------
//    //2) If the payment_status is Completed, check the txn_id against the previous PayPal transaction that you processed to ensure the IPN message is not a duplicate
//    if (!isset($myPost['txn_id'])) {
//        // TODO
//    }
//    $tnx_id = $myPost['txn_id'];
//    // TODO check for unicity
//    //--------------------------------------------------------------------------------------
//    //3) Check that the receiver_email is an email address registered in your PayPal account.
//    if (!isset($myPost['receiver_email'])) {
//        // TODO
//    }
//
//    if ($myPost['receiver_email'] !== $receiver_email) {
//        // TODO
//    }
//
//    //--------------------------------------------------------------------------------------
//    //4-preliminar) Get the order data
//    if (!isset($myPost['custom'])) {
//        // TODO
//    }
//    $idOrdine = $myPost['custom'] + 0;
//    
//    //--------------------------------------------------------------------------------------
//    //4) Check that the price (carried in mc_gross) and the currency (carried in mc_currency) are correct for the item (carried in item_name or item_number).
//    if (!isset($myPost['mc_gross'])) {
//        // TODO
//    }
//    if (!isset($myPost['mc_currency'])) {
//        // TODO
//    }
//    if ($myPost['mc_currency'] != 'EUR') {
//        // TODO
//    }
//    $orderTotal = $myPost['mc_gross'] + 0;
//    if($orderTotal !== $totale) {
//        // TODO
//    }
} else {
    addToLog('Invalid IPN');
}
writeLog();
//
//// STEP 1: read POST data
//// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
//// Instead, read raw POST data from the input stream. 
//$raw_post_data = file_get_contents('php://input');
//$raw_post_array = explode('&', $raw_post_data);
//
//$myPost = array();
////if (count($raw_post_array) > 0) {
//foreach ($raw_post_array as $keyval) {
//    $keyval = explode('=', $keyval);
//    if (count($keyval) == 2) {
//        $myPost[$keyval[0]] = urldecode($keyval[1]);
//    }
//}
//
//// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
//
//$log .= '----------------------------------';
//if (file_put_contents('.' . microtime() . '.log', $log) === false) {
//    echo 'impossibile scrivere';
//}
//
////Check that the payment_status is Completed.
////If the payment_status is Completed, check the txn_id against the previous PayPal transaction that you processed to ensure the IPN message is not a duplicate.
////Check that the receiver_email is an email address registered in your PayPal account.
////Check that the price (carried in mc_gross) and the currency (carried in mc_currency) are correct for the item (carried in item_name or item_number).
////    }
////} else {
////    header("HTTP/1.1 422 Unprocessable entity");
////    header("Content-Type: text/plain");
////    echo 'No data to process';
////}
//
//    