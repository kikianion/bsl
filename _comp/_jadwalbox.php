<?php
@$tahun=$_SESSION['tahun'];

if(!$tahun){
    $tahun=get_a_field("master_tahun","active_",'1',"tahun");
}

$tahun_aktif=get_a_field("master_tahun","tahun",$tahun,"active_");
if($tahun_aktif!=1) {
    echo "<h4 style=''>Jadwal untuk tahun $tahun tidak aktif</h4>";
    exit;
};

?>
<style type="text/css">
    .jadwalbox{
        border-style: solid;
        border-color: black;
        border-width: 1px;
        border-collapse: collapse;
    }

    .jadwalbox *{
        vertical-align: top;
        padding: 3px; 
    }

    .bold1{
        font-weight: bold;
    }
    .bold2{
        font-weight: bold;
        font-size: 1.2em;
    }
</style>

<?php
@$aktif_tahun=get_a_field("master_tahun","active_","1","tahun");
?>

<div id="jadwal" style="margin-top: 30px;">
    <table class="jadwalbox" style="width: 100%;">
        <tr>
            <td colspan="3" class="jadwalbox bold2" style="text-align: center;">Jadwal untuk tahun aktif <?php echo $aktif_tahun ?></td>
        </tr>
        <tr>
            <td colspan="3" class="jadwalbox bold1" >Musrenbang Kecamatan</td>
        </tr>
        <tr >
            <td style="width: 40%">
                <?php 
                echo get_a_field("jadwal","kode","musrencam-kec-entri","nama");
                ?>
            </td>
            <td>:</td>
            <td>
                <?php 
                $musrencam_kec_entri_start=get_a_field("jadwal","kode","musrencam-kec-entri","startdt");
                $musrencam_kec_entri_finish=get_a_field("jadwal","kode","musrencam-kec-entri","finishdt");
                echo date('j M Y', strtotime($musrencam_kec_entri_start)).' - '.date('j M Y', strtotime($musrencam_kec_entri_finish));

                $fEditable=isRWActiveNow('musrencam-kec-entri');
                if($fEditable) echo " <span style='color: green; font-weight: bold'>(Aktif)</span>"
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php 
                echo get_a_field("jadwal","kode","musrencam-bid-ver","nama");
                ?>

            </td>
            <td>:</td>
            <td>
                <?php 
                $musrencam_bid_ver_start=get_a_field("jadwal","kode","musrencam-bid-ver","startdt");
                $musrencam_bid_ver_finish=get_a_field("jadwal","kode","musrencam-bid-ver","finishdt");
                echo date('j M Y', strtotime($musrencam_bid_ver_start)).' - '.date('j M Y', strtotime($musrencam_bid_ver_finish));

                $fEditable=isRWActiveNow('musrencam-bid-ver');
                if($fEditable) echo "  <span style='color: green; font-weight: bold'>(Aktif)</span>"
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php 
                echo get_a_field("jadwal","kode","musrencam-dinas-ro","nama");
                ?>
            </td>
            <td>:</td>
            <td>Mulai
                <?php 
                $musrencam_dinas_ro_start=get_a_field("jadwal","kode","musrencam-dinas-ro","startdt");
                echo date('j M Y', strtotime($musrencam_dinas_ro_start));

                $fEditable=isRWActiveNow('musrencam-dinas-ro', RW_ACTIVE_NOW_START);
                if($fEditable) echo "  <span style='color: green; font-weight: bold'>(Aktif)</span>"
                ?>
                <br><br><br>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="jadwalbox bold1" >SIPPD (Musrenbang Kabupaten)</td>
        </tr>
        <tr >
            <td style="width: 40%">
                <?php 
                echo get_a_field("jadwal","kode","musrenkab-skpd-entri","nama");
                ?>
            </td>
            <td>:</td>
            <td>
                <?php 
                $musrenkab_skpd_entri_start=get_a_field("jadwal","kode","musrenkab-skpd-entri","startdt");
                $musrenkab_skpd_entri_finish=get_a_field("jadwal","kode","musrenkab-skpd-entri","finishdt");
                echo date('j M Y', strtotime($musrenkab_skpd_entri_start)).' - '.date('j M Y', strtotime($musrenkab_skpd_entri_finish));

                $fEditable=isRWActiveNow('musrenkab-skpd-entri');
                if($fEditable) echo " <span style='color: green; font-weight: bold'>(Aktif)</span>"
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php 
                echo get_a_field("jadwal","kode","musrenkab-bid-ver","nama");
                ?>

            </td>
            <td>:</td>
            <td>
                <?php 
                $musrenkab_bid_ver_start=get_a_field("jadwal","kode","musrenkab-bid-ver","startdt");
                $musrenkab_bid_ver_finish=get_a_field("jadwal","kode","musrenkab-bid-ver","finishdt");
                echo date('j M Y', strtotime($musrenkab_bid_ver_start)).' - '.date('j M Y', strtotime($musrenkab_bid_ver_finish));

                $fEditable=isRWActiveNow('musrenkab-bid-ver');
                if($fEditable) echo "  <span style='color: green; font-weight: bold'>(Aktif)</span>"
                ?>
            </td>
        </tr>
    </table>
</div>
