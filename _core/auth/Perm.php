<?php

/**
 * Created by PhpStorm.
 * User: super1
 * Date: 19/11/2016
 * Time: 07:03
 */
class PermissionManager
{
    private $passThroughPage = " dashboard,  _auth/login,";

    public function checkPermission($page)
    {
        $r = strpos($this->passThroughPage, $page);
        if (strpos($this->passThroughPage, $page) !== false) return $page;

        include_once "app/menu.php";
        $menuArr = (new AppMenuEntry())->menuTmpl;
        for ($i = 0; $i < count($menuArr); $i++) {
            $accesslevel = $menuArr[$i][1];
            $moduleName = $menuArr[$i][2];
            if ($moduleName == $page) {
                if (strpos($accesslevel, 'all') !== false || strpos($accesslevel, $_SESSION['level']) !== false) {
                    return $moduleName;
                }
            }
        }
        return false;
    }
}