<?php

//init
class mypricePage
{
    private $ds = 'MyPriceDS';

    public function DS()
    {
        include_once "app/ds/" . $this->ds . ".php";
        echo (new $this->ds())->start();
    }
}


if (isset($_REQUEST['ds'])) goto end;

//view part
$select_subcatproducts = load_select_subcatprods();

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Harga Pokok</h1>
    </div>
</div>

<?php
include "_comp/HOT_SINGLE/hot-single.html";
?>

<div id="hottable"></div>

<script type="text/javascript">

    var data_select_subcatprods =<?php echo json_encode($select_subcatproducts)?>;

    var arrPickSubCat = [];
    for (var i = 0; i < data_select_subcatprods.length; i++) {
        arrPickSubCat.push(data_select_subcatprods [i].id);
    }

    var _SELF = {};
    (function (_SELF) {
        //init
        _SELF.hot = null;

        var hot_options = {
            btnExport: false,
            colHeaders: ['', 'Sub Kategori Sampah', 'Nama', 'Harga <br>Per Satuan',],
            colWidths: [20, 100, 300, 100, 200, 100, 100, 200],
            columns: [
                {
                    type: 'numeric',
                    readOnly: true,
                    renderer: htmlRenderer1,
                    backColor: "#ddd",
                    maskId: "*",
                },
                {
                    type: 'dropdown',
                    source: arrPickSubCat,
                    allowInvalid: false,
                },
                {
                    readOnly: true,
                    renderer: htmlRenderer1,

                },
                {},

            ],
            beforeRemoveRow: function (index, amount) {
                _SELF.hot.on_before_remove_row(index, amount);
                return false;
            },
            afterCreateRow: function (index, amount) {
            },
            afterChange: function (change, source) {
                _SELF.hot.on_after_change(change, source);
            }
        }

        _SELF.hot = new HotSingle("hottable", hot_options, "myprice");

        _SELF.hot.build(
            function (hot_options) {
                hot_options.minSpareRows = 1;
            },
            function (hot) {
                hot.updateSettings({
                    cells: function (row, col, prop) {
                        var cellProperties = {};
                        return cellProperties;
                    }
                })
            }
        );

        _SELF.hot.loadData(
            function (res) {
                var data = [], row;

                for (var i = 0, ilen = res.data.length; i < ilen; i++) {
                    row = [];
                    row[0] = parseInt(res.data[i].id);
                    row[1] = res.data[i].subcatprod;
                    row[2] = res.data[i].prodname;
                    row[3] = res.data[i].price;

                    data[i] = row;
                }
                return data;

            },
            function (textStatus, errorThrown) {
                flashMessage(textStatus + ": " + errorThrown);
            },
            null,
            null
        );

    })(_SELF);

    function layout_start() {
        var clrBtn = $("#hottable-clear-search")[0];
        if (!clrBtn) {
            return;
        }
        var bt1 = clrBtn.getBoundingClientRect().bottom;
        var top1 = $(window).innerHeight();
        var vb1 = top1 - bt1 - 20;
//            $("#hottable").css('height', vb1 + 50);
        $("#hottable-htable").css('height', vb1);
        if (_SELF.hot)_SELF.hot.hot.render();

        $(window).resize(function () {
            layout_start();
        });
    }
</script>

<?php
function load_select_subcatprods()
{
    $pdo_conn = (new DB())->getPDOConnection();
    try {
        $s = "
select ' ' as id, '-' as text,'' as unit
union
select code as id,concat(code,'-',name) as text,unit from subcatproducts ";
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
