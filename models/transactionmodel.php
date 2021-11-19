<?php

class Transaction
{


    public $sender_id;
    public $recipient_id;




    public function validateSender($conn, $amount, $sender)
    {
        $sql = 'SELECT id, max_limit, amount FROM cards WHERE card_number = "' . $sender . '"';
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            return [
                'error' => "sender not found",
                'status' => 1
            ];
        } else {
            $row = $result->fetch_assoc();
            if ($row['max_limit'] < $amount) {
                return [
                    'error' => "sender maximum limit is less than amount",
                    'status' => 3
                ];
            } else if ($row['amount'] < $amount) {
                return [
                    'error' => "the sender does not have enough funds",
                    'status' => 4
                ];
            }
        }
        return 0;
    }



    public function validateRecipientCard($conn, $amount, $recipient)
    {
        $sql = 'SELECT id, max_limit FROM cards WHERE card_number = "' . $recipient . '"';
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            return [
                'error' => "recipient not found",
                'status' => 2
            ];
        } else {
            $row = $result->fetch_assoc();
            if ($row['max_limit'] < $amount) {
                return [
                    'error' => "recipient maximum limit is less than amount",
                    'status' => 5
                ];
            }
        }
        return 0;
    }
    public function validateRecipientPhone($conn, $amount, $recipient)
    {
        $sql = 'SELECT c.id, c.max_limit, c.card_number FROM cards c INNER JOIN users u ON u.card_id = c.owner_id WHERE phone_num = "' . $recipient . '"';
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            return [
                'error' => "recipient not found",
                'status' => 2
            ];
        } else {
            $row = $result->fetch_assoc();
            if ($row['max_limit'] < $amount) {
                return [
                    'error' => "recipient maximum limit is less than amount",
                    'status' => 5
                ];
            }
            return $row;
        }
        
        
        
    }

    public function incOrDec($conn, $amount, $type, $sign, $code)
    {
        $sql = 'UPDATE cards SET amount = amount ' . $sign . ' ' . $amount . ' where  card_number = "' . $type . '"';
        $result = $conn->query($sql);
        // var_dump($result);
        //           exit();      
        if (!$result) {
            if ($code == 10) {
                return [
                    'error' => "transaction error: impossible to top up the balance",
                    'status' => 6
                ];
            }
            return [
                'error' => "transaction error: cannot be withdrawn from balance",
                'status' => 7
            ];
        }
        return 0;
    }



    public function getId($conn, $type)
    {
        $sql = 'SELECT u.id FROM users u  INNER JOIN cards c ON u.card_id =c.owner_id WHERE c.card_number="' . $type . '"';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
    }


    public function updateStatus($conn,$lastid)
    {
        $sql = 'UPDATE transactions SET STATUS ="1" WHERE id = '. $lastid .'';

        $result = $conn->query($sql);
        //   var_dump($sql);
        //       exit();        
        if (!$result) {
            return [
                'error' => "unable to to update transaction status",
                'status' => 9
            ];
        }
        return 0;
    }



    public function makeTransactionByCard($conn, $amount, $sender, $recipient, $transaction_id)
    {

        $resval = $this->validateSender($conn, $amount, $sender);

        if ($resval != 0) return $resval;

        $resval = $this->validateRecipientCard($conn, $amount, $recipient);

        if ($resval != 0) return $resval;

        // 10 = recipient
        // 11 = sender
        $resval = $this->incOrDec($conn, $amount, $recipient, "+",  10);
        if ($resval != 0) return $resval;

        $resval = $this->incOrDec($conn, $amount, $recipient, "-", 11);
        if ($resval != 0) return $resval;



        $this->sender_id = $this->getId($conn, $sender);
        $this->recipient_id = $this->getId($conn, $recipient);

        $sql = 'INSERT INTO transactions 
                                        (transaction_id, 
                                        sender_id, 
                                        sender_card, 
                                        recipient_id, 
                                        recipient_card, 
                                        transaction_type, 
                                        amount) VALUES 
                                                    (' . $transaction_id . ', 
                                                    ' . $this->sender_id . ', 
                                                    "' . $sender . '", 
                                                    ' . $this->recipient_id . ', 
                                                    "' . $recipient . '", 
                                                    "by_card", 
                                                    ' . $amount . ')';

        $result = $conn->query($sql);
        //   var_dump($sql);
        //       exit();        
        if (!$result) {
            return [
                'error' => "unable to write a transaction",
                'status' => 8
            ];
        }

        $last_id = $conn->insert_id;
        $resval = $this->updateStatus($conn, $last_id);
        if ($resval != 0) return $resval;


        
        // var_dump($last_id );
        //   exit(); 

        $sql = 'SELECT transaction_id, sender_card, recipient_card, transaction_type, amount, transaction_time  FROM transactions   WHERE id=' . $last_id . '';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
        }
        $transresp = [
            'timeStamp' => $row['transaction_time'],
            'providerTrnId' => $row['transaction_id'],
            'status' => 'OK',
            'parameters' => [
                [
                    'paramKey' => 'sender_card',
                    'paramValue' => $row['sender_card']
                ],
                [
                    'paramKey' => 'recipient_card',
                    'paramValue' => $row['recipient_card']
                ],
                [
                    'paramKey' => 'amount',
                    'paramValue' => $row['amount']
                ]
            ]
        ];

        return $transresp;
    }







    public function makeTransactionByPhone($conn, $amount, $sender, $recipient, $transaction_id)
    {

        $resval = $this->validateSender($conn, $amount, $sender);

        if ($resval != 0) return $resval;

        $resvalue = $this->validateRecipientPhone($conn, $amount, $recipient);

        if(!isset($resvalue['card_number'])) return $resval;
        
        // 10 = recipient
        // 11 = sender
        $resval = $this->incOrDec($conn, $amount, $resvalue['card_number'], "+",  10);
        if ($resval != 0) return $resval;

        $resval = $this->incOrDec($conn, $amount, $sender, "-", 11);
        if ($resval != 0) return $resval;

        

        $this->sender_id = $this->getId($conn, $sender);
        $this->recipient_id = $this->getId($conn, $resvalue['card_number']);

        $sql = 'INSERT INTO transactions 
                                        (transaction_id, 
                                        sender_id, 
                                        sender_card, 
                                        recipient_id, 
                                        recipient_phone, 
                                        transaction_type, 
                                        amount) VALUES 
                                                    (' . $transaction_id . ', 
                                                    ' . $this->sender_id . ', 
                                                    "' . $sender . '", 
                                                    ' . $this->recipient_id . ', 
                                                    "' . $recipient . '", 
                                                    "by_phone", 
                                                    ' . $amount . ')';

        $result = $conn->query($sql);
               
        if (!$result) {
            return [
                'error' => "unable to write a transaction",
                'status' => 8
            ];
        }
        $last_id = $conn->insert_id;
        $resval = $this->updateStatus($conn, $last_id);
        if ($resval != 0) return $resval;
        // var_dump($last_id );
        //   exit(); 

        $sql = 'SELECT transaction_id, sender_card, recipient_card, recipient_phone, transaction_type, amount,transaction_time  FROM transactions   WHERE id=' . $last_id . '';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
        }
        $transresp = [
            'timeStamp' => $row['transaction_time'],
            'providerTrnId' => $row['transaction_id'],
            'status' => 'OK',
            'parameters' => [
                [
                    'paramKey' => 'sender_card',
                    'paramValue' => $row['sender_card']
                ],
                [
                    'paramKey' => 'recipient_phone',
                    'paramValue' => $row['recipient_phone']
                ],
                [
                    'paramKey' => 'amount',
                    'paramValue' => $row['amount']
                ]
            ]
        ];

        return $transresp;
    }







    public function ResponseTransaction($error, $status, $pr = null)
    {
        $arr = [
            'timeStamp' => date('m/d/Y h:i:s a', time()),
            'errorMsg' => $error,
            'status' => $status,
            'parameters' => $pr,
            'providerTrnId' => 2
        ];
        return $arr;
    }
}
