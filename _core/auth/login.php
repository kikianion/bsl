<?php

/**
 * Created by PhpStorm.
 * User: super1
 * Date: 18/11/2016
 * Time: 23:10
 */
class Auth
{

    private $dbTable = "workers";

    function userLogin($username, $password)
    {
        $pdo_conn = (new DB)->getPDOConnection();

        $res['result'] = '';
        if ($username == null || $password == null || $username == '' or $password == '') {
            $res["result"] = "error";
            $res["msg"] = "some data empty";
            return $res;
        }

        try {
            $s = "select * from $this->dbTable where username=:username and pass=:pass limit 1";

            $st = $pdo_conn->prepare($s);
            $st->execute(array(
                'username' => $username,
                'pass' => $password,
            ));

            if ($st->rowCount() == 1) {
                $result = $st->fetchAll(PDO::FETCH_ASSOC);
                $level = $result[0]['level'];
                $userid = $result[0]['id'];
                $masterid = $result[0]['masterid'];

                //set sessions
                $_SESSION["user"] = $username;
                $_SESSION["level"] = $level;
                $_SESSION["userid"] = $userid;
                $_SESSION["masterid"] = $masterid;
                $_SESSION["name"] = $result[0]['name'];

                $res["result"] = "ok";
                $res["msg"] = "success";

            } else {
                $res["result"] = "error";
                $res["msg"] = "user or password false";
            }

        } catch (Exception $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
        }
        return $res;
    }
}