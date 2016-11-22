<?php

//init
class usermgmtPage
{
    private $ds = 'usermgmtDS';

    public function DS()
    {
        include_once "app/ds/" . $this->ds . ".php";
        echo (new $this->ds())->start();
    }
}
?>
