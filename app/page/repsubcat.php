<?php

//init
class repsubcatPage
{
    private $ds = 'ReportsDS';

    public function DS()
    {
        include_once "app/ds/" . $this->ds . ".php";
        echo (new $this->ds())->start();
    }
}

if (isset($_REQUEST['ds'])) goto end;

?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Rekapan berdasarkan sub kategori sampah</h1>
        </div>
    </div>


<?php
$select_citizens = load_select_citizens();
$select_subcatproducts = load_select_subcatprods();

?>
    Bulan
    <select ng-model="pickMonth">
        <option ng-repeat="item in ['Semua',1,2,3,4,5,6,7,8,9,10,11,12]">{{item}}</option>
    </select>
    Tahun
    <select ng-model="pickYear">
        <option ng-repeat="item in arr_year">{{item}}</option>
    </select>

    <button ng-click="getRepSubcat()">Proses</button>

    <button ng-click="exportExcel()">Export Seluruh Data (Excel)</button>
    <button ng-click="printTrxBooking()">Print</button><br>

    <div class="table-responsive" style="height: 500px">
        <div id="printdata">
            <link href="_libs/bootstrap/css/bootstrap.css" rel="stylesheet"/>

            <table class="table table-bordered table-condensed">
                <tr>
                    <td>Bulan</td>
                    <td>Kode Sampah</td>
                    <td>Total Vol</td>
                    <td>Total Nilai (Rp)</td>
                </tr>
                <tr ng-repeat="item in trxBookingYearMonth | filter: repBookingFilter ">
                    <td>{{$index>=1 ? item.month==trxBookingYearMonth[$index-1].month?"":item.month : item.month}}</td>
                    <td>{{item.subcatprod}}</td>
                    <td>{{item.jmlvol+" "+item.unit}}</td>
                    <td>{{item.jmlrp}}</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        var data_select_citizens =<?php echo json_encode($select_citizens)?>;
        var data_select_subcatprods =<?php echo json_encode($select_subcatproducts)?>;
        $("#cbMasterId").select2({
            data: data_select_citizens,
            width: 400,
        })
        $("#cbSubCatProd").select2({
            data: data_select_subcatprods,
            width: 400,
        })

    </script>

<?php
function load_select_citizens()
{
    $pdo_conn = (new DB())->getPDOConnection();

    try {
        $head = $_SESSION['userid'];
        $s = "
select ' ' as id, '-' as text
union
select id,concat(masterid,'-',name) as text from workers where head=" . $head;
        $st = $pdo_conn->prepare($s);
        $st->execute();

        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function load_select_subcatprods()
{
    $pdo_conn = (new DB())->getPDOConnection();

    $buyerid = $_SESSION['userid'];
    try {
        //$head = $_SESSION['userid'];
//        $s = "
//select ' ' as id, '-' as text,'' as unit,'' as price
//union
//select code as id,concat(code,'-',name) as text,unit,price from subcatproducts ";

        $s = "
        select ' ' as id, '' as text, '' as subcatprod,'' as prodname, 0 as price, '' as unit
        union
        select mp.subcatprod , concat(mp.subcatprod,'-',mp.prodname) as text, mp.subcatprod,mp.prodname,mp.price, scp.unit from myprice mp
        left join subcatproducts scp on scp.code=mp.subcatprod
        where mp.buyerid=$buyerid
        ";
        $st = $pdo_conn->prepare($s);
        $st->execute();

        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

?>

<?php
end:
