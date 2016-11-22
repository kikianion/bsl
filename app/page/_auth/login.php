<?php
$msgResult = '';
if (isset($_SESSION['login-msg'])) {
    if (strpos($_SESSION['login-msg'], 'empty') !== false) {
        $msgResult = "Mohon lengkapi data";
    } elseif (strpos($_SESSION['login-msg'], 'false') !== false) {
        $msgResult = "Username atau password salah";
    }
    $_SESSION['login-msg'] = "";
}

?>
<div class="col-md-4 col-md-offset-2">
    <div class="login-panel panel panel-default">

        <!--        todo-setting-->
        <h1 style="text-align: center;">BANK SAMPAH</h1>

        <!--        todo-setting-->
        <h2 style="text-align: center;">Lamongan</h2>

        <div class="panel-heading">
            <h3 class="panel-title">Silahkan Login</h3>
        </div>
        <div class="panel-body">
            <div style="margin: 5px; margin-bottom: 15px; color: darkred">
                <?php
                echo $msgResult;
                ?>
            </div>
            <form role="form" action="?" method="post">
                <fieldset>
                    <input type="hidden" name="do_login" value="1">

                    <div class="form-group">
                        <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    </div>
                    <!-- Change this to a button or input when using this as a form -->
                    <input type="submit" value="Login" class="btn btn-lg btn-success btn-block">
                </fieldset>
            </form>
        </div>
    </div>
</div>
<div class="col-md-4 ">
    <div class="login-panel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Berita</h3>
        </div>
        <div class="panel-body">

            <div class="table-responsive" style="height: 280px">
                <table class="table xtable-bordered">
                    <?php
                    $pdo_conn = (new DB())->getPDOConnection();
                    try {
                        $s = "select * from news order by dt desc limit 100";
                        $st = $pdo_conn->prepare($s);
                        $st->execute();

                        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
                        for ($i = 0; $i < count($rs); $i++) {
                            ?>
                            <tr style="background-color: #ddd">
                                <td>
                                    <b><?php echo htmlspecialchars($rs[$i]['title']) ?></b>
                                    <i style="font-size: .8em">(<?php echo date('Y-m-d', strtotime($rs[$i]['dt'])) ?>
                                        )</i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($rs[$i]['content']) ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var loginPage = 1;
</script>