<?php

//phpinfo();
ini_set("soap.wsdl_cache_enabled", "0"); 

class MyGetInformationArguments
{
    public $paramKey;
    public $paramValue;
    public $password;
    public $username;
    public $serviceId;
  
}

$req = new MyGetInformationArguments();

$req->parameters = [
    ['paramKey' => 'user_id',
    'paramValue'=>1]
];
$req->username = "user1";
$req->serviceId =3;
$req->password = "1234";






$client = new SoapClient("ProviderWebService.wsdl",array( 'soap_version' => SOAP_1_1));
//$functions = $client->__getFunctions ();
// try{
//     var_dump($client->GetInformation($req));
    
// }
// catch (SoapFault $exception) {

// echo $exception;      
// }
echo '<pre>';
var_dump($client->GetInformation($req));
echo '</pre>';
exit;
//$client->retData();
//var_dump($client->GetInformation($req));
?>
