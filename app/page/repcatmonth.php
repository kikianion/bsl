<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Laporan Penjualan Perbulan by Sub Kategori Sampah</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<?php
/**
 * Created by PhpStorm.
 * User: super1
 * Date: 04/11/2016
 * Time: 02:40
 */

session_start();
include_once "../config/db.php";

?>
<table class="table table-bordered table-condensed" ng-init="genReport1()">
    <tr ng-repeat="item in arr_datarep">
        <td>{{item.month}}</td>
        <td>Bulan</td>
        <td>Bulan</td>
        <td>Bulan</td>
        <td>Bulan</td>
    </tr>
</table>
