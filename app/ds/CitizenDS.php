<?php

include_once "common/CommonDS.php";

class CitizenDS extends CommonDS
{
    public $table;
    public $colMaps;

    public $orderby;
    public $strfilter;

    public function __construct()
    {
        $this->table = "workers";
        $this->colMaps = array(
            0 => '-',
            1 => 'head',
            2 => 'level',
            3 => 'masterid',
            4 => 'name',
            5 => 'username',
            6 => 'pass',
            7 => 'address',
            8 => 'phone',
        );

        $this->orderby = "id";
        $this->strfilter = "";

        $this->strfilter = " where head=" . $_SESSION['userid'] . " ";
    }

    public function  callback_save1()
    {
        // TODO: Implement callback_save1() method.
    }

    public function callback_perchange_aftersave1($lastid, $save_info)
    {
        $pdo_conn = (new DB())->getPDOConnection();

        try {
            if ($save_info['type'] == 'insert') {

                $defaultLevel = "-1";
                $defaultHead = "-1";
                if ($_SESSION['level'] == 'admin') {
                    $defaultLevel = "unit";

                } else if ($_SESSION['level'] == 'unit') {
                    $defaultLevel = "warga";
                }

                //gen masterid
                $s = "select * from $this->table where head=" . $_SESSION['userid'] . " order by masterid desc limit 1";
                $stmt = $pdo_conn->prepare($s);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $lastMasterId = "";
                $lastMasterId_1 = "";
                if (count($result) == 1) {
                    $lastMasterId = $result[0]['masterid'];
                    $idxsep = strpos($lastMasterId, '-');
                    if ($idxsep !== false && $_SESSION['level'] == 'unit') {
                        $numlast = substr($lastMasterId, $idxsep + 1);
                    } else if ($idxsep === false && $_SESSION['level'] == 'admin') {
                        $numlast = substr($lastMasterId, 1);
                    } else {
                        $res["result"] = "error";
                        $res["msg"] = "assert default value on citizen insert failed";
                        return $res;
                    }
                    $newNum = intval($numlast) + 1;
                    if ($_SESSION['level'] == 'unit') {
                        $strNewNum = str_pad($newNum, 5, '0', STR_PAD_LEFT);
                    } else if ($_SESSION['level'] == 'admin') {
                        $strNewNum = str_pad($newNum, 4, '0', STR_PAD_LEFT);
                    }
                    if ($_SESSION['level'] == 'unit') {
                        $lastMasterId_1 = $_SESSION['masterid'] . '-' . $strNewNum;
                    } else if ($_SESSION['level'] == 'admin') {
                        $lastMasterId_1 = 'U' . $strNewNum;
                    }
                } else {
                    if ($_SESSION['level'] == 'unit') {
                        $lastMasterId_1 = $_SESSION['masterid'] . '-00001';

                    } else if ($_SESSION['level'] == 'admin') {
                        $lastMasterId_1 = 'U0001';
                    }
                }


                //cek apakah sudha ada user dengan master id, assert
                $s = "select * from $this->table where masterid='$lastMasterId_1' ";
                $st = $pdo_conn->prepare($s);
                $st->execute();
                $result = $st->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {

                    $s = "delete from $this->table where id=$lastid ";
                    $st = $pdo_conn->prepare($s);
                    $st->execute(array());

                    $res["result"] = "error";
                    $res["msg"] = "Ada record dengan nomor induk yang sama, cek integritas data terlebih dulu, hubungi admin";
                    return $res;
                }

                //updateing masterid
                $s = "update $this->table set head=:head, level=:level,masterid=:newnum where id=" . $lastid;

                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'head' => $_SESSION['userid'],
                    'newnum' => $lastMasterId_1,
                    'level' => $defaultLevel,
                ));

                $res["result"] = "ok";
                $res["affected"] = $st->rowCount();

                $res["msg"] = "affected: " . $st->rowCount();
                return $res;
            }
        } catch (Exception $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;
        }
    }

    public function callback_aftersave1()
    {
        // TODO: Implement callback_aftersave1() method.
    }

    public function callback_perchange_beforesave1()
    {
        // TODO: Implement callback_perchange_beforesave1() method.
    }

    public function callback_beforedelete1()
    {
        // TODO: Implement callback_beforedelete1() method.
    }
}


?>