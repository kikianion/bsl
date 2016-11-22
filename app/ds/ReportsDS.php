<?php
/**
 * Created by PhpStorm.
 * User: super1
 * Date: 03/11/2016
 * Time: 11:44
 */

include "common/CommonDS.php";

class ReportsDS
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

    function getRepSubcat()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $month = $_REQUEST['month'];
        $year = $_REQUEST['year'];
        $sellerid = $_SESSION['user'];
        try {

            $s = "";
            if (strtolower($month) == 'semua') {
                $s = "
select tbl1.subcatprod,jmlvol,jmlrp,unit from
(select subcatprod,sum(vol) as jmlvol, sum(amount) as jmlrp
from trxworkers
where year(dt)=$year and sellerid='$sellerid'
group by subcatprod
order by subcatprod) tbl1
left join subcatproducts scp on scp.code=tbl1.subcatprod
";

            } else {
                $s = "
select tbl1.subcatprod,jmlvol,jmlrp,unit from
(select subcatprod,sum(vol) as jmlvol, sum(amount) as jmlrp
from trxworkers
where year(dt)=$year and month(dt)=$month and sellerid='$sellerid'
group by subcatprod
order by subcatprod) tbl1
left join subcatproducts scp on scp.code=tbl1.subcatprod
";
            }

            $stmt = $pdo_conn->prepare($s);
            $stmt->execute(array());
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $res["result"] = "ok";
            $res["data"] = $result;

            return $res;

        } catch (Exceotion $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;

        }
    }

    function getRepCitizen()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $month = $_REQUEST['month'];
        $year = $_REQUEST['year'];
        $sellerid = $_SESSION['user'];
        try {
//            $s = "
//select citizenid,subcatprod,sum(vol) as jmlvol, sum(amount) as jmlrp,count(*) as jmlrec
//
//from trxworkers
//
//where year(dt)=$year and month(dt)=$month and sellerid='$sellerid'
//
//group by citizenid,subcatprod
//order by citizenid,subcatprod
//";

            $s = "
        select citizenid,w.name as citizenname,subcatprod, scp.name as subcatprodname,jmlvol,unit, jmlrp, jmlrec
from
(select citizenid,subcatprod,sum(vol) as jmlvol,sum(amount) as jmlrp,count(*) as jmlrec
from trxworkers
where year(dt)=$year and month(dt)=$month and sellerid='$sellerid'
group by citizenid,subcatprod
order by citizenid,subcatprod) tbl1
left join subcatproducts scp on scp.code=tbl1.subcatprod
left join workers w on w.masterid=tbl1.citizenid
        ";

            $stmt = $pdo_conn->prepare($s);
            $stmt->execute(array());
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $res["result"] = "ok";
            $res["data"] = $result;

            return $res;

        } catch (Exceotion $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;

        }
    }

    function getAdminRepSeller()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $month = $_REQUEST['month'];
        $year = $_REQUEST['year'];
        try {
            $s = "
        select citizenid,w.name as citizenname,subcatprod, scp.name as subcatprodname,jmlvol,unit, jmlrp, jmlrec
from
(select citizenid,subcatprod,sum(vol) as jmlvol,sum(amount) as jmlrp,count(*) as jmlrec
from trxworkers
where year(dt)=$year and month(dt)=$month
group by citizenid,subcatprod
order by citizenid,subcatprod) tbl1
left join subcatproducts scp on scp.code=tbl1.subcatprod
left join workers w on w.masterid=tbl1.citizenid
        ";

            $s = "
        select sellerid,w.name as sellername,subcatprod, scp.name as subcatprodname,jmlvol,unit, jmlrp, jmlrec
from

(select sellerid,subcatprod,sum(vol) as jmlvol,sum(amount) as jmlrp,count(*) as jmlrec
from trxworkers

where year(dt)=$year and month(dt)=$month

group by sellerid,subcatprod

order by sellerid,subcatprod) tbl1

left join subcatproducts scp on scp.code=tbl1.subcatprod
left join workers w on w.username=tbl1.sellerid

        ";
            $stmt = $pdo_conn->prepare($s);
            $stmt->execute(array());
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $res["result"] = "ok";
            $res["data"] = $result;

            return $res;

        } catch (Exceotion $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;

        }
    }

    function repAdmin_booking_load()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $month = $_REQUEST['month'];
        $year = $_REQUEST['year'];
        $sellerid = $_SESSION['user'];
        try {

            $s = "";
            if (strtolower($month) == 'semua') {
                $s = "
        select * from trxworkers
        where year(dt)=$year
        order by id";

            } else {
                $s = "
        select * from trxworkers
        where year(dt)=$year and month(dt)=$month
        order by id";

            }

            $stmt = $pdo_conn->prepare($s);
            $stmt->execute(array());
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $res["result"] = "ok";
            $res["data"] = $result;

            return $res;
        } catch (Exceotion $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;
        }
    }

    function getTrxBooking()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $month = $_REQUEST['month'];
        $year = $_REQUEST['year'];
        $sellerid = $_SESSION['user'];
        try {

            $s = "";
            if (strtolower($month) == 'semua') {
                $s = "
        select * from trxworkers
        where year(dt)=$year and sellerid='$sellerid'
        order by id";

            } else {
                $s = "
        select * from trxworkers
        where year(dt)=$year and month(dt)=$month and sellerid='$sellerid'
        order by id";
            }
            $stmt = $pdo_conn->prepare($s);
            $stmt->execute(array());
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $res["result"] = "ok";
            $res["data"] = $result;

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
////-----------
//
//function putTrx($trx)
//{
//    global $pdo_conn;
//
//    $dkBySeller = ($trx == '-1' ? "K1" : "D1");
//
//    $citizenid = $_REQUEST['custmasterid'];
//    $vol = $_REQUEST['vol'];
//    $subcatprod = $_REQUEST['subcatprod'];
//    $ppu = $_REQUEST['ppu'];
//
//    try {
//
//        $s = "select masterid from workers where id=" . $citizenid;
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute();
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $masterId = "-1";
//        if (count($result) == 1) {
//            $masterId = $result[0]['masterid'];
//        } else {
//            $res["result"] = "error";
//            $res["msg"] = "citizens not exist";
//            return $res;
//        }
//
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
//
////        $s = "insert into trx(dkbyseller,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance)
////values(:dkbyseller, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance)";
////
////        $amount = doubleval($vol) * doubleval($trx) * doubleval($ppu);
////        $balance = $amount + $lastBalance;
////
////        $st = $pdo_conn->prepare($s);
////        $st->execute(array(
////            'dkbyseller' => $dkBySeller,
////            'amount' => $amount,
////            'citizenid' => $masterId,
////            'sellerid' => $_SESSION['user'],
////            'vol' => $vol,
////            'subcatprod' => $subcatprod,
////            'priceperunit' => $ppu,
////            'balance' => $balance,
////        ));
//
//
//        //trx by worker
//        $s = "select balance from trxworkers where citizenid=:id order by dt desc limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $masterId,
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $lastBalanceWorker = 0.0;
//        if (count($result) == 1) {
//            $lastBalanceWorker = doubleval($result[0]['balance']);
//        }
//
//        $dkByWorker = ($trx == '-1' ? "D1" : "K1");
//
//        $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid,vol,subcatprod,priceperunit, balance)
//values(:dkbyworker, :amount, :citizenid,:sellerid,:vol,:subcatprod,:priceperunit ,:balance)";
//
//        $amount = doubleval($vol) * doubleval($trx) * -1 * doubleval($ppu);
//        $balance = $amount + $lastBalanceWorker;
//
//        $st = $pdo_conn->prepare($s);
//        $st->execute(array(
//            'dkbyworker' => $dkByWorker,
//            'amount' => $amount,
//            'citizenid' => $masterId,
//            'sellerid' => $_SESSION['user'],
//            'vol' => $vol,
//            'subcatprod' => $subcatprod,
//            'priceperunit' => $ppu,
//            'balance' => $balance,
//        ));
//
//        $res["result"] = "ok";
//        $res["affected"] = $st->rowCount();
//
//        $res["msg"] = "affected: " . $st->rowCount();
//        return $res;
//    } catch (Exception $e) {
//        $res["result"] = "error";
//        $res["msg"] = $e->getMessage();
//        return $res;
//    }
//
//}
//
//function takeTrx($trx)
//{
//    global $pdo_conn;
//
//    $dkBySeller = ($trx == '-1' ? "K1" : "D1");
//
//    $citizenid = $_REQUEST['custmasterid'];
//    $amountTotal = $_REQUEST['amountTotal'];
//
//    try {
//
//        $s = "select masterid from workers where id=" . $citizenid;
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute();
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $masterId = "-1";
//        if (count($result) == 1) {
//            $masterId = $result[0]['masterid'];
//        } else {
//            $res["result"] = "error";
//            $res["msg"] = "citizens not exist";
//            return $res;
//        }
//
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
//
////        $s = "insert into trx(dkbyseller,amount,citizenid,sellerid, balance)
////values(:dkbyseller, :amount, :citizenid,:sellerid,:balance)";
////
////        $amount = doubleval($amountTotal);
////        $balance = $lastBalance + (doubleval($trx) * $amount);
////
////        $st = $pdo_conn->prepare($s);
////        $st->execute(array(
////            'dkbyseller' => $dkBySeller,
////            'amount' => $amount,
////            'citizenid' => $masterId,
////            'sellerid' => $_SESSION['user'],
////            'balance' => $balance,
////        ));
//
//
//        //trx by worker
//        $s = "select balance from trxworkers where citizenid=:id order by dt desc limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $masterId,
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $lastBalanceWorker = 0.0;
//        if (count($result) == 1) {
//            $lastBalanceWorker = doubleval($result[0]['balance']);
//        }
//
//        $dkByWorker = ($trx == '-1' ? "D1" : "K1");
//
//        $s = "insert into trxworkers(dkbyworker,amount,citizenid,sellerid, balance)
//values(:dkbyworker, :amount, :citizenid,:sellerid,:balance)";
//
//        $amount = doubleval($amountTotal) * -1;
//        $balance = $lastBalanceWorker + (doubleval($trx) * $amount);
//
//        $st = $pdo_conn->prepare($s);
//        $st->execute(array(
//            'dkbyworker' => $dkByWorker,
//            'amount' => $amount,
//            'citizenid' => $masterId,
//            'sellerid' => $_SESSION['user'],
//            'balance' => $balance,
//        ));
//
//        $res["result"] = "ok";
//        $res["affected"] = $st->rowCount();
//
//        $res["msg"] = "affected: " . $st->rowCount();
//        return $res;
//    } catch (Exception $e) {
//        $res["result"] = "error";
//        $res["msg"] = $e->getMessage();
//        return $res;
//    }
//
//}
//
//function saveTrx()
//{
//    $trx = $_REQUEST['trx'];
//
//    if ($trx == '-1') {
//        return putTrx($trx);
//    } else if ($trx == '1') {
//        return takeTrx($trx);
//    }
//}


//function loadUnprintedTrxs()
//{
//    global $pdo_conn;
//
//    $id = $_REQUEST['id'];
//    try {
//
//        $s = "select masterid from workers where id=:id limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $id,
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $masterId = "-1";
//        if (count($result) == 1) {
//            $masterId = $result[0]['masterid'];
//        }
//
//        $s = "select * from trxworkers where citizenid=:id and printed=0 and sellerid=:sellerid order by dt asc ";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $masterId,
//            "sellerid" => $_SESSION['user'],
//        ));
//        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        $s = "select printed from trxworkers where citizenid=:id and printed<>0 and sellerid=:sellerid order by printed desc limit 1 ";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $masterId,
//            "sellerid" => $_SESSION['user'],
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $printedLast = "0";
//        if (count($result) == 1) {
//            $printedLast = $result[0]['printed'];
//        }
//
//        $res["result"] = "ok";
//        $res["data"] = $data;
//        $res["printedLast"] = $printedLast;
//
//        return $res;
//
//    } catch (Exceotion $e) {
//        $res["result"] = "error";
//        $res["msg"] = $e->getMessage();
//        return $res;
//
//    }
//
//}


//function getWorkerBalance()
//{
//    global $pdo_conn;
//
//    $id = $_REQUEST['id'];
//    try {
//
//        $s = "select masterid from workers where id=:id limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $id,
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $masterId = "-1";
//        if (count($result) == 1) {
//            $masterId = $result[0]['masterid'];
//        }
//
//        $s = "select balance from trxworkers where citizenid=:id order by dt desc limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $masterId,
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $lastBalanceWorker = 0.0;
//        if (count($result) == 1) {
//            $lastBalanceWorker = doubleval($result[0]['balance']);
//        }
//        $res["result"] = "ok";
//        $res["balance"] = $lastBalanceWorker;
//
//        return $res;
//
//    } catch (Exceotion $e) {
//        $res["result"] = "error";
//        $res["msg"] = $e->getMessage();
//        return $res;
//    }
//}
//
//function updateTrxWorkersPrinted()
//{
//    global $pdo_conn;
//
//    $data = $_REQUEST['data'];
//    $id = $_REQUEST['id'];
//
//    try {
//        $data_arr = json_decode($data);
//
//        $s = "select masterid from workers where id=:id limit 1";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $id,
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $masterId = "-1";
//        if (count($result) == 1) {
//            $masterId = $result[0]['masterid'];
//        }
//
//        $s = "select printed from trxworkers where citizenid=:id and printed<>0 and sellerid=:sellerid order by printed desc limit 1 ";
//
//        $stmt = $pdo_conn->prepare($s);
//        $stmt->execute(array(
//            "id" => $masterId,
//            "sellerid" => $_SESSION['user'],
//        ));
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $printedLast = "0";
//        if (count($result) == 1) {
//            $printedLast = $result[0]['printed'];
//        }
//
//
//        $serial = intval($printedLast);
//        for ($i = 0; $i < count($data_arr); $i++) {
//            $id = $data_arr[$i];
//            $s = "update trxworkers set printed=:serial where id=" . $id;
//            $stmt = $pdo_conn->prepare($s);
//
//            $serial++;
//            $stmt->execute(array(
//                "serial" => $serial,
//            ));
//        }
//
//        $res["result"] = "ok";
////        $res["balance"] = $lastBalanceWorker;
//
//        return $res;
//
//    } catch (Exception $e) {
//        $res["result"] = "error";
//        $res["msg"] = $e->getMessage();
//        return $res;
//
//    }
//
//}


?>