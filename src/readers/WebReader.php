
<?php
header('Content-Type: text/html; charset=UTF-8');
include('utils/ReaderUtils.php');
//define('BASE_PATH_TOSAVE',__DIR__.'/../../assets/img/documental/Casino/Reportes/BenitoJuarez/');
function readFromWeb(){

    $errors = []; // Store errors here
    $fileExtensionsAllowed = ['jpeg','jpg','png']; 

    //echo '<br/>\n\t En lector web: '.var_export($_POST['selSolicitudCliente'],true).' \n<br/>'; 
    //echo '<br/>\n\t En lector web: '.var_export($_POST['txtFechaReporte'],true).' \n<br/>'; 
    //echo(var_export($_FILES['userfile'], true));
    //echo(var_export($_POST['userfile'], true));

    $imgArrayToEnsembleReport = [
        'subject' => [],
        'horizontal' => [],
        'vertical' => []
    ];
    //$datetime = DateTime::createFromFormat('Y-m-d', $_POST['txtFechaReporte']);
    //echo '<br/>\n\t Fecha formato: '.var_export($datetime,true).' \n<br/>'; 
    //setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
    setlocale(LC_ALL, 'es_ES');
    $dateToReport = str_replace("/","-",$_POST['txtFechaReporte']);
    //echo $datetime->format('F').' \n<br/>';
    $monthNameReport = strftime('%B', strtotime($dateToReport));

    //echo $datetime->format('M').' \n<br/>';  // Output: Jun
    $yearReport = ' '.strftime('%Y', strtotime($dateToReport));

    $monthNameReport = translateMonthName($monthNameReport);
    //echo $datetime->format('Y').' \n<br/>'; 
    //echo $year = strftime('%B $Y', strtotime($dateToReport)).' \n<br/>';

    if($monthNameReport !== false)
        if(isset($_FILES['userfile'])){
            $imgArrayToEnsembleReport['subject'][] = $_POST['selSolicitudCliente'].'/Alfombras '.$monthNameReport.$yearReport;
            foreach ($_FILES['userfile']['tmp_name'] as $fileKey => $filePath) {
                # code...
/*                 echo '<br/>\n\t Elemento: '.var_export($fileKey,true).' \n<br/>'; 
                echo(var_export($filePath, true));
                echo '<br/>\n\t Elemento: '.var_export($_FILES['userfile']['name'][$fileKey],true).' \n<br/>';  */
                //echo(var_export($fileSpot, true));
                //echo '<br/>\n\t Elemento: '.var_export($_FILES['userfile']['type'][$fileKey],true).' \n<br/>'; 
                //echo(var_export($fileSpot, true));

                //$type = pathinfo($filePath, PATHINFO_EXTENSION);
                $decoded_attachment = file_get_contents($filePath);
                $encoded_attachment = base64_encode($decoded_attachment);
                $imgArrayToEnsembleReport = evalAndSave($decoded_attachment, BASE_PATH_TOSAVE, $imgArrayToEnsembleReport, $encoded_attachment, $_FILES['userfile']['name'][$fileKey]);
    
            }
            return $imgArrayToEnsembleReport;
        }

    return $imgArrayToEnsembleReport = false;
}


?>
