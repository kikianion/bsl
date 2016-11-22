<?php
include_once "app/menu.php";

class AppMenu
{
    public function expandSideMenu($menuTmpl)
    {
        $s = "";
        $lastMenuParent = null;
        $lastMenuChild = null;
        $lastMenuParentDisabled = false;
        $lastChildNum = 0;
        for ($i = 0; $i < count($menuTmpl); $i++) {
            $accesslevel = $menuTmpl[$i][1];

            $menuLevel = $menuTmpl[$i][0];
            $moduleName = $menuTmpl[$i][2];
            $display = $menuTmpl[$i][5];
            $fa_icon = $menuTmpl[$i][3];
            $visibility = $menuTmpl[$i][4];
            if(!$visibility) continue;

            $_href = '';
            if ($moduleName != '') {
                $_href = "#/page/$moduleName";
            }

            if (strpos($accesslevel, 'all') === false && strpos($accesslevel, $_SESSION['level']) === false) {
                //disable menu render
                if ($menuLevel == 0) {
                    $lastMenuParentDisabled = true;
                }
                continue;
            }

            if ($menuLevel == 0) {
                $lastMenuParent = $i;
                $lastMenuChild = -1;
                $lastChildNum = 0;
                $lastMenuParentDisabled = false;

                $s .= <<<EOD
                <li>
                    <a href="$_href" id="$moduleName"><i class="$fa_icon"></i>$display</a>
                    <!--parent-$i-->
                </li>
EOD;
            } else if ($menuLevel == 1) {
                if ($lastMenuParentDisabled) {
                    continue;
                }

                if ($lastChildNum == 0) {
                    $s1 = <<<EOD
                        <ul class="nav nav-second-level">
                            <li>
                                <a id="$moduleName" href="$_href"><i class="$fa_icon"></i>$display</a>
                            </li>
                            <!--nextchild-$i-->
                        </ul>
EOD;
                    $s = str_replace("<!--parent-$lastMenuParent-->", $s1, $s);
                } else {
                    $s1 = <<<EOD
                            <li>
                                <a id="$moduleName" href="$_href"><i class="$fa_icon"></i>$display</a>
                            </li>
                            <!--nextchild-$i-->
EOD;
                    $s = str_replace("<!--nextchild-$lastMenuChild-->", $s1, $s);
                }

                $lastChildNum++;
            }
            $lastMenuChild = $i;

        }

        return $s;
    }

}

?>

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <?php
            echo (new AppMenu)->expandSideMenu((new AppMenuEntry())->menuTmpl);
            ?>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>

