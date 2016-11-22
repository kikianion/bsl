<?php
/**
 * Created by PhpStorm.
 * User: super1
 * Date: 03/11/2016
 * Time: 11:44
 */


include "common/CommonDS.php";

$table = "trx";
$colMap = array(
    0 => '-',
    1 => 'masterid',
    2 => 'name',
    3 => 'address',
    4 => 'unithead',
    5 => 'na',
);

$orderby = "id";
$strfilter = " where head=" . $_SESSION['userid'] . " ";

$callback_perchange_save1 = "defaultVal";

//param
@$f = $_REQUEST["f"];

//main
if (function_exists($f)) {
    echo json_encode($f());
} else {
    echo '{"result":"function not exist"}';
}

//-----------

function setUser()
{

    global $pdo_conn;
    $user = $_REQUEST['user'];
    try {
        $s = "select * from workers where username=:username limit 1";

        $st = $pdo_conn->prepare($s);
        $st->execute(array(
            'username' => $user,

        ));

        if ($st->rowCount() == 1) {
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $level = $result[0]['level'];
            $userid = $result[0]['id'];
            $masterid = $result[0]['masterid'];

            $_SESSION["user"] = $user;
            $_SESSION["level"] = $level;
            $_SESSION["userid"] = $userid;
            $_SESSION["masterid"] = $masterid;
            $_SESSION["name"] = $result[0]['name'];
            $res["result"] = "ok";
            $res["msg"] = "success";
            return $res;
        } else {
            $res["result"] = "error";
            $res["msg"] = "user atau password salah";
            return $res;
        }

    } catch (Exception $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;
    }

}

function setNewBal()
{
    global $pdo_conn;
    $idRow = $_REQUEST['idRow'];
    $newbal = $_REQUEST['newbal'];

    try {
        $s = "update trxworkers set balance=:newbal where id=:idrow";

        $st = $pdo_conn->prepare($s);
        $st->execute(array(
            'newbal' => $newbal,
            'idrow' => $idRow,

        ));

        $res["result"] = "ok";
        $res["msg"] = "ok";
        return $res;

    } catch (Exception $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;
    }

}

function putTrx()
{
    global $pdo_conn;

//    if ($trx == "-1") {
//        $dkBySeller = "K1";
//    } else if ($trx == "1") {
//        $dkBySeller = "D1";
//    } else if ($trx == "2") {
//        $dkBySeller = "K1";
//    }
//    $dkBySeller = ($trx == '-1' ? "K1" : "D1");

    $citizenid = $_REQUEST['custmasterid'];
    $vol = $_REQUEST['vol'];
    $subcatprod = $_REQUEST['subcatprod'];
    $ppu = $_REQUEST['ppu'];
    $dt = $_REQUEST['dt'];

    try {

        $s = "select masterid from workers where id=" . $citizenid;

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $masterId = "-1";
        if (count($result) == 1) {
            $masterId = $result[0]['masterid'];
        } else {
            $res["result"] = "error";
            $res["msg"] = "citizens not exist";
            return $res;
        }

//        $s = "select balance from trx where sellerid=:id order by dt desc limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $_SESSION['user'],
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $lastBalance = 0.0;
//        if (count($result) == 1) {
//            $lastBalance = doubleval($result[0]['balance']);
//        }

//        $s = "insert into trx(dkbyseller,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance)
//values(:dkbyseller, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance)";
//
//        $amount = doubleval($vol) * doubleval($trx) * doubleval($ppu);
//        $balance = $amount + $lastBalance;
//
//        $st = $pdo_conn->prepare($s);
//        $st->execute(array(
//            'dkbyseller' => $dkBySeller,
//            'amount' => $amount,
//            'citizenid' => $masterId,
//            'sellerid' => $_SESSION['user'],
//            'vol' => $vol,
//            'subcatprod' => $subcatprod,
//            'priceperunit' => $ppu,
//            'balance' => $balance,
//        ));


        //trx by worker
        $s = "select balance from trxworkers where citizenid=:id order by id desc limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastBalanceWorker = 0.0;
        if (count($result) == 1) {
            $lastBalanceWorker = doubleval($result[0]['balance']);
        }

//        $dkByWorker = ($trx == '-1' ? "D1" : "K1");
//        if ($trx == "-1") {
//            $dkByWorker = "D1";
//        } else if ($trx == "1") {
//            $dkByWorker = "K1";
//        } else if ($trx == "2") {
//            $dkByWorker = "D2";
//            $trx = "-1";
//
//        }

        $dkByWorker = "D1";

        //sing one plus for flag further coding
        $amount = doubleval($vol) * 1 * doubleval($ppu);
        $balance = $amount + $lastBalanceWorker;
        $s = "";
        $param = "";
        if ($dt == date("Y-m-d")) {
            $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance)
values(:dkbyworker, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance)";
            $param = array(
                'dkbyworker' => $dkByWorker,
                'amount' => $amount,
                'citizenid' => $masterId,
                'sellerid' => $_SESSION['user'],
                'vol' => $vol,
                'subcatprod' => $subcatprod,
                'priceperunit' => $ppu,
                'balance' => $balance,
            );
        } else {
            $dt .= date(" H:i:s");
            $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance,dt)
values(:dkbyworker, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance,:dt)";
            $param = array(
                'dkbyworker' => $dkByWorker,
                'amount' => $amount,
                'citizenid' => $masterId,
                'sellerid' => $_SESSION['user'],
                'vol' => $vol,
                'subcatprod' => $subcatprod,
                'priceperunit' => $ppu,
                'balance' => $balance,
                'dt' => $dt,
            );
        }
        $st = $pdo_conn->prepare($s);

        $st->execute($param);

        $res["result"] = "ok";
        $res["affected"] = $st->rowCount();

        $res["msg"] = "affected: " . $st->rowCount();
        return $res;
    } catch (Exception $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;
    }
}

