<?php

include "common/CommonDS.php";

class MyPriceDS extends CommonDS{
    public $table;
    public $colMaps;

    public $orderby;
    public $strfilter;

    public function __construct()
    {
        $this->table = "myprice";
        $this->colMaps = array(
            0 => '-',
            1 => 'subcatprod',
            2 => 'prodname',
            3 => 'price',
        );

        $this->orderby = "id";
        $this->strfilter = "";

        $this->strfilter = " where buyerid=" . $_SESSION['userid'] . " ";
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
                //update name subcat
                $s = "select subcatprod from $this->table where id=" . $lastid;
                $st = $pdo_conn->prepare($s);
                $st->execute(array());
                $result = $st->fetchAll(PDO::FETCH_ASSOC);
                $subcatprod = "-1";
                if (count($result) == 1) {
                    $subcatprod = $result[0]['subcatprod'];
                }

                //update name subcat
                $s = "select name from subcatproducts where code=:code limit 1 ";

                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'code' => $subcatprod,
                ));
                $result = $st->fetchAll(PDO::FETCH_ASSOC);
                $prodname = "-1";
                if (count($result) == 1) {
                    $prodname = $result[0]['name'];
                }

                $s = "update $this->table set buyerid=:buyerid,prodname=:prodname where id=" . $lastid;

                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'buyerid' => $_SESSION['userid'],
                    'prodname' => $prodname,
                ));

                $res["result"] = "ok";
                $res["affected"] = $st->rowCount();

                $res["msg"] = "affected: " . $st->rowCount();
                return $res;
            }

        } catch (Exception $e) {

            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                try {
                    $s = "delete from $this->table where id=" . $lastid;
                    $st = $pdo_conn->prepare($s);
                    $st->execute(array());

                } catch (Exception $e) {
                    $res["result"] = "error";
                    $res["msg"] = $e->getMessage();
                    return $res;

                }
            }

            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;
        }
    }

    public function callback_aftersave1()
    {
        // TODO: Implement callback_aftersave1() method.
    }

    public function callback_beforedelete1()
    {
        // TODO: Implement callback_beforedelete1() method.
    }

    public function callback_perchange_beforesave1()
    {
        // TODO: Implement callback_perchange_beforesave1() method.
    }
}

?>