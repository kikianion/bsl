<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="?">
            <h4 style="font-weight: bold; color: black; text-shadow: gray 1px 0px">Bank Sampah Lamongan</h4>
        </a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>
                Lvl: <?php echo ucfirst($_SESSION['level']) ?>
                | No Induk: <?php echo $_SESSION['masterid'] ?>
                | User: <?php echo $_SESSION['user'] ?>
                <i class="fa fa-caret-down"></i>
            </a>

            <ul class="dropdown-menu dropdown-messages" style="width: 200px">
                <li><a href="javascript: dlg_chpass_open();"><i class="fa fa-sign-out fa-fw"></i> Ganti
                        Password</a>
                </li>
                <li><a href="?page=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->
    <?php
    include "_core/menu.php";
    ?>
</nav>
<script type="text/javascript">
    var dialog;
    function dlg_chpass_open() {
        $("#pass-old").val("");
        $("#pass-new").val("");
        $("#pass-new2").val("");
//        $("#dlg-chpass").modal("show");

        dialog = $("#dialog-form").dialog({
            autoOpen: false,
            height: 400,
            width: 650,
            modal: true,
            buttons: {
                "Batal": function () {
                    dialog.dialog("close");
                },
                "Ganti Password": dlg_chpass_yes,
            },
            close: function () {
//                form[ 0 ].reset();
//                allFields.removeClass( "ui-state-error" );
            }
        });
        dialog.dialog("open");

//        console.log('dialog open');
    }

    function dlg_chpass_yes() {

        //todo implementasi md5 password, harus diganti processing function ini

        var password_lama = $("#pass-old").val();
        var password_baru = $("#pass-new").val();
        var password_baru2 = $("#pass-new2").val();


        if (password_baru != password_baru2) {
            alert("Password baru dan perulangan tidak sama, silahkan isi dengan benar");
            return;
        }

        data = {
            lama: password_lama,
            baru: password_baru,
        };

        $.ajax({
            url: '?page=usermgmt&ds=1&f=chpass_save&',
            dataType: 'json',
            data: data,
            type: 'post',
            success: function (res) {
                if (res.result == 'ok' && res.affected == "1") {
                    flashMessage("Berhasil", "success");
//                    $("#dlg-chpass").modal("hide");
                    dialog.dialog("close");
                }
                else {
                    alert("Gagal, password lama salah");
                }
            },
            error: function () {
                flashMessage("Gagal mengirim data", "error");
            }
        });


    }
</script>

<style>
    .page-header {
        xpadding-bottom: 1px !important;
        margin: 10px 0 0px !important;
    }
</style>

<div id="page-wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="padding-top: 0px" ng-app="bsl">
        <div
            style="xbackground-color: beige; width: 100%; text-align: right; position: absolute; left: -20px; z-index: 800">
            <button ng-click="reloadPage()"><i class="fa fa-refresh"></i></button>
        </div>
        <div id="ngview" ng-view>

        </div>
    </div><!-- /.content-wrapper -->

    <!--    --><?php
    //    $f2 = $p2 . ".php";
    //    if (file_exists($f2)) {
    //        include "$f2";
    //    } else {
    //        include "home.php";
    //    }
    //    ?>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

<script type="text/javascript" src="_libs/angular-1.4.4/angular.js"></script>
<script type="text/javascript" src="_libs/angular-1.4.4/angular-route.js"></script>
<script type="text/javascript" src="_libs/angular-1.4.4/angular-animate.js"></script>
<script type="text/javascript" src="_libs/angular-1.4.4/angular-sanitize.js"></script>

<script type="text/javascript" src="_libs/downloadify/js/swfobject.js"></script>
<script type="text/javascript" src="_libs/downloadify/js/downloadify.min.js"></script>

<script type="text/javascript" src="_libs/excel-builder.js-master/dist/excel-builder.dist.js"></script>

<script type="text/javascript" src="app/client/ng-app.js"></script>

<!--<script type="text/javascript" src="_libs/toastr-8bbd8db/build/toastr.min.js"></script>-->
<script type="text/javascript" src="_libs/alasql-0.2/alasql.min.js"></script>

<!--<script type="text/javascript" src="_common/common.js"></script>-->

<script type="text/javascript">
    var session = {};
    session.user = "<?php echo $_SESSION['user']?>";
    session.level = "<?php echo $_SESSION['level']?>";
    session.name = "<?php echo $_SESSION['name']?>";

</script>

<div id="dialog-form" title="Ganti Password" style="display: none;">
    <h4>Ganti Password</h4>
    <table class="table table-striped">
        <tr>
            <td style="width: 200px">Password Lama</td>
            <td style="width: 3px">:</td>
            <td style="width: 300px;"><input type="password" id="pass-old" class="form-control"></td>
        </tr>
        <tr>
            <td style="width: 100px">Password Baru</td>
            <td style="width: 3px">:</td>
            <td style="width: 200px;"><input type="password" id="pass-new" class="form-control"></td>
        </tr>
        <tr>
            <td style="width: 100px">Password Baru (Ulangi)</td>
            <td style="width: 3px">:</td>
            <td style="width: 200px;"><input type="password" id="pass-new2" class="form-control"></td>
        </tr>
    </table>
</div>

<a href="#" id="downloader" download="export.xlsx"></a>