function takeTrx()
{
    global $pdo_conn;

//    $dkBySeller = ($trx == '-1' ? "K1" : "D1");

    $citizenid = $_REQUEST['custmasterid'];
    $amountTotal = $_REQUEST['amountTotal'];

    try {

        $s = "select masterid from workers where id=" . $citizenid;

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $masterId = "-1";
        if (count($result) == 1) {
            $masterId = $result[0]['masterid'];
        } else {
            $res["result"] = "error";
            $res["msg"] = "citizens not exist";
            return $res;
        }

        $s = "select balance from trx where sellerid=:id order by dt desc limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $_SESSION['user'],
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastBalance = 0.0;
        if (count($result) == 1) {
            $lastBalance = doubleval($result[0]['balance']);
        }

//        $s = "insert into trx(dkbyseller,amount,citizenid,sellerid, balance)
//values(:dkbyseller, :amount, :citizenid,:sellerid,:balance)";
//
//        $amount = doubleval($amountTotal);
//        $balance = $lastBalance + (doubleval($trx) * $amount);
//
//        $st = $pdo_conn->prepare($s);
//        $st->execute(array(
//            'dkbyseller' => $dkBySeller,
//            'amount' => $amount,
//            'citizenid' => $masterId,
//            'sellerid' => $_SESSION['user'],
//            'balance' => $balance,
//        ));


        //trx by worker
        $s = "select balance from trxworkers where citizenid=:id order by dt desc limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastBalanceWorker = 0.0;
        if (count($result) == 1) {
            $lastBalanceWorker = doubleval($result[0]['balance']);
        }

//        $dkByWorker = ($trx == '-1' ? "D1" : "K1");
        $dkByWorker = "K1";

//        $amount = doubleval($amountTotal) * -1;
        $amount = doubleval($amountTotal);
//        $balance = $lastBalanceWorker + (doubleval($trx) * $amount);
        $balance = $lastBalanceWorker - $amount;

        $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance)
values(:dkbyworker, :amount, :citizenid,:sellerid,:balance)";


        $st = $pdo_conn->prepare($s);
        $st->execute(array(
            'dkbyworker' => $dkByWorker,
            'amount' => $amount,
            'citizenid' => $masterId,
            'sellerid' => $_SESSION['user'],
            'balance' => $balance,
        ));

        $res["result"] = "ok";
        $res["affected"] = $st->rowCount();

        $res["msg"] = "affected: " . $st->rowCount();
        return $res;
    } catch (Exception $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;
    }

}

