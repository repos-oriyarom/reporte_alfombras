<?php
//main access tio process
/* error_reporting(E_ALL);
ini_set('display_errors', '1'); */
//echo "\n\t<br /> <<<<<<< Welcome to PDF Maker Casino Reports >>>>>>> <br />\n";
//echo "\n<br />-----------------------------v1.0.0-----------------------------<br />\n";


//echo(var_export($_GET, true));

if (!isset($_GET['option'])) 
    $_GET['option'] = '0';

switch ($_GET['option']) {
    case '1':
        # code...
        //echo('Reporter on web');
        //echo(var_export($_POST, true));
        include('src/views/web_reports.php');
        break;
    
    case '0':
    default:
        # code...
        echo('Reporter on mail');
        include('src/InitMakerPDFCasinoReports.php');
        break;
}
?>