<?php

$fileList=array( 
    "../_comp/head-singleton-css.php",//main cek mtime

    "../libs/jquery/css/ui-lightness/jquery-ui-1.8.22.custom.css",
    "../libs/toastr-8bbd8db/build/toastr.css",
    "../libs/handsontable-0.18.0/dist/handsontable.css",
    "../libs/handsontable-0.18.0/dist/pikaday/pikaday.css",
    "../libs/select2-4.0.2/dist/css/select2.css",
    "../libs/assets/app.css",
    "../libs/tagsinput/bootstrap-tagsinput.css",
    "../libs/bootstrap/css/bootstrap-datepicker3.css",
    "../libs/vakata-jstree-92ac4c8/dist/themes/default/style.min.css",
    "../libs/bootstrap3dialog/css/bootstrap-dialog.css",
    "../libs/DataTables-1.10.8/datatables.css",
    "../libs/css/font-awesome.css",
    "../libs/css/ionicons.css",
    "../libs/adminlte/css/AdminLTE.css",
    "../libs/adminlte/css/skins/skin-blue.css",
    "../libs/adminlte/css/skins/skin-purple.css",
    "../libs/hover-master/css/hover-min.css",
    "../libs/tooltipster-master/css/tooltipster.css",
    "../libs/tooltipster-master/css/themes/tooltipster-shadow.css",

    "../common/css/main.css",

    
    
);

// Getting headers sent by the client.
$headers = apache_request_headers(); 

$mtime=0;
for($i=0; $i<count($fileList); $i++){
    if(file_exists($fileList[$i])){
        $mtime=max($mtime, filemtime($fileList[$i]));

    }
    else{
        echo "file not found: ",$fileList[$i];
        exit();
    }
}
// Checking if the client is validating his cache and if it is current.
if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $mtime)) {
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime ).' GMT', true, 304);
} else {
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime ).' GMT', true, 200);
}

header('Content-Type: text/css');

for($i=0; $i<count($fileList); $i++){
    if( strpos($fileList[$i], 'head-singleton')===false ){
        echo ( file_get_contents($fileList[$i]) );
    }
}

?>

