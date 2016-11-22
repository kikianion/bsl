<?php
session_start();
include_once "../config/db.php";


$trxs = load_trxs();
$trxsellers = load_trxsellers();
?>
    <style>
        .redf {
            background-color: red;
            display: inline-block;
            width: 10px;
            height: 10px;
        }
    </style>
    <script>
        var trxs =<?php echo json_encode($trxs)?>;
        var trxsellers =<?php echo json_encode($trxsellers)?>;


        function correctBal() {
            var lastcitizenid = "";
            for (var i = 0; i < trxs.length; i++) {
                if (lastcitizenid != trxs[i].citizenid) {
                    lastcitizenid = trxs[i].citizenid;
                    trxs[i]._newbal = trxs[i].amount + ".00";
                    trxs[i].citizenid = trxs[i].citizenid + "__";
                }
                else {
                    trxs[i]._newbal = parseFloat(trxs[i - 1]._newbal) + parseFloat(trxs[i].amount);
                }

                if (parseFloat(trxs[i]._newbal) != parseFloat(trxs[i].balance)) {
                    trxs[i].dkbyworker = trxs[i].dkbyworker +
                        "<div class='redf' ></div>";
                }
            }
        }


        correctBal();
    </script>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" style="display: inline-block">correct bal</h1>
            <select ng-model="pickSeller" ng-change="doPickSeller()">
                <option ng-repeat="pick1 in trxsellers" value="{{pick1.sellerid}}">
                    {{pick1.sellerid+'-'+pick1.jmltrxs}}
                </option>
            </select>
            <input type="button" value="do correct" ng-click="doCorrect()">
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div id="printdata">
        <link href="libs/bootstrap/css/bootstrap.css" rel="stylesheet"/>

        <table class="table table-bordered table-condensed">
            <tr>
                <td>id</td>
                <td>dt</td>
                <!--                <td>sellerid</td>-->
                <td>citizenid</td>
                <td>dkbyworker</td>
                <!--                <td>subcatprod</td>-->
                <td>vol</td>
                <td>priceperunit</td>
                <td>amount</td>
                <td>balance</td>
                <!--                <td>printed</td>-->
                <td>_newbal</td>
            </tr>
            <tr ng-repeat="item in trxs">
                <td>{{item.id}}</td>
                <td>{{item.dt}}</td>
                <!--                <td>{{item.sellerid}}</td>-->
                <td>{{item.citizenid}}</td>
                <td>
                    <div ng-bind-html="item.dkbyworker"></div>
                </td>
                <!--                <td>{{item.subcatprod}}</td>-->
                <td>{{item.vol}}</td>
                <td>{{item.priceperunit}}</td>
                <td>{{item.amount}}</td>
                <td>{{item.balance}}</td>
                <!--                <td>{{item.printed}}</td>-->
                <td>
                    <div ng-bind-html="item._newbal"></div>
                </td>
            </tr>
        </table>
    </div>

<?php
function load_trxs()
{
    global $pdo_conn;
    try {
        $user = $_SESSION['user'];
        $s = "
        select * from trxworkers where sellerid='$user' order by citizenid,id
        ";
        $st = $pdo_conn->prepare($s);
        $st->execute();

        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function load_trxsellers()
{
    global $pdo_conn;
    try {
        //$user = $_SESSION['user'];
        $s = "
        select sellerid,count(*) as jmltrxs from trxworkers group by sellerid order by sellerid
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