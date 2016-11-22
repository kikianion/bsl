<?php
session_start();
include_once "../config/db.php";

function _rand()
{
    global $pdo_conn;
    try {

        $s = "select * from trxworkers where id>1869";

        $st = $pdo_conn->prepare($s);
        $st->execute();

        $rs = $st->fetchAll(PDO::FETCH_ASSOC);

        $dtPick = array(
            '2016-09-12 08:14',
            '2016-10-11 08:24',
            '2016-11-14 08:34',
        );

        $it = 0;
        for ($i = 0; $i < count($rs); $i++) {
            $s = "update trxworkers set dt='" . $dtPick[$it] . "' where id=" . $rs[$i]["id"];

            $st = $pdo_conn->prepare($s);
            $st->execute();
            $it++;
            if ($it > 2) $it = 0;
            echo($i."-");
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


_rand();
?>