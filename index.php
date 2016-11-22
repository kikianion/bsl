<?php

// main router file, index.php

//-- init -- start
session_start();

include_once "__config/db.php";
include_once "_common/func.php";
//-- init -- end


//-- param -- start
@$page = $_REQUEST['page'];
@$ds = $_REQUEST['ds'];
//todo remove prevent csrf
@$msg = $_REQUEST["msg"];
@$do_login = $_REQUEST["do_login"];
//-- param -- end


//handle main login -- start
if ($do_login == "1") {
    include_once "_core/auth/login.php";

    @$username = $_REQUEST["username"];
    @$password = $_REQUEST["password"];

    $res = (new Auth())->userLogin($username, $password);

    if ($res['result'] == 'error') {
        $_SESSION['login-msg'] = $res['msg'];
    } elseif ($res['result'] == 'ok') {
        redirect_out("index.php");
    }
}
//handle main login -- end


//security -- start

if ($page == 'logout') {
    include_once "_core/auth/logout.php";
    exit();
}

$shellPage = false;
if (isset($_SESSION['user']) && $_SESSION['user'] != '') {
    if ($page == null) {
        //load shell after login
        $page = 'dashboard';
        $shellPage = true;
    }
} else {
    $page = '_auth/login';
    $shellPage = true;
}
//security -- end

//-- controller -- start
// place for processing
if ($page == '') {
    //todo refactor
    $res['result'] = 'error';
    $res['msg'] = "module to load empty: $page";
    echo json_encode($res);
    exit();
}
ob_start();

//check permission
include_once "_core/auth/Perm.php";
if (($moduleName = (new PermissionManager())->checkPermission($page)) !== false) {
    $fname = preg_replace("/\./i", "/", $moduleName);
    $fname = "app/page/$fname.php";
    if (file_exists($fname)) {
        include $fname;

        @$ds = $_REQUEST['ds'];
        if ($ds == '1') {
            $class_ = $page . "Page";
            $class_ = preg_replace("/\./i", "_", $class_);
            $class_ = preg_replace("/\-/i", "_", $class_);

            (new $class_())->DS();
        }
    } else {
        //todo refactor
        $res['result'] = 'error';
        $res['msg'] = "module not found: $page";
        echo json_encode($res);
        exit();
    }
} else {
    //todo refactor
    $res['result'] = 'error';
    $res['msg'] = "permission denied: $page";
    echo json_encode($res);
    exit();
}

$cnt = ob_get_contents();
ob_end_clean();

//request page SPA
if (!$shellPage) {
    echo $cnt;
    exit();
}

//-- controller -- end

