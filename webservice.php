<?php
include "models/usermodel.php";
include "models/transactionmodel.php";
include "db/conn.php";

//phpinfo();
ini_set("soap.wsdl_cache_enabled", "0");



class BaseClass
{
    public $db;
    public function __construct()
    {
        $this->db = new DB;
    }

    public function Luhn($s)
    {
            $s = strrev(preg_replace('/[^\d]/','',$s));
            $sum = 0;
            for ($i = 0, $j = strlen($s); $i < $j; $i++) {
                if (($i % 2) == 0) {
                    $val = $s[$i];
                } else {
                    $val = $s[$i] * 2;
                    if ($val > 9)  $val -= 9;
                }
                $sum += $val;
            }
            return (($sum % 10) == 0);
    }
}





class MySoapHandler extends BaseClass
{
    function GetInformation($gp)
    {
        $res = new User($gp->password, $gp->username);
        $error = "Success";
        $status = 0;
        $valCode = $res->validate($this->db->conn);
        if (!$valCode) {
            return $res->ResponseInform($valCode['error'], $valCode['code']);
        }

        $servicename = $res->getServiceById($this->db->conn, $gp->serviceId);
        if ($servicename == 1) {
            $error = " Service doesn`t exists";
            $status = -1;
            return $res->ResponseInform($error, $status);
        }

        if ($gp->parameters->paramKey == "card_number") {
            $validate = $res->validateCard($gp->parameters->paramValue);
            // var_dump($validate);
            // exit();
            if ($validate != 0) {
                return $res->ResponseInform($validate['error'], $validate['code']);
            }

            $userd = $res->getLimitBalanceByCardNUmber($this->db->conn, $gp->parameters->paramValue);
            if ($userd == 1) {
                $error = "user not found";
                $status = -2;
                return $res->ResponseInform($error, $status);
            }
            $parameters = [
                [
                    'paramKey' => 'card_number',
                    'paramValue' => $gp->parameters->paramValue
                ],
                [
                    'paramKey' => 'balance',
                    'paramValue' => $userd['amount']
                ],
                [
                    'paramKey' => 'limit',
                    'paramValue' => $userd['max_limit']
                ]
            ];
        }
        if ($gp->parameters->paramKey == "phone_number") {
            $validate = $res->validatePhone($gp->parameters->paramValue);
            if ($validate !== 0) {
                return $res->ResponseInform($validate['error'], $validate['code']);
            } 

                $userd = $res->getLimitBalanceByPhone($this->db->conn, $gp->parameters->paramValue);
                if ($userd == 1) {
                    $error = "phone not found";
                    $status = -2;
                    return $res->ResponseInform($error, $status);
                } 
                $parameters = [
                    [
                        'paramKey' => 'phone_number',
                        'paramValue' => $gp->parameters->paramValue
                    ],
                    [
                        'paramKey' => 'balance',
                        'paramValue' => $userd['amount']
                    ],
                    [
                        'paramKey' => 'limit',
                        'paramValue' => $userd['max_limit']
                    ]
                ];
            
        }

        return $res->ResponseInform($error, $status, $parameters);
    }



    function PerformTransaction($gp)
    {
        $res = new User($gp->password, $gp->username);
        $valCode = $res->validate($this->db->conn);
        $error = "Success";
        $status = 0;

        if (!$valCode) {
            $error = " Wrong username or password";
            $status = 1;
            return $res->ResponseTransaction($error, $status);
        } 

        $servicename = $res->getServiceById($this->db->conn, $gp->serviceId);
        if ($servicename == 1 || $servicename['type'] != "perform_transaction") {
            $error = " Service not exists or wrong type";
            $status = -1;
            return $res->ResponseTransaction($error, $status);
        }

        if ($gp->parameters[0]->paramKey === "sender_card") {
            $validate = $res->validateCard($gp->parameters[0]->paramValue);
            if ($validate != 0) {
                return $res->ResponseTransaction($validate['error'], $validate['code']);
            }

            if ($gp->parameters[1]->paramKey === "recipient_card") {
                $validate = $res->validateCard($gp->parameters[1]->paramValue);
                if ($validate != 0) {
                    return $res->ResponseTransaction($validate['error'], $validate['code']);
                }
                $trans = new Transaction();
                $resp = $trans->makeTransactionByCard($this->db->conn, $gp->amount, $gp->parameters[0]->paramValue, $gp->parameters[1]->paramValue, $gp->transactionId);
                if ($resp['status'] == 1) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 2) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 3) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 4) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 5) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 6) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 7) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 8) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 9) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                }
            }



            if ($gp->parameters[1]->paramKey === "recipient_phone") {
                $validate = $res->validatePhone($gp->parameters[1]->paramValue);
                if ($validate !== 0) {
                    return $res->ResponseInform($validate['error'], $validate['code']);
                } 
                $trans = new Transaction();
                $resp = $trans->makeTransactionByPhone($this->db->conn, $gp->amount, $gp->parameters[0]->paramValue, $gp->parameters[1]->paramValue, $gp->transactionId);
                if ($resp['status'] == 1) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 2) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 3) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 4) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 5) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 6) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 7) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 8) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                } else  if ($resp['status'] == 9) {
                    return $res->ResponseTransaction($resp['error'], $resp['status']);
                }
            }
        }

        $arr = [
            'timeStamp' => $resp['timeStamp'],
            'errorMsg' => $resp['status'],
            'status' => 1,
            'parameters' => $resp['parameters'],
            'providerTrnId' => $resp['providerTrnId']
        ];
        return $arr;
    }


    function CheckTransaction($gp)
    {
        return [
            'timeStamp' => $gp->transactionTime,
            'errorMsg' =>'OK',
            'status' => 0,
            'providerTrnId' => $gp->transactionId,
            'transactionState' =>0,
            'transactionStateErrorStatus'=>200,
            'transactionStateErrorMsg'=>"OK"
        ];
    }
}




$server = new SoapServer("ProviderWebService.wsdl", array("trace" => 1, "soap_version" => SOAP_1_1));
$server->setClass("MySoapHandler");
$server->handle();
