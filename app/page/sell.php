<?php
class sellPage
{
    private $ds = 'TrxDS';

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
        <h1 class="page-header">Transaksi Penjualan <?php echo ucfirst($_SESSION['name']) ?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>


<?php
$select_citizens = load_select_citizens();
$select_subcatproducts = load_select_subcatprods();

?>
<form id="formtrx">
    <table class="table table-striped">
        <tr>
            <td style="width: 200px">Tanggal</td>
            <td style="width: 2px">:</td>
            <td style="width: 400px">
                <input type="text" id="dt" readonly ng-model="val.dt" name="dt">
            </td>
        </tr>
        <tr>
            <td style="width: 200px">Jenis Transaksi</td>
            <td style="width: 2px">:</td>
            <td style="width: 400px">
                <select name="trx" ng-model="trxKind" ng-init="trxKind='-1'">
                    <option></option>
                    <option value="-1">D1-Setor Sampah</option>
                    <option value="1">K1-Ambil Uang</option>
                    <option value="2">D2-Deposit Uang</option>
                    <option value="3">D9-Koreksi Masuk</option>
                    <option value="4">K9-Koreksi Ambil</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>No Induk</td>
            <td>:</td>
            <td>
                <select id="cbMasterId" name="custmasterid" ng-change="getBalanceWorker()" ng-model="masterId">

                </select><br>
                Saldo akhir: {{label.balanceWorker}}
            </td>

        </tr>
        <tr ng-show="trxKind==-1">
            <td>Kode Sub Sampah</td>
            <td>:</td>
            <td>
                <select id="cbSubCatProd" ng-change="updateUnitMeasure(unitMeasure)"
                        ng-model="unitMeasure" name="subcatprod"></select>
            </td>
        </tr>
        <tr ng-show="trxKind==-1">
            <td>Volume</td>
            <td>:</td>
            <td>
                <input type="number" ng-model="val.vol" ng-change="totalRp()" name="vol" id="vol">
                {{label.unitmeasure}}
            </td>
        </tr>
        <tr ng-show="trxKind==-1">
            <td>Harga Per Unit</td>
            <td>:</td>
            <td>
                <input type="number" ng-model="val.pricePerUnit" ng-change="totalRp()" name="ppu" id="ppu">
            </td>
        </tr>
        <tr ng-show="trxKind==3 || trxKind==4">
            <td>Keterangan</td>
            <td>:</td>
            <td>
                <textarea cols="50" rows="3" ng-model="val.ket" name="ket" id="ket"></textarea>
            </td>
        </tr>
        <tr>
            <td>Total Rp.</td>
            <td>:</td>
            <td>
                <input type="number" ng-model="val.totalRp" name="amountTotal" ng-readonly="trxKind==-1"
                       id="totalrp" ng-change="checkLimitOnCreditTrx()">
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right">
                <button ng-click="sellFormSubmit()" ng-disabled="flag.submiting==1">{{label.submit}}</button>
                <input type="hidden" value="saveTrx" name="f">
            </td>
        </tr>

    </table>
</form>

<h4 style="display:inline-block ">Print Transaksi</h4>
<button ng-click="printTrx()">Print</button><br>

<table class="table table-bordered table-condensed">
    <thead>
    <td>Tanggal</td>
    <td>Jenis Transaksi</td>
    <!--        <td>No Induk</td>-->
    <td>Kode Sampah</td>
    <td>Vol</td>
    <td>Harga/Unit</td>
    <td>Total</td>
    <td>Saldo akhir</td>
    <td>Ket</td>
    </thead>
    <tr ng-repeat="item in unprintedTrxs">
        <td>{{ momentf(item.dt,outDateFormat) }}</td>
        <td>{{item.dkbyworker}}</td>
        <!--            <td>{{item.citizenid}}</td>-->
        <td>{{item.subcatprod}}</td>
        <td>{{item.vol}}</td>
        <td>{{item.priceperunit}}</td>
        <td>{{item.amount}}</td>
        <td>{{item.balance}}</td>
        <td>{{item.ket}}</td>
    </tr>
</table>
<div id="unprinted" style="display: none">
    <link href="_libs/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <style>
        #tobeprinted tr, #tobeprinted td, {
            border: none;
        }

        .table-borderless tbody tr td, .table-borderless tbody tr th, .table-borderless thead tr th {
            border: none;
        }

        .border1 {
            border-top: 1px solid black;
        }
    </style>

    <table class="table table-condensed table-borderless" id="tobeprinted">
        <tr ng-repeat="item in willPrintedTrxs" style="height: 80px; " ng-class="item.serial==''?'':'border1'">
            <td>{{item.serial}}</td>
            <td>{{item.dt}}</td>
            <td>{{item.subcatprod}}</td>
            <td>{{item.vol}}</td>
            <td>{{item.dkbyworker}}</td>
            <td>{{item.amount}}</td>
            <td>{{item.balance}}</td>
            <td style="width: 200px"></td>
        </tr>
    </table>
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

    function preventMinus(event) {
        if (event.which == 45 || event.which == 189) {
            event.preventDefault();
        }
    }

    $("#vol").keypress(function (event) {
        preventMinus(event);
    });
    $("#ppu").keypress(function (event) {
        preventMinus(event);
    });
    $("#totalrp").keypress(function (event) {
        preventMinus(event);
    });

    $('#dt').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });
</script>

<?php
function load_select_citizens()
{
    $pdo_conn = (new DB)->getPDOConnection();
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
    $pdo_conn = (new DB)->getPDOConnection();
    $buyerid = $_SESSION['userid'];
    try {

        $s = "
        select ' ' as id, '' as text, '' as subcatprod,'' as prodname, 0 as price, '' as unit
        union
        select mp.subcatprod ,
        concat(mp.subcatprod,'-',mp.prodname) as text,
        mp.subcatprod,mp.prodname,mp.price, scp.unit from myprice mp
        left join subcatproducts scp on scp.code=mp.subcatprod
        where mp.buyerid=$buyerid
        ";

        $s = "
select ' ' as id, '' as text, 0 as price,'' as unit

        union

        select mp.subcatprod ,
concat(cp.code,'-',cp.name,'  |  ',mp.subcatprod,'-',mp.prodname) as text,
mp.price, scp.unit from myprice mp
left join subcatproducts scp on scp.code=mp.subcatprod
left join catproducts cp on cp.code=scp.parentid
where mp.buyerid=$buyerid

union

select scp.code,
concat(cp.code,'-',cp.name,'  |  ',scp.code,'-',scp.name),
0,
unit from subcatproducts scp
left join catproducts cp on cp.code=scp.parentid
where scp.code not in(select subcatprod from myprice where buyerid=$buyerid)


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