<?php
/**
 * Created by PhpStorm.
 * User: super1
 * Date: 03/11/2016
 * Time: 11:44
 */

include "common/CommonDS.php";

class TrxDS
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

    public function putTrx($code)
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $citizenid = $_REQUEST['custmasterid'];
        $vol = $_REQUEST['vol'];
        $subcatprod = $_REQUEST['subcatprod'];
        $ppu = $_REQUEST['ppu'];
        $dt = $_REQUEST['dt'];
        $ket = "";
        if ($code == "D1") {

        } else if ($code = "D9") {
            $ket = $_REQUEST['ket'];
        }

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

            $dkByWorker = $code;

            //sing one plus for flag further coding
            $amount = doubleval($vol) * 1 * doubleval($ppu);
            $balance = $amount + $lastBalanceWorker;
            $s = "";
            $param = "";
            if ($dt == date("Y-m-d")) {
                $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance, ket)
values(:dkbyworker, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance, :ket)";
                $param = array(
                    'dkbyworker' => $dkByWorker,
                    'amount' => $amount,
                    'citizenid' => $masterId,
                    'sellerid' => $_SESSION['user'],
                    'vol' => $vol,
                    'subcatprod' => $subcatprod,
                    'priceperunit' => $ppu,
                    'balance' => $balance,
                    'ket' => $ket,
                );
            } else {
                $dt .= date(" H:i:s");
                $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance,dt,ket)
values(:dkbyworker, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance,:dt,:ket)";
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
                    'ket' => $ket,
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

    function takeTrx($code)
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $citizenid = $_REQUEST['custmasterid'];
        $amountTotal = $_REQUEST['amountTotal'];
        $dt = $_REQUEST['dt'];

        $ket = "";
        if ($code == "K1") {

        } else if ($code == "K9") {
            $ket = $_REQUEST['ket'];
        }

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

            $dkByWorker = $code;

            $amount = -doubleval($amountTotal);
            $balance = $lastBalanceWorker + $amount;

            if ($dt == date("Y-m-d")) {
                $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance, ket)
values(:dkbyworker, :amount, :citizenid,:sellerid,:balance, :ket)";
                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'dkbyworker' => $dkByWorker,
                    'amount' => $amount,
                    'citizenid' => $masterId,
                    'sellerid' => $_SESSION['user'],
                    'balance' => $balance,
                    'ket' => $ket,
                ));
            } else {
                $dt .= date(" H:i:s");
                $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance, ket, dt)
values(:dkbyworker, :amount, :citizenid,:sellerid,:balance, :ket, :dt)";
                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'dkbyworker' => $dkByWorker,
                    'amount' => $amount,
                    'citizenid' => $masterId,
                    'sellerid' => $_SESSION['user'],
                    'balance' => $balance,
                    'ket' => $ket,
                    'dt' => $dt,
                ));
            }

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

    function depositTrx($code)
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $citizenid = $_REQUEST['custmasterid'];
        $amountTotal = $_REQUEST['amountTotal'];
        $dt = $_REQUEST['dt'];

        $ket = "";
        if ($code == 'D9') {
            $ket = $_REQUEST['ket'];
        }

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

            $dkByWorker = $code;

            $amount = doubleval($amountTotal);
            $balance = $lastBalanceWorker + $amount;

            if ($dt == date("Y-m-d")) {
                $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance,ket)
values(:dkbyworker, :amount, :citizenid,:sellerid,:balance,:ket)";

                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'dkbyworker' => $dkByWorker,
                    'amount' => $amount,
                    'citizenid' => $masterId,
                    'sellerid' => $_SESSION['user'],
                    'balance' => $balance,
                    'ket' => $ket,
                ));
            } else {
                $dt .= date(" H:i:s");
                $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance,ket,dt)
values(:dkbyworker, :amount, :citizenid,:sellerid,:balance,:ket,:dt)";

                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'dkbyworker' => $dkByWorker,
                    'amount' => $amount,
                    'citizenid' => $masterId,
                    'sellerid' => $_SESSION['user'],
                    'balance' => $balance,
                    'ket' => $ket,
                    'dt' => $dt,
                ));
            }
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

    public function saveTrx()
    {
        $trx = $_REQUEST['trx'];

        if ($trx == '-1') {
            return $this->putTrx("D1");
        } else if ($trx == '1') {
            return $this->takeTrx("K1");
        } else if ($trx == '2') {
            return $this->depositTrx("D2");
        } else if ($trx == '3') {
            return $this->depositTrx("D9");
        } else if ($trx == '4') {
            return $this->takeTrx("K9");
        }
    }


    function loadUnprintedTrxs()
    {
        $pdo_conn = (new DB())->getPDOConnection();

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

            $s = "select * from trxworkers where citizenid=:id and printed=0 and sellerid=:sellerid order by id asc ";

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
        $pdo_conn = (new DB())->getPDOConnection();

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
        $pdo_conn = (new DB())->getPDOConnection();

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
}

//$table = "trx";
//$colMap = array(
//    0 => '-',
//    1 => 'masterid',
//    2 => 'name',
//    3 => 'address',
//    4 => 'unithead',
//    5 => 'na',
//);
//
//$orderby = "id";
//$strfilter = " where head=" . $_SESSION['userid'] . " ";
//
//$callback_perchange_save1 = "defaultVal";
//
////param
//@$f = $_REQUEST["f"];
//
////main
//if (function_exists($f)) {
//    echo json_encode($f());
//} else {
//    echo '{"result":"function not exist"}';
//}
//


?>