<?php

include "common/CommonDS.php";

class usermgmtDS
{
    public function start()
    {
        @$f = $_REQUEST["f"];
        if (method_exists($this, $f)) {
            echo json_encode($this->$f());
        } else {
            echo '{"result":"function not exist"}';
        }
    }

    function chpass_save()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $user = $_SESSION['user'];
        $passOld = $_REQUEST['lama'];
        $passNew = $_REQUEST['baru'];

        $s = "update workers set pass=:passnew where username=:username and pass=:passold";
        $st = $pdo_conn->prepare($s);
        $st->execute(array(
            'username' => $user,
            'passold' => $passOld,
            'passnew' => $passNew,
        ));

        $res["result"] = "ok";
        $res["affected"] = $st->rowCount();

        $res["msg"] = "affected: " . $st->rowCount();
        return $res;
    }
}

?>