function depositTrx()
{
    global $pdo_conn;

    $citizenid = $_REQUEST['custmasterid'];
    $amountTotal = $_REQUEST['amountTotal'];

    try {
        $s = "select masterid from workers where id=" . $citizenid;

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $masterId = "-1";
        if (count($result) == 1) {
            $masterId = $result[0]['masterid'];
        } else {
            $res["result"] = "error";
            $res["msg"] = "citizens not exist";
            return $res;
        }

        $s = "select balance from trx where sellerid=:id order by dt desc limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $_SESSION['user'],
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastBalance = 0.0;
        if (count($result) == 1) {
            $lastBalance = doubleval($result[0]['balance']);
        }

        //trx by worker
        $s = "select balance from trxworkers where citizenid=:id order by dt desc limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastBalanceWorker = 0.0;
        if (count($result) == 1) {
            $lastBalanceWorker = doubleval($result[0]['balance']);
        }

        $dkByWorker = "D2";

        $amount = doubleval($amountTotal);
        $balance = $lastBalanceWorker + $amount;

        $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance)
values(:dkbyworker, :amount, :citizenid,:sellerid,:balance)";


        $st = $pdo_conn->prepare($s);
        $st->execute(array(
            'dkbyworker' => $dkByWorker,
            'amount' => $amount,
            'citizenid' => $masterId,
            'sellerid' => $_SESSION['user'],
            'balance' => $balance,
        ));

        $res["result"] = "ok";
        $res["affected"] = $st->rowCount();

        $res["msg"] = "affected: " . $st->rowCount();
        return $res;
    } catch (Exception $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;
    }

}

function saveTrx()
{
    $trx = $_REQUEST['trx'];

    if ($trx == '-1') {
        return putTrx();
    } else if ($trx == '1') {
        return takeTrx();
    } else if ($trx == '2') {
        return depositTrx();
    }
}


function loadUnprintedTrxs()
{
    global $pdo_conn;

    $id = $_REQUEST['id'];
    try {

        $s = "select masterid from workers where id=:id limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $id,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $masterId = "-1";
        if (count($result) == 1) {
            $masterId = $result[0]['masterid'];
        }

        $s = "select * from trxworkers where citizenid=:id and printed=0 and sellerid=:sellerid order by dt asc ";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
            "sellerid" => $_SESSION['user'],
        ));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $s = "select printed from trxworkers where citizenid=:id and printed<>0 and sellerid=:sellerid order by printed desc limit 1 ";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
            "sellerid" => $_SESSION['user'],
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $printedLast = "0";
        if (count($result) == 1) {
            $printedLast = $result[0]['printed'];
        }

        $res["result"] = "ok";
        $res["data"] = $data;
        $res["printedLast"] = $printedLast;

        return $res;

    } catch (Exceotion $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;

    }

}

function getWorkerBalance()
{
    global $pdo_conn;

    $id = $_REQUEST['id'];
    try {

        $s = "select masterid from workers where id=:id limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $id,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $masterId = "-1";
        if (count($result) == 1) {
            $masterId = $result[0]['masterid'];
        }

        $s = "select balance from trxworkers where citizenid=:id order by dt desc limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastBalanceWorker = 0.0;
        if (count($result) == 1) {
            $lastBalanceWorker = doubleval($result[0]['balance']);
        }
        $res["result"] = "ok";
        $res["balance"] = $lastBalanceWorker;

        return $res;

    } catch (Exceotion $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;

    }


}

function updateTrxWorkersPrinted()
{
    global $pdo_conn;

    $data = $_REQUEST['data'];
    $id = $_REQUEST['id'];

    try {
        $data_arr = json_decode($data);

        $s = "select masterid from workers where id=:id limit 1";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $id,
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $masterId = "-1";
        if (count($result) == 1) {
            $masterId = $result[0]['masterid'];
        }

        $s = "select printed from trxworkers where citizenid=:id and printed<>0 and sellerid=:sellerid order by printed desc limit 1 ";

        $stmt = $pdo_conn->prepare($s);
        $stmt->execute(array(
            "id" => $masterId,
            "sellerid" => $_SESSION['user'],
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $printedLast = "0";
        if (count($result) == 1) {
            $printedLast = $result[0]['printed'];
        }


        $serial = intval($printedLast);
        for ($i = 0; $i < count($data_arr); $i++) {
            $id = $data_arr[$i];
            $s = "update trxworkers set printed=:serial where id=" . $id;
            $stmt = $pdo_conn->prepare($s);

            $serial++;
            $stmt->execute(array(
                "serial" => $serial,
            ));
        }

        $res["result"] = "ok";
//        $res["balance"] = $lastBalanceWorker;

        return $res;

    } catch (Exceotion $e) {
        $res["result"] = "error";
        $res["msg"] = $e->getMessage();
        return $res;

    }

}