?>
    <!--view -- start -->
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:bottom="http://www.w3.org/1999/xhtml"
          xmlns:width="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
        <meta name="description" content="">
        <meta name="author" content="">

        <!--        todo-->
        <title>BANK SAMPAH LAMONGAN - BADAN LINGKUNGAN HIDUP LAMONGAN (BLH)</title>
        <script src="_libs/jquery/jquery-1.10.2.min.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link href="_libs/jquery/css/ui-lightness/jquery-ui-1.8.22.custom.css" rel="stylesheet">


        <link href="_libs/bootstrap/css/bootstrap.css" rel="stylesheet"/>
        <script src="_libs/bootstrap/js/bootstrap.min.js"></script>

        <link rel="stylesheet" type="text/css" href="_libs/bootstrap/css/bootstrap-datepicker3.css">

        <link rel="stylesheet" type="text/css" href="_libs/css/style.css">
        <style type="text/css">
            /* <![CDATA[ */
            body {
                margin: 0px 0px;
                overflow-x: hidden;
                overflow-y: auto;
                background: none repeat scroll 0 0 #222d32;
            }

            /* ]]> */
        </style>

        <link href="_libs/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <!-- Ionicons -->
        <link href="_libs/css/ionicons.css" rel="stylesheet" type="text/css"/>
        <!-- Theme style -->
        <link href="_libs/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <link href="_libs/sbadmin/css/sb-admin-2.css" rel="stylesheet">
        <link href="_libs/toastr-8bbd8db/build/toastr.css" rel="stylesheet">

        <link href="_libs/bootstrap3dialog/css/bootstrap-dialog.css" rel="stylesheet">
        <link href="_libs/select2-4.0.2/dist/css/select2.css" rel="stylesheet">

        <link data-jsfiddle="common" rel="stylesheet" media="screen"
              href="_libs/handsontable-0.18.0/dist/handsontable.full.css">

        <![if lt IE 9]>
        <!--        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>-->
        <!--        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
        <![endif]>

        <!--        <script type="text/javascript" src="js/app.js"></script>-->
        <script type="text/javascript" src="_libs/jquery/js/jquery-ui-1.8.22.custom.min.js"></script>
        <script type="text/javascript" src="_libs/jquery/jquery.form.js"></script>
        <script type="text/javascript" src="_libs/jquery/jquery-migrate-1.1.0.js"></script>

        <script src="_libs/bootstrap3dialog/js/bootstrap-dialog.js"></script>
        <script type="text/javascript" src="_libs/bootstrap/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="_libs/bootstrap/locales/bootstrap-datepicker.id.min.js"></script>

        <script src="_libs/metisMenu/dist/metisMenu.min.js"></script>
        <script src="_libs/sbadmin/js/sb-admin-2.js"></script>

        <script data-jsfiddle="common" src="_libs/handsontable-0.18.0/dist/handsontable.full.js"></script>
        <script data-jsfiddle="common" src="_libs/handsontable-0.18.0/dist/moment/moment.js"></script>
        <script data-jsfiddle="common" src="_libs/handsontable-0.18.0/dist/moment/locale/id.js"></script>
        <script data-jsfiddle="common" src="_libs/handsontable-0.18.0/lib/numeral/numeral.id-id.js"></script>

        <script data-jsfiddle="common" src="_libs/select2-4.0.2/dist/js/select2.full.js"></script>
        <script type="text/javascript" src="_libs/toastr-8bbd8db/build/toastr.min.js"></script>

        <script type="text/javascript" src="_common/common.js"></script>

    </head>
    <body id="body">


    <div class="wrapper">

        <div class="row">
            <?php
            if ($msg != '') {
                ?>
                <!--div class="alert alert-warning alert-dismissible fade in" role="alert" style="margin:0px 10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php
                echo $msg;
                ?>
                    </div-->

                <script type="text/javascript">
                    /* <![CDATA[ */
                    $(function () {
                        BootstrapDialog.show({
                            type: BootstrapDialog.TYPE_DEFAULT,
                            closable: false,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            title: 'Pesan',
                            message: '<?php echo $msg?>',
                            buttons: [
                                {
                                    label: 'Tutup',
                                    action: function (dialogItself) {
                                        dialogItself.close();
                                    }
                                }]
                        });
                    })
                    /* ]]> */
                </script>
                <?php
            }
            ?>

            <div class="col-md-12">
                <?php
                echo $cnt;
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var msg = "<?php
        @$msg=$_SESSION['msg'];
        $_SESSION['msg']='';
        echo $msg
        ?>";

        if (msg.length > 0) {
            flashMessage(msg, 'info');
        }

        function openAbout() {
            var dialog;

            dialog = $("#dialog-about").dialog({
                autoOpen: false,
                height: 400,
                width: 450,
                modal: true,
                buttons: {
                    "Tutup": function () {
                        dialog.dialog("close");
                    },
                },
                close: function () {
                }
            });
            dialog.dialog("open");

        }
    </script>
    <div id="dialog-about" title="Tentang Aplikasi" style="display: none;">
        <h4>Aplikasi Bank Sampah Terpadu v.1</h4>
        <table class="table">
            <tr>
                <td style="width: 200px">Dibuat oleh</td>
                <td style="width: 3px">:</td>
                <td style="width: 300px;">
                    Dzikri UAZ<br>
                    email: dzikri@duaz.web.id<br>
                    whatsapp: 081 336 491 544<br>
                    telegram: 0856 5526 5060<br>
                    fb: <a href="http://facebook.com/dzikriuaz">facebook.com/dzikriuaz</a>
                </td>
            </tr>
            <tr>
                <td style="width: 200px">Spesifikasi Teknis</td>
                <td style="width: 3px">:</td>
                <td style="width: 300px;">
                    Mini custom PHP framework, single route file entry, MVC, Mysql, permission configurable per CRUD action, jquery 1.10,
                    AngularJS 1.4, Handsontable 0.18, excel like entry, SPA, multiple concurrent data entry, copy paste from/to excel,
                    Bootstrap 3, client side excel-builder-js, toastr, sbAdmin Template, simple news function<br>
                    <br>
                    *software include open source and MIT licensed components, file license included in the app source
                </td>
            </tr>
        </table>
    </div>

    <style>
        .footer1 {
            position: absolute;
            bottom: 0px;
            width: 100%;
            text-align: right;
            padding-right: 10px;
            padding-bottom: 3px;
            font-size: .8em;
        }
    </style>

    <div class="footer1">
        <a href="javascript: openAbout();">Tentang Aplikasi</a>
    </div>
    </body>
    </html>

    <!--view -- end -->
<?php
//-- cleanup -- start

//-- cleanup -- end

?>