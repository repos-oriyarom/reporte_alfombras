<?php

header('Content-Type: text/html; charset=UTF-8');
include('src/utils/ReaderUtils.php');
define('BASE_PATH_TOSAVE',__DIR__.'/../../assets/img/documental/Casino/Reportes/BenitoJuarez/');
function readFromEmail(){
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    //$inbox = imap_open("{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX", "casavi369@gmail.com", "t3ST98n_X") or die("No Se Pudo Conectar Al Servidor: " . imap_last_error());
    $inbox = imap_open("{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX", "yulisslop.mar@gmail.com", "yespoint1") or die("No Se Pudo Conectar Al Servidor: " . imap_last_error());
    //$inbox = imap_open("{mail.oriyarom.com:993/imap/ssl/novalidate-cert}INBOX", "chuy@oriyarom.com", "S4f3_0uT<13nXVi>iY26") or die("No Se Pudo Conectar Al Servidor: " . imap_last_error());
    echo '<br /><h3>INBOX: </h3><br /><pre>' , var_dump($inbox) , '</pre>'; 
/*     if($inbox===false || $inbox===NULL){
        $errors = imap_errors();
        echo '\n\t Imposible leer este correo: '.var_export($errors, true).' \n'; 
        return 0;
    }  */
    /* Search emails from gmail inbox*/
/* 
    echo '\n\t Debugging mail: '.var_export($inbox, true).' \n'; 
    return 1; */
    $mails = imap_search($inbox, '');
    $imgArrayToEnsembleReport = [
        'subject' => [],
        'horizontal' => [],
        'vertical' => []
    ];
    
  
    //echo '<br /><h3>MAILS: </h3><br /><pre>' , var_dump($mails) , '</pre>'; 
    $email_number = count($mails);
    //for ($email_number = 32; $email_number < count($mails); $email_number++) {
  
        //echo '<br /><h3>EMAIL_NUMBER: </h3><br /><pre>' , var_dump($email_number) , '</pre>'; 
        $structure = imap_fetchstructure($inbox, $email_number);
        //echo '<br /><h3>STRUCTURE: </h3><br /><pre>' , var_dump($structure) , '</pre>'; 
        $flattenedParts = flattenParts($structure->parts);
        //echo '<br /><h3>FLATTENEDPARTS: </h3><br /><pre>' , var_dump($flattenedParts) , '</pre>'; 
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        //echo '<br /><h3>OVERVIEW: </h3><br /><pre>' , var_dump($overview) , '</pre>'; 
        
        $seen = $overview[0]->seen;
        echo '\n\t El correo fue visto?: '.$seen.' \n'; 
        echo '\n\t overview?: '.var_export($overview,true).' \n'; 
        if($seen != 0){
            echo '\n\t El correo ya fue visto: '.$seen.' \n'; 
            return 0;

        } 

        if(count($overview)>0)
            if(isset($overview[0]->subject)){

                $imgArrayToEnsembleReport['subject'][] = quoted_printable_decode($overview[0]->subject);
            }
        //echo '<br /><h3>SUBJECT: </h3><br /><pre>' , var_dump(quoted_printable_decode($overview[0]->subject)) , '</pre>'; 
    
        foreach($flattenedParts as $partNumber => $part) {

            echo '\n\t part?: '.var_export($part,true).' \n'; 
        //if($seen != 0){
            switch($part->type) {
                
                case 0:
                    // the HTML or plain text part of the email
/*                     $message = returnEncodeAttachment($inbox, $email_number, $partNumber, $part->encoding);
                    echo '<br /><h3>MESSAGE: </h3><br /><pre>' , var_dump($message) , '</pre>'; */ 
                    // now do something with the message, e.g. render it
                break;
            
                case 1:
                    // multi-part headers, can ignore
            
                break;
                case 2:
                    // attached message headers, can ignore
                break;
            
                case 3: // application
                case 4: // audio
                case 5: // image
                case 6: // video
                case 7: // other
                    $filename = getFilenameFromPart($part);
                    if($filename) {
                        echo '<br /><h3>FILENAME: </h3><br /><pre>' , var_dump($filename) , '</pre>'; 

                        // it's an attachment
                        $attachment = returnEncodeAttachment($inbox, $email_number, $partNumber, $part->encoding);
                        //echo '<br /><h3>ATTACHMENT: </h3><br /><pre>' , var_dump($attachment) , '</pre>'; 
                        $decoded_attachment = base64_encode($attachment);
                        //echo '<br /><h3>DECODED_ATTACHMENT: </h3><br /><pre>' , var_dump($decoded_attachment) , '</pre>'; 
                        //echo "\n\t Se guardara: $decoded_attachment\n";
        
                        $filename = BASE_PATH_TOSAVE.$part->parameters[0]->value;
                        $imgArrayToEnsembleReport = evalAndSave($attachment, BASE_PATH_TOSAVE, $imgArrayToEnsembleReport, $decoded_attachment, $filename);
                        // now do something with the attachment, e.g. save it somewhere
                    }
                    else {
                        // don't know what it is
                    }
                break;
            
            }
            
        }
    
    //echo '<br /><h3>IMGARRAYTOENSEMBLEREPORT: </h3><br /><pre>' , var_dump($imgArrayToEnsembleReport) , '</pre>'; 

    imap_close($inbox);
    return $imgArrayToEnsembleReport;
}


