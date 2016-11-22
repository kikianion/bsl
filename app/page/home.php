<?php
//session_start();
//include_once "../_config/db.php";
?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">SELAMAT DATANG DI APLIKASI BANK SAMPAH </h1>

            <H2>BADAN LINGKUNGAN HIDUP LAMONGAN</H2>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <style>
        .btnHome {
            width: 200px;
        }

        .table-borderless tbody tr td, .table-borderless tbody tr th, .table-borderless thead tr th {
            border: none;
        }
    </style>
    <table class="table table-borderless">
        <tr>
            <td>
                <button ng-click="location.url('/page/sell/4');" class="btn btn-primary btnHome" type="submit">Transaksi
                    Penjualan
                </button>
            </td>
        </tr>

        <tr>
            <td>
                <button ng-click="location.url('/page/catproducts/4');" class="btn btn-primary  btnHome" type="submit">
                    Data Kategori Sampah
                </button>
            </td>
        </tr>
        <tr>
            <td>
                <button ng-click="location.url('/page/subcatproducts/4');" class="btn btn-primary  btnHome" type="submit">
                    Data Sub Kategori Sampah
                </button>
            </td>
        </tr>
    </table>

<?php

?>