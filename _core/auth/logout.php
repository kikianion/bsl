<?php
session_destroy();
session_start();
$_SESSION['msg'] = 'Anda telah logout dari sistem';
redirect_out("index.php");
?>