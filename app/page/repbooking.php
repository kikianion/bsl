<?php

class repbookingPage
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
            <h1 class="page-header">Pembukuan Transaksi</h1>
        </div>
        <!-- /.col-lg-12 -->
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

    <button ng-click="getTrxBooking()">Proses</button>

    <button ng-click="exportExcel()">Export Seluruh Data (Excel)</button>

    <button ng-click="printTrxBooking()">Print</button><br>

    <div class="table-responsive" style="height: 450px">
        <div id="printdata">
            <link href="_libs/bootstrap/css/bootstrap.css" rel="stylesheet"/>

            <table class="table table-bordered table-condensed">
                <tr>
                    <td style="width: 100px; text-align: right">
                        <button ng-click="repBookingFilterCLear()">Kosongkan</button>
                    </td>
                    <td style="width: 20px;"><input type="text" ng-model="filter.dkbyworker1" style="width: 100%"></td>
                    <td style="width: 100px;"><input type="text" ng-model="filter.masterid1" style="width: 100%"></td>
                    <td style="width: 20px;"><input type="text" ng-model="filter.subcatprod1" style="width: 100%"></td>
                    <td style="width: 50px;">
                        <button ng-click="repBookingFilter1()">Filter</button>
                    </td>
                    <td style="width: 100px;"></td>
                    <td style="width: 100px;"></td>
                    <td style="width: 100px;"></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>Jenis Transaksi</td>
                    <td>No Induk</td>
                    <td>Kode Sampah</td>
                    <td>Vol</td>
                    <td>Harga/Unit</td>
                    <td>Total</td>
                    <td>Saldo akhir</td>
                </tr>
                <tr ng-repeat="item in trxBookingYearMonth | filter: repBookingFilter ">
                    <td>{{momentf(item.dt,outDateFormat)}}</td>
                    <td>{{item.dkbyworker}}</td>
                    <td>{{item.citizenid}}</td>
                    <td>{{item.subcatprod}}</td>
                    <td>{{item.vol}}</td>
                    <td>{{item.priceperunit}}</td>
                    <td>{{item.amount}}</td>
                    <td>{{item.balance}}</td>
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

end:
?>