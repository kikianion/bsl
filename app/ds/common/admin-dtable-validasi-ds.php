<?php
function set_actual_nama(){
    global $pdo_conn, $table, $cek_nama_error;


    $res=get_actual_nama();

    if($res['result']=='ok'){
        $nama=$res['data'][0]['nama'];
    }
    else{
        $resp['result']='error';
        $resp['msg']='no row changed';
        return $resp;
    }

    $id=$_REQUEST['id'];

    try{

        $s="
        update $table set nama=? where id=?
        ";
        $st=$pdo_conn->prepare($s);

        $st->execute(array($nama,$id));

        if($st->rowCount()>0){
            $resp['result']='ok';
        }
        else{
            $resp['result']='error';
            $resp['msg']='no row changed';
        }

        $ceklen=strlen($cek_nama_error[0]);
        $s="
        update $table 
        set info=if(instr(info,'$cek_nama_error[0]')>0,replace(info,'$cek_nama_error[0]',''),info)
        where id=$id
        ";
        $st=$pdo_conn->prepare($s);

        $st->execute(array($nama,$id));
    }
    catch(Exception $e){
        $resp['result']='error';
        $resp['msg']=$e->getMessage();

    }
    return ($resp);

}

function kegiatan_del_notdefined(){
    global $pdo_conn, $table, $cek_nama_error;

    $id=$_REQUEST['id'];

    try{
        $s="
        delete from $table where id=?
        ";
        $st=$pdo_conn->prepare($s);

        $st->execute(array($id));

        if($st->rowCount()>0){
            $resp['result']='ok';
        }
        else{
            $resp['result']='error';
            $resp['msg']='no row changed';
        }
    }
    catch(Exception $e){
        $resp['result']='error';
        $resp['msg']=$e->getMessage();

    }
    return ($resp);
}

function kegiatan_del_noauth(){
    global $pdo_conn, $table, $cek_nama_error;

    $id=$_REQUEST['id'];

    try{
        $s="
        delete from $table where id=?
        ";
        $st=$pdo_conn->prepare($s);

        $st->execute(array($id));

        if($st->rowCount()>0){
            $resp['result']='ok';
        }
        else{
            $resp['result']='error';
            $resp['msg']='no row changed';
        }
    }
    catch(Exception $e){
        $resp['result']='error';
        $resp['msg']=$e->getMessage();

    }
    return ($resp);
}

function get_actual_nama(){
    global $pdo_conn, $table;

    $u=$_REQUEST['u'];
    $b=$_REQUEST['b'];
    $p=$_REQUEST['p'];
    $k=$_REQUEST['k'];

    if(intVal($p)<15){
        $s="
        select * from master_kegiatan
        where kode_p='$p' and kode_k='$k'
        limit 1
        ";
    }
    else{
        $s="
        select nama from master_kegiatan
        where kode_p='$p' and kode_k='$k' and kode_u='$u' and kode_b='$b'
        limit 1
        ";

    }

    try{
        $st=$pdo_conn->prepare($s);

        $st->execute();

        $rs=$st->fetchAll(PDO::FETCH_ASSOC);

        if(count($rs)==0){
            $resp['result']='error';
            $resp['msg']="not found";
        }
        else{
            $resp['result']='ok';
            $resp['data']=$rs;
        }
        //append kode angka skpd

    }
    catch(Exception $e){
        $resp['result']='error';
        $resp['msg']=$e->getMessage();

    }
    return ($resp);

}

?>
