<?php

//init
class unitsPage
{
    private $ds = 'CitizenDS';

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
            <h1 class="page-header">Unit Bank Sampah</h1>
        </div>
    </div>

<?php
include "_comp/HOT_SINGLE/hot-single.html";
?>

    <div id="hottable"></div>

    <script type="text/javascript">
        var _SELF = {};
        (function (_SELF) {
            //init
            _SELF.hot = null;

            var hot_options = {
                colHeaders: ['', '_Dibawahi', '_Level', 'No Induk', 'Nama', 'Username', 'Password', 'Alamat', 'Telepon'],
                colWidths: [20, 1, 1, 100, 300, 200, 200, 200, 200],
                columns: [
                    {
                        type: 'numeric',
                        readOnly: true,
                        renderer: htmlRenderer1,
                        backColor: "#ddd",
                        maskId: "*",
                    },
                    {
                        readOnly: true,
                        renderer: htmlRenderer1,

                    },
                    {
                        readOnly: true,
                        renderer: htmlRenderer1,

                    },
                    {
                        readOnly: true,
                        renderer: htmlRenderer1,
                    },
                    {},
                    {},
                    {},
                    {},
                    {},

                ],
                beforeRemoveRow: function (index, amount) {
                    //todo
                    _SELF.hot.on_before_remove_row(index, amount);
                    return false;
                },
                afterCreateRow: function (index, amount) {
                },
                afterChange: function (change, source) {
                    _SELF.hot.on_after_change(change, source);
                }
            }

            _SELF.hot = new HotSingle("hottable", hot_options, "units");

            _SELF.hot.exportData = function () {
                var a = [['#', 'No Induk', 'Nama', 'Username', 'Alamat', 'Telp']]

                var d = _SELF.hot.hot.getData();

                for (var i = 0; i < d.length; i++) {
                    var o = d[i];
                    a.push([(i + 1), o[3], o[4], o[5], o[7],o[8]]);
                }
                export_excel(a);
            }
            _SELF.hot.build(
                function (hot_options) {
                    hot_options.minSpareRows = 1;
                },
                function (hot) {
                    hot.updateSettings({
                        cells: function (row, col, prop) {
                            var hot = self.hot;
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
                        row[1] = res.data[i].head;
                        row[2] = res.data[i].level;
                        row[3] = res.data[i].masterid;
                        row[4] = res.data[i].name;
                        row[5] = res.data[i].username;
                        row[6] = res.data[i].pass;
                        row[7] = res.data[i].address;
                        row[8] = res.data[i].phone;

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
end:
?>