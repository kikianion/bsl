<?php

include "common/CommonDS.php";

class SubCatProductsDS extends CommonDS
{
    public $table;
    public $colMaps;

    public $orderby = '';
    public $strfilter = '';

    public function __construct()
    {
        $this->table = "subcatproducts";
        $this->colMaps = array(
            0 => '-',
            1 => 'code',
            2 => 'name',
            3 => 'unit',
            4 => 'parentid',
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
        // TODO: Implement callback_perchange_aftersave1() method.
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