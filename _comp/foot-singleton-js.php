<?php

$fileList=array( 
    "../_comp/foot-singleton-js.php",//main cek mtime
    "../libs/jquery/js/jquery-ui-1.8.22.custom.min.js",
    "../libs/jquery/jquery.form.js",
    "../libs/jquery/jquery-migrate-1.1.0.js",
    "../libs/toastr-8bbd8db/toastr.js",
    "../libs/angular-1.4.4/angular-route.js",
    "../libs/angular-1.4.4/angular-animate.js",
    "../libs/angular-1.4.4/angular-sanitize.js",
    "../libs/alasql-0.2/alasql.min.js",
    "../libs/bootstrap/js/bootstrap.min.js",
    "../libs/DataTables-1.10.8/datatables.js",
    "../libs/DataTables-1.10.8/dataTables.colResize.js",
    "../libs/handsontable-0.18.0/dist/moment/moment.js",
    "../libs/handsontable-0.18.0/dist/moment/locale/id.js",

    "../libs/handsontable-0.18.0/dist/pikaday/pikaday.js",
    "../libs/handsontable-0.18.0/dist/zeroclipboard/ZeroClipboard.js",
    "../libs/handsontable-0.18.0/dist/handsontable.js",
    "../libs/handsontable-0.18.0/lib/numeral/numeral.id-id.js",
    "../libs/select2-4.0.2/dist/js/select2.full.js",
    "../libs/tagsinput/typeahead.bundle.js",
    "../libs/tagsinput/bootstrap-tagsinput.js",
    "../libs/jquery/jssor.slider.mini.js",
    "../libs/vakata-jstree-92ac4c8/dist/jstree.min.js",
    "../libs/bootstrap3dialog/js/bootstrap-dialog.js",
    "../libs/blockui-master/jquery.blockUI.js",
    "../libs/require.js-2.1.11/require.min.js",
    "../libs/tooltipster-master/js/jquery.tooltipster.js",


    "../libs/require.js-2.1.11/underscore.js",
    "../libs/require.js-2.1.11/json2.js",
    "../libs/adminlte/js/app.js",
    "../libs/ripple/ripple.js",
    "../libs/highchart/js/highcharts.js",
    "../libs/highchart/js/modules/exporting.js",

    "../libs/bootstrap/js/bootstrap-datepicker.js",
    "../libs/bootstrap/locales/bootstrap-datepicker.id.min.js",
    "../libs/bootstrap/bootstrap-contextmenu.js",
    
    "../libs/jQuery-slimScroll-1.3.7/jquery.slimscroll.js",
    "../libs/jquery/jquery.svg3dtagcloud.js",
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
    // Client's cache IS current, so we just respond '304 Not Modified'.
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime ).' GMT', true, 304);
} else {
    // Image not cached or cache outdated, we respond '200 OK' and output the image.
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime ).' GMT', true, 200);
    //header('Content-Length: '.filesize($fn),
}

header('Content-Type: text/javascript');

for($i=0; $i<count($fileList); $i++){
    if( strpos($fileList[$i], 'foot-singleton')===false ){
        echo ( file_get_contents($fileList[$i]) );
        echo "//--------------------------------delimiter\r\n\r\n";

    }
}

?>
