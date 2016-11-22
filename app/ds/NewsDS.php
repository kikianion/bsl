<?php

include_once "common/CommonDS.php";

class NewsDS extends CommonDS
{
    public function __construct()
    {
        $this->table = "news";
        $this->colMaps = array(
            0 => '-',
            1 => 'title',
            2 => 'content',
            3 => 'dt',
        );

        $this->orderby = "dt";
        $this->strfilter = "";
    }

    public function  callback_save1()
    {
        // TODO: Implement callback_save1() method.
    }

    public function callback_perchange_aftersave1($lastid, $save_info)
    {
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