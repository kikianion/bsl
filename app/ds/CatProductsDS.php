<?php

include "common/CommonDS.php";

class CatProductsDS extends CommonDS
{
    public $table;
    public $colMaps;

    public $orderby = '';
    public $strfilter = '';

    public function __construct()
    {
        $this->table = "catproducts";
        $this->colMaps = array(
            0 => '-',
            1 => 'owner',
            2 => 'code',
            3 => 'name',
        );

        $this->orderby = "id";
        $this->strfilter = "";
        $this->configPermission = array(
            "admin" => array(
                "load" => true,
                'save' => true,
                'delete' => true,
            ),
            "unit" => array(
                "load" > true,
            )
        );
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
                $s = "update $this->table set owner=:owner where id=" . $lastid;

                $st = $pdo_conn->prepare($s);
                $st->execute(array(
                    'owner' => $_SESSION['user'],
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