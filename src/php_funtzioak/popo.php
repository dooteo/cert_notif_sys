<?php

require_once "pop2.php";

// Create an API Key here: https://www.tractis.com/webservices/tsa/apikeys
// Copy the Identifier and Secret here:

/*$api_identifier = "your_api_identifier";
$api_secret = "your_api_secret";
$tsa_cert_chain_file = "chain.cer";
*/

$api_identifier = "";
$api_secret = "";
$tsa_cert_chain_file = "SafeCreative_TSA.cer";
date_default_timezone_set('Europe/Madrid'); 
$hashtype = 'sha1';
$my_hash = hash($hashtype, "content to stamp"); // <-- To Search in TSA Server ;)

echo 'myhash: '.$my_hash.'<br>';

$requestfile_path = TrustedTimestamps::createRequestfile($hashtype, $my_hash);


$response = TrustedTimestamps::signRequestfile(
				$requestfile_path, 
				"http://tsa.safecreative.org", 
				$api_identifier, 
				$api_secret);
print_r($response);
echo '<br><br>----------^ signRequestfile------------<br><br>';
/*  Array (
	    [response_string] => Shitload of text (base64-encoded Timestamp-Response of the TSA)
	    [response_time] => 1299098823
	)
*/

echo TrustedTimestamps::getTimestampFromAnswer($response['response_string']); //1299098823
echo '<br><br>---------^ getTimestampFromAnswer-------------<br><br>';
$validate = TrustedTimestamps::validate(
				$my_hash, 
				$response['response_string'], 
				$response['response_time'], 
				$tsa_cert_chain_file);

print_r("\nValidation result\n");

var_dump($validate); //bool(true)
echo '<br><br>--------^ validate--------------<br><br>';
//now with an incorrect hash. Same goes for a manipulated response string or response time
$validate = TrustedTimestamps::validate(
				sha1("im not the right hash"), 
				$response['response_string'], 
				$response['response_time'], 
				$tsa_cert_chain_file);
				
print_r("\nValidation result after content manipulation\n");

var_dump($validate); //bool(false)
echo '<br><br>----------------------<br><br>';
?>
