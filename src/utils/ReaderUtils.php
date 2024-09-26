<?php
function check_base64_image($decodedAttachment,$baseDirToSave) {
    $img = false;
    //echo "<br />Temp Name: $baseDirToSave<br />";
    //echo "<br />decodedAttachment: ".var_export($decodedAttachment,true)."<br />";
    if (!$img = imageCreateFromString($decodedAttachment)) {
        return false;
    }

    //echo "<br />Temp Name: $baseDirToSave<br />";
    //echo "<br />decodedAttachment: ".var_export($decodedAttachment,true)."<br />";
    $baseDirToSave.='tmp.png';
    if(!imagepng($img, $baseDirToSave))
        return false;
    
    $info = getimagesize($baseDirToSave);

    //echo "<br />Info: ".var_export($info,true)."<br />";
    //echo '<br /><h3>INFO: </h3><br /><pre>' , var_dump($info) , '</pre>'; 

    unlink($baseDirToSave);

    if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
        switch ($info['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/png':
                return evalImgOrientation($info[0], $info[1]);
                break;
            
            default:
                # code...
                break;
        }
    }

    return false;
}

function evalImgOrientation($widhtImg, $heightImg){
    //echo '<br /><h3>WIDHTIMG: </h3><br /><pre>' , var_dump($widhtImg) , '</pre>'; 
    //echo '<br /><h3>HEIGHTIMG: </h3><br /><pre>' , var_dump($heightImg) , '</pre>'; 

    if($widhtImg > $heightImg)
        return 1;//Horizontal
    else if($widhtImg < $heightImg)
        return 2;//Vertical
    else if($widhtImg === $heightImg)
        return  3; //Square
    else 
        return 0;
}

function translateMonthName($monthName){

    $monthsTrl = [
        'Enero' => 'january',
        'Febrero' => 'february',
        'Marzo' => 'march',
        'Abril' => 'april',
        'Mayo' => 'may',
        'Junio' => 'june',
        'Julio' => 'july',
        'Agosto' => 'august',
        'Septiembre' => 'september',
        'Octubre' => 'october',
        'Noviembre' => 'november',
        'Diciembre' => 'december',
    ];

    foreach ($monthsTrl as $keyMonth => $valueMonth) {
        # code...
        if(strtolower($monthName) == $valueMonth)
            return $keyMonth;
        
    }
    return false;
}
?>