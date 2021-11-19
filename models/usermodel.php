<?php

class User extends BaseClass
{
    public $username;
    public $password;


    public function __construct($password, $username)
    {
        $this->password = $password;
        $this->username = $username;
    }


    public function getUser($conn, $id)
    {
        $sql = "SELECT username, fullname, phone_num, password FROM users WHERE id = " . $id . "";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
            return $row;
        }

        return 1;
    }

    public function validate($conn)
    {
        if($this->password=="" || $this->username=="")
        {
            return [
                'error' => "empty password or username",
                'code' => 2
            ];
        }
        $this->password = hash("sha512",$this->password);
        
        // var_dump( $this->password);
        // exit();
        $sql = 'SELECT id FROM auth WHERE username = "' . $this->username . '" AND  password = "' . $this->password . '"';
        
        $result = $conn->query($sql);
        // var_dump($result->num_rows>0);
        // exit();
        if ($result->num_rows > 0) return true;

        return [
            'error' => " Wrong username or password",
            'code' => 1
        ];
        
    }


    public function getServiceById($conn, $id)
    {
        $sql = 'SELECT type FROM services WHERE id = "' . $id . '"';

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            return $row;
        }
        return 1;
    }
    public function validateCard($card)
    {
        $checkluhn = $this->Luhn($card);
        if (!$checkluhn) {
            return [
                'error' => "failed luhn verification",
                'code' => 2
            ];
        }

        if (strlen($card) == 5) {
            return 0;
        }
        return [
            'error' => "invalid card value",
            'code' => 1
        ];
    }

    public function validatePhone($phone)
    {

        $mobileregex = "/^[+]?[1-9][0-9]{11,11}$/";

        if (preg_match($mobileregex, $phone) == 1) {
            return 0;
        }
        return [
            'error' => "invalid phone number",
            'code' => -1
        ];
        
    }




    public function ResponseInform($error, $status, $pr = null)
    {
        $arr = [
            'timeStamp' => date('m/d/Y h:i:s a', time()),
            'errorMsg' => $error,
            'status' => $status,
            'parameters' => $pr
        ];
        return $arr;
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



    public function getBalanceAndFullNameById($conn, $id)
    {
        $sql = "SELECT u.fullname, c.amount FROM users u  INNER JOIN cards c ON u.card_id =c.owner_id WHERE u.id =" . $id . "";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();

            return $row;
        } else {
            return 1;
        }
    }


    public function getLimitBalanceByCardNUmber($conn, $card)
    {
        $sql = "SELECT max_limit, amount FROM cards  WHERE card_number =" . $card . "";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();

            return $row;
        } else {
            return 1;
        }
    }


    public function getLimitBalanceByPhone($conn, $phone)
    {
        $sql = 'SELECT c.max_limit, c.amount FROM cards c  INNER JOIN users u ON c.owner_id = u.card_id WHERE u.phone_num ="' . $phone . '"';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();

            return $row;
        } else {
            return 1;
        }
    }


    public function getAllById($conn, $id)
    {
        $sql = "SELECT 
    u.fullname,
    u.phone_num,
    c.card_number,
    c.max_limit,
    c.status,
     c.amount 
     FROM users u  INNER JOIN cards c ON u.card_id =c.owner_id WHERE u.id =" . $id . "";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();

            return $row;
        } else {
            return 1;
        }
    }
}
