<?php

//init
class repadmin_citizens
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
            <h1 class="page-header">Daftar Seluruh Nasabah - Admin</h1>
        </div>
    </div>

<?php
$select_citizens = load_select_citizens();

?>
    <button ng-click="exportExcel()">Export Seluruh Data (Excel)</button>

    <div class="table-responsive" style="height: 450px">
        <div id="printdata">
            <link href="_libs/bootstrap/css/bootstrap.css" rel="stylesheet"/>

            <table class="table table-bordered table-condensed">
                <tr>
                    <td style="width: 20px; text-align: right">&nbsp;</td>
                    <td style="width: 100px; text-align: right">
                        <button ng-click="data_all_citizens_filterClear()">Kosongkan</button>
                    </td>
                    <td style="width: 200px;"><input type="text" ng-model="filter.name1" style="width: 100%"></td>
                    <td style="xwidth: 100px;"><input type="text" ng-model="filter.address1" style="width: 100%"></td>
                    <td style="width: 200px;"><input type="text" ng-model="filter.headname1" style="width: 100%"></td>
                    <td style="width: 20px;">
                        <button ng-click="data_all_citizens_filter1()">Filter</button>
                    </td>
                    <td style="width: 100px;"></td>
                </tr>
                <tr>
                    <td>#</td>
                    <td>No Induk</td>
                    <td>Nama</td>
                    <td>Alamat</td>
                    <td>Unit Bank</td>
                    <td>Telepon</td>
                    <td>Saldo akhir</td>
                </tr>
                <tr ng-repeat="item in data_all_citizens | filter: data_all_citizens_filter ">
                    <td>{{$index+1}}</td>
                    <td>{{item.masterid}}</td>
                    <td>{{item.name}}</td>
                    <td>{{item.address}}</td>
                    <td>{{item.headname}}</td>
                    <td>{{item.phone}}</td>
                    <td>{{item.balance}}</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        var data_all_citizens =<?php echo json_encode($select_citizens)?>;
    </script>

<?php
function load_select_citizens()
{
    $pdo_conn = (new DB())->getPDOConnection();

    try {
        $s = "
        select tbl1.id,name,address,phone,level,head,masterid,headname,balance
from
(select w1.id,w1.name,w1.address,w1.phone,
w1.level,w1.head,w1.masterid,w2.name as headname
from workers w1
left join workers w2 on w1.head=w2.id
where w1.level='warga'
order by w1.masterid) tbl1
left join (select id,citizenid,balance
from(select * from trxworkers order by id desc,balance) x
group by citizenid) tbl2 on tbl1.masterid=tbl2.citizenid
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