function evalAndSave($decoded_attachment, $baseDirToSave, $imgArrayToEnsembleReport, $attachment, $filename){
    // Guardar el archivo adjunto
    //echo '<br/>\n\t Evaluar y guardar: '.var_export($filename,true).' \n<br/>'; 
    if($imgOrientation = check_base64_image($decoded_attachment,$baseDirToSave)){
        //echo "\n\t <br />Se guardara: $filename\n";
        switch ($imgOrientation) {
            case 1:
            case 3:
                $imgArrayToEnsembleReport['horizontal'][] = ['dataB64'=>$attachment];
                break;
            
            case 2:
                $imgArrayToEnsembleReport['vertical'][] = ['dataB64'=>$attachment];
                break;
            default:
                # code...
                break;
        }
/*         file_put_contents($filename, $decoded_attachment);
        echo "\n\t Archivo adjunto guardado: $filename\n"; */
    }
    return $imgArrayToEnsembleReport;
}

function processUnseenMsg($structure,$inbox, $email_number, $imgArrayToEnsembleReport){
    //echo "<br />structure PARYS2 IN: ".var_export($structure,true);
    foreach ($structure->parts as $part_number => $part) {
        if(isset($part->disposition)){
            //echo "<br />part disposition: ".var_export($part->disposition,true);
/*             $actualpartNumber =$part_number + 1;
            $actualpartNumber = ''.$actualpartNumber; */
            if ($part->disposition == 'attachment') {
                $filename = BASE_PATH_TOSAVE.$part->parameters[0]->value;
                //echo "\n\t Se guardara: $filename\n";
                $attachment = imap_fetchbody($inbox, $email_number, $part_number + 1);
                $decoded_attachment = base64_decode($attachment);
                //echo "\n\t Se guardara: $decoded_attachment\n";

                $imgArrayToEnsembleReport = evalAndSave($decoded_attachment, BASE_PATH_TOSAVE, $imgArrayToEnsembleReport, $attachment, $filename);

            }
/*             else if ($part->disposition == 'inline') {
                //echo "<br />part IN: ".var_export($part->encoding,true);
                $filename = BASE_PATH_TOSAVE.$part->parameters[0]->value;
                $decoded_attachment = returnEncodeAttachment($inbox, $email_number,''.$actualpartNumber.'.', $part->encoding);
                $attachment = base64_encode($decoded_attachment);
                //echo "<br />attachment IN: ".var_export($decoded_attachment,true);

                $imgArrayToEnsembleReport = evalAndSave($decoded_attachment, BASE_PATH_TOSAVE, $imgArrayToEnsembleReport, $attachment, $filename);

            } */
        }

    }
    return $imgArrayToEnsembleReport;
}

function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

	foreach($messageParts as $part) {
		$flattenedParts[$prefix.$index] = $part;
		if(isset($part->parts)) {
			if($part->type == 2) {
				$flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
			}
			elseif($fullPrefix) {
				$flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
			}
			else {
				$flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix);
			}
			unset($flattenedParts[$prefix.$index]->parts);
		}
		$index++;
	}

	return $flattenedParts;
			
}

function getFilenameFromPart($part) {

	$filename = '';
	
	if($part->ifdparameters) {
		foreach($part->dparameters as $object) {
			if(strtolower($object->attribute) == 'filename') {
				$filename = $object->value;
			}
		}
	}

	if(!$filename && $part->ifparameters) {
		foreach($part->parameters as $object) {
			if(strtolower($object->attribute) == 'name') {
				$filename = $object->value;
			}
		}
	}
	
	return $filename;
	
}

function returnEncodeAttachment($inbox, $email_number,$part_number, $encoding){

        $data = imap_fetchbody($inbox, $email_number, $part_number);
        //echo "<br />data IN: ".var_export($data,true);
        switch($encoding) {
            case 0: return $data; // 7BIT
            case 1: return $data; // 8BIT
            case 2: return $data; // BINARY
            case 3: return base64_decode($data); // BASE64
            case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case 5: return $data; // OTHER
        }


}
?>