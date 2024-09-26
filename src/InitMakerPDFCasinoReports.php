<?php


//echo "\n\t<br />At Initializer...<br /> \n";
include("templates/AlfombraCasinoReport.html.php");
include('readers/ReaderImplement.php');
/* error_reporting(E_ALL);
ini_set('display_errors', '1'); */

$jsonRsp = array("status"=>false,"message"=> "", "data"=> array());
try {
    //echo "\n\t<br /> Se intentara escribir archivo de prueba<br />... \n";

    $readerImplement = new ReaderImplement();
    switch ($_GET['option']) {
        case '1':
            # code...
            //echo('Reporter on web');
            //echo(var_export($_POST, true));
            $readerImplement->setModeToRead(1);
            include("../mpdf/mpdf.php");
            //break;
            break;
        
        case '0':
        default:
            # code...
            echo('Reporter on mail');
            include("mpdf/mpdf.php");
            //$readerImplement = new ReaderImplement();
            //$dataToProcess = $readerImplement->main();
            break;
    }

    $dataToProcess = $readerImplement->main();

    if($dataToProcess===false){
        //echo "Error Processing Email";
        $jsonRsp["message"] = "Error Processing Email";
        return json_encode($jsonRsp);
    }

    $header_test = str_replace('%MonthSpaceYear%',$dataToProcess['data'][1],$header_test);
    $header_test = str_replace('%ImageLogo%',getImageToLogo($dataToProcess['data'][0]),$header_test);

   //echo "<br />dataToProcess : ".var_export($header_test,true)."<br />";
    //echo "<br />dataToProcess : ".var_export($dataToProcess,true)."<br />";
    ob_start();
    $mpdf=new mPDF('UTF-8', 'A4', '', '', 10, 10, 10, 10, 10, 5); 
    $mpdf->mirrorMargins = true;
    
    $mpdf->SetDisplayMode('fullpage','two');
    ob_clean(); 

    $stylesheet = file_get_contents(__DIR__.'/templates/AlfombraCasinoReport.style.css');
    $mpdf->WriteHTML($stylesheet,1);


    $mpdf->SetHTMLHeader($header_test);
    $mpdf->SetHTMLHeader($header_test,'E');
    //$mpdf->SetHTMLFooter($footer);
    $mpdf->SetFooter(array(
            'R' => array(
                'content' => 'Reporte Alfombras {PAGENO}',
                'font-family' => 'monospace',
                'font-style' => '',
                'font-size' => '10',
            ),
            'line' => 1,		/* 1 to include line below header_test/above footer */
        ), 'O'
    );
    $mpdf->SetFooter(array(
            'L' => array(
                'content' => '{PAGENO} Reporte Alfombras ',
                'font-family' => 'monospace',
                'font-style' => '',
                'font-size' => '10',
            ),
            'line' => 1,		/* 1 to include line below header_test/above footer */
        ), 'E'	/* defines footer for Even Pages */
    );
/*     $mpdf->WriteHTML($body_init,2);
    if(count($dataToProcess['tables']))
    foreach ($dataToProcess['tables'] as $key => $value) {
        # code...
        if($key>0)
            $mpdf->AddPage();
        $actualBody = str_replace('%subsidiary%',$dataToProcess['data'][0],$tableContainer);
        $actualBody = str_replace('%TableImgDocumentalReport%',$value,$actualBody);
        $mpdf->WriteHTML($actualBody,2);
    } */
    if(count($dataToProcess['tables']))
        foreach ($dataToProcess['tables'] as $key => $value) {
            # code...
            if($key>0)
                $mpdf->AddPage();
            $actualBody = str_replace('%subsidiary%',$dataToProcess['data'][0],$body);
            $actualBody = str_replace('%TableImgDocumentalReport%',$value,$actualBody);
            $mpdf->WriteHTML($actualBody,2);
        }
    $basePages = baseImagesToReport($dataToProcess['data'][0]);
    if(count($basePages))
        foreach ($basePages as $key => $bodyProduct) {
            # code...
            $mpdf->AddPage();
            $mpdf->WriteHTML($bodyProduct,2);
        }
    
    //$mpdf->WriteHTML($body_end,2);

    //$fileName = date('Ymd_Hi');
    $cleanDate = str_replace('ALFOMBRAS','',$dataToProcess['data'][1]);
    $cleanName = strtolower($dataToProcess['data'][0].$cleanDate);
    $cleanName = str_replace(' ','_',$cleanName);
    $cleanName = 'reporte_alfombras_'.$cleanName.'.pdf';
    //$cleanName = "fecha_nombre";
    //echo "<br /> CleanName: $cleanName <br /> ";
    //var_dump($cleanName);
    $dir = __DIR__.'/../assets/PDFsReportes/';

    $fname = $dir.$cleanName;
    //echo "<br /> fname: $fname <br /> ";
    //var_dump($fname);
    ob_end_clean();
    $pdfcode = $mpdf->Output($fname, 'F');

    require_once('SendMail.php');

/*     $fp = fopen( $fname, 'w' );
    if(fwrite( $fp, $pdfcode ))
        echo "Se crea el archivo";
    else
        echo "No Se crea el archivo";

    fclose( $fp ); *///End Output Buffering
} catch (MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    //echo $e->getMessage();
    $jsonRsp["message"] = $e->getMessage();
    return json_encode($jsonRsp);
}
exit;

?>