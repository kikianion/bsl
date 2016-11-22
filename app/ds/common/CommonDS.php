<?php

abstract class CommonDS
{

    public $table;
    public $orderby;
    public $strfilter;

//    public $configPermission = array(
//        "level" => array(
//            "function name" => true,
//        ),
//
//    );

    public $configPermission = array();

    public abstract function  callback_save1();

    public abstract function callback_perchange_aftersave1($lastid, $save_info);

    public abstract function callback_aftersave1();

    public abstract function callback_beforedelete1();

    public abstract function callback_perchange_beforesave1();

    public function start()
    {
        @$f = $_REQUEST["f"];
        if (method_exists($this, $f)) {

            //check configured permission if any
            if (count($this->configPermission) > 0) {
                $level_ = $_SESSION['level'];
                if (isset($this->configPermission[$level_])) {
                    @$funcPerm_ = isset($this->configPermission[$level_][$f])?$this->configPermission[$level_][$f]:false;
                    if ($funcPerm_ != null && $funcPerm_ == true) {
                        echo json_encode($this->$f());
                    }
                    else{
                        echo '{"result":"error","msg":"function denied"}';
                    }
                } else {
                    echo '{"result":"error","msg":"function denied"}';
                }
                //--
            }else{
                echo json_encode($this->$f());
            }

        } else {
            echo '{"result":"error","msg":"function not exist"}';
        }
    }

    private $pdo_conn;

    public $colMaps;

    public function __construct($pdo_conn)
    {
        $this->pdo_conn = $pdo_conn;
    }

    public function load()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        $s = "";
        if ($this->strfilter != '') {
            $s = "select * from $this->table " . $this->strfilter . " order by $this->orderby";
        } else {
            $s = "select * from $this->table order by $this->orderby";
        }

        try {
            $stmt = $pdo_conn->prepare($s);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $res = array('data' => $result);
            $res["result"] = "ok";
        } catch (Exception $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
        }
        return $res;
    }

    public function save()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        try {
            if (isset($_POST['changes'])) {
                $change_rows = json_decode($_POST['changes'], true);
                foreach ($change_rows as $change_row) {

                    $res_cbbefore = $this->callback_perchange_beforesave1();

                    $rowId = $change_row['id'];
                    if ($rowId == "") {
                        $rowId = "-1";
                    }

                    $colNames = "";
                    $newVals = "";

                    $newVals_pdo = "";

                    $prepareParamarray = array();
                    $updateColVal_pdo = '';

                    $updateColVal = '';

                    for ($j = 0; $j < count($change_row['rowData']); $j++) {
                        $row1 = $change_row['rowData'][$j];
                        $colId = $row1[1];

                        if ($j == count($change_row['rowData']) - 1) {
                            $colNames .= $this->colMaps[$colId];
                            $newVals .= "'" . $row1[3] . "'";
                            $updateColVal .= $this->colMaps[$colId] . "=" . "'" . $row1[3] . "'";

                            $updateColVal_pdo .= $this->colMaps[$colId] . "=?";
                            $prepareParamarray[] = $row1[3];

                            $newVals_pdo .= "?";
                        } else {
                            $colNames .= $this->colMaps[$colId] . ",";
                            $newVals .= "'" . $row1[3] . "',";
                            $updateColVal .= $this->colMaps[$colId] . "=" . "'" . $row1[3] . "',";

                            $updateColVal_pdo .= $this->colMaps[$colId] . "=?, ";
                            $prepareParamarray[] = $row1[3];

                            $newVals_pdo .= "?,";
                        }
                    }

                    $stmt = $pdo_conn->prepare("SELECT id FROM $this->table WHERE id=:id LIMIT 1");
                    $stmt->execute(array(
                        'id' => $rowId
                    ));

                    $lastid = null;

                    //record sudah ada
                    if ($row = $stmt->fetch()) {

                        //val row before update
                        $s = "select * from $this->table WHERE id = $rowId ";
                        $query = $pdo_conn->prepare($s);
                        $query->execute();
                        $rs6 = $query->fetchAll(PDO::FETCH_ASSOC);
                        if (count($rs6) > 0) {
                            $save_info['oldval'] = $rs6[0];
                        } else {
                            $save_info['oldval'] = 'false';
                        }

                        $s = "UPDATE $this->table SET $updateColVal_pdo WHERE id = ? ";

                        $param_arr1 = array_merge($prepareParamarray, array($rowId));
                        $query = $pdo_conn->prepare($s);
                        $query->execute($param_arr1);

                        $lastid = $rowId;

                        $save_info['type'] = 'update';
                    } //record belum ada
                    else {
                        $s = "INSERT INTO $this->table ($colNames) VALUES($newVals_pdo)";
                        $query = $pdo_conn->prepare($s);
                        $query->execute($prepareParamarray);
                        $lastid = $pdo_conn->lastInsertId();
                        $save_info['type'] = 'insert';
                    }


                    $res_cb = $this->callback_perchange_aftersave1($lastid, $save_info);
                    if ($res_cb != null && $res_cb["result"] == 'error') {
                        return $res_cb;
                    }

                }

                $this->callback_aftersave1();

                $res["result"] = "ok";
                $res["msg"] = "affected: " . $query->rowCount();
                return $res;
            }
        } catch (Exception $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;
        }
    }

    public function delete()
    {
        $pdo_conn = (new DB())->getPDOConnection();

        @$ids = $_REQUEST["ids"];

        try {
            $this->callback_beforedelete1();

            if (count($ids) > 0) {
                for ($i = 0; $i < count($ids); $i++) {
                    $stmt = $pdo_conn->prepare("delete from $this->table WHERE id=:id ");
                    $stmt->execute(array(
                        'id' => $ids[$i]
                    ));

                }
            }

            $res["result"] = "ok";
            return $res;

        } catch (Exception $e) {
            $res["result"] = "error";
            $res["msg"] = $e->getMessage();
            return $res;
        }
    }
}

?>