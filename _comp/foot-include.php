
<script src="_comp/foot-singleton-js.php" type="text/javascript"></script>
<script src="common/plugin-handsontable-select2.js" type="text/javascript"></script>
<script src="common/plugin-handsontable-select2-multiple.js" type="text/javascript"></script>


<script src="common/common.js"></script>
<script src="libs/tinymce/js/tinymce/tinymce.js"></script>
<script src="common/plugin-handsontable-tinymce.js"></script>


<?php 
@$cycle1=$_SESSION['login']['cycle'];
?>

<script type="text/javascript">
    var cycle1='<?php echo $cycle1?>';
    $(function(){
        var t1=typeof getLast10;
        //getLast10(); 
        //debugger;
        if (typeof getLast10 === 'function') { 
            getLast10(); 
            setInterval(function(){
                updateActLog();
                },5000);
        }


        /**
        * change skin based on cycle
        */
        if(cycle1=='musrenbangkec'){
            //
        }
        else if(cycle1=='musrenbangkab'){
            //$('body').removeClass('skin-blue');
            //$('body').addClass('skin-purple');
            //var aa=$('.main-header').css('background-color');
            //debugger;
            //$('.main-header').css('background-color','red');
        }
    });

</script>