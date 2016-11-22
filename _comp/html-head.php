<head>
    <meta charset="UTF-8">           
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>
        siska lamongan, sippd lamongan, perencanaan pembangunann lamongan, musrenbang, musrenbang kecamatan, musrenbang kabupaten, sistem informasi lamongan, bappeda lamongan
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link href="libs/pace-1.0.0/ball.css" rel="stylesheet">
    <link rel="icon" 
        type="image/png" 
        href="images/fav1.png">

    <script type="text/javascript">

        paceOptions = {
            ajax: false, // disabled
            xdocument: false, // disabled
            eventLag: false, // disabled
        };        

    </script>

    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="_comp/head-singleton-css.php" rel="stylesheet" />

    <script type="text/javascript" src="libs/pace-1.0.0/pace.min.js"></script>
    <script src="libs/jquery/jquery-1.10.2.min.js" ></script>
    <script src="libs/angular-1.4.4/angular.min.js"></script>
    <script type="text/javascript">

        Pace.on('done', function(){
            $(".main-hide").css('opacity','1');
            $(".main-hide").fadeIn('slow');
            //$("#main-header").css('background-color','red');
            <?php
            if(isset($_REQUEST['openlogin'])){
                ?>
                if(typeof openLogin!=='undefined')
                    setTimeout(function(){
                        openLogin('<?php echo $_REQUEST['openlogin'] ?>');

                        },1000);
                <?php
            }
            ?>

        }); 

    </script>

    <style type="text/css">

        .main-hide{
            opacity: 0;
        }

    </style>

</head>
