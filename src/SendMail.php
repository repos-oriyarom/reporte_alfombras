<?php 

date_default_timezone_set( 'America/Toronto' );
include_once( 'utils/class.phpmailer.php' );
include_once( "utils/class.smtp.php" );

$mail = new PHPMailer();
$body = $mail->getFile( __DIR__.'/templates/correo-reporte.html' );
$body = eregi_replace( "[\]", '', $body );
//$body = " $TextoCuerpo ";
$mail->AltBody = "";
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = "mail.adcomx.net"; // SMTP server
$mail->SMTPSecure = "ssl";
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = "chuy@adcomx.net";
$mail->Password = "(Chuy2023)";
$mail->From = "chuy@adcomx.net";
$mail->FromName = "ReportesCasino ";
$mail->AddReplyTo = "ReportesCasino";
$unwantedArray = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
$cleanedAtr = strtr( $dataToProcess['data'][0], $unwantedArray );
$mail->Subject = "Reportes Casino ".$cleanedAtr." - ".$dataToProcess['data'][1];
$mail->MsgHTML( $body );

$fileTosendPath = $fname;
$nombreArchivo = $cleanName;
if ( file_exists($fileTosendPath )) {
    $mail->AddAttachment( $fileTosendPath, $nombreArchivo );
}

$correosTosend[] = "alberto.yarto@me.com";
$correosTosend[] = "jesus.cortez@safeoutsourcing.com.mx";

foreach ($correosTosend as $key => $value) {
    # code...
    $mail->AddAddress( $value, $value );
}

if ( !$mail->Send() ) {
    echo "Error al enviar " . $mail->ErrorInfo;
} else {
    //echo "¡Correo Eviado Correctamente!";
/*     if($_GET['option']==='1')
        echo('<br/><br/><br/><div class="col-sm-6">
				<div class="form-actions d-flex justify-content-end">
					<a href="https://tunomina.com.mx/chuy/index.php?option=1" class="btn icon btn-agregarAbb"><i data-feather="plus-circle"></i> Regresar</a>
				</div>
			</div>'); */
    if($_GET['option']==='1'){
        //$fileTosendPath;
        $jsonRsp["status"] = true;
        $jsonRsp["message"] = "¡Correo Eviado Correctamente!";
        $jsonRsp["data"]["uri"] = $fileTosendPath;
        $jsonRsp["data"]["name"] = $nombreArchivo;
        echo json_encode($jsonRsp);

    }
    else
        echo "¡Correo Eviado Correctamente!";
    //Borrar PDF
}

?>