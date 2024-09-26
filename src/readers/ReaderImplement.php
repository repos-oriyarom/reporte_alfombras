<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//include('utils/ReaderUtils.php');

include('MailReader.php');
include('WebReader.php');

class ReaderImplement{
    public static $ELEMENTS_BY_PAGE = 4;
    protected $modeToRead = 0;
    public function main(){
        //echo '\n\t Modo de lectura: '.$this->modeToRead.' \n'; 
        switch ($this->modeToRead) {
            case 1:
                # code...
                return $this->getFormatImgAndDataFromWeb();
                //break;
            
            case 0:
            default:
                # code...
                return $this->getFormatImgAndDataFromEmail();
               //break;
        }
    }

    public function setModeToRead($mode){
        $this->modeToRead = $mode;
    }
    
    function getFormatImgAndDataFromWeb(){
        if($dataToBuildReports = readFromWeb()) {

            $completePage = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'/></head><body>";
            $subjectInfo = [];
            if($subjectInfo = $this->segmentSubject($dataToBuildReports['subject'][0]))
                if(count($subjectInfo)>0)
                    foreach ($subjectInfo as $key => $value) {
                        # code...
                        $completePage .= "<br /><h3>Formated subject info: [$value]</h3><br />";
                    }
            $completePage .= "<br /><h3>Count horizontal tables: ".count($dataToBuildReports['horizontal'])."</h3><br />";
            $tablesToAdd = $this->processArrayImage($dataToBuildReports['horizontal'],[]);
            $completePage .= "<br /><h3>Count vertical tables: ".count($dataToBuildReports['vertical'])."</h3><br />";
            $tablesToAdd = $this->processArrayImage($dataToBuildReports['vertical'],$tablesToAdd,'vertical');
        
            if(count($tablesToAdd)>0)
                foreach ($tablesToAdd as $key => $value) {
                    # code...
                    $completePage .= $value;
                }
        
            $completePage .= "</body></html>";
            //echo "$completePage";
            return ['data'=>$subjectInfo,'tables'=>$tablesToAdd];
        }
        return false;
    }

    function getFormatImgAndDataFromEmail(){
        if($dataToBuildReports = readFromEmail()) {
    
            //echo "<br />dataToBuildReports : ".var_export($dataToBuildReports,true)."<br />";
            $completePage = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'/></head><body>";
            $subjectInfo = [];
            if($subjectInfo = $this->segmentSubject($dataToBuildReports['subject'][0]))
                if(count($subjectInfo)>0)
                    foreach ($subjectInfo as $key => $value) {
                        # code...
                        $completePage .= "<br /><h3>Formated subject info: [$value]</h3><br />";
                    }
            $completePage .= "<br /><h3>Count horizontal tables: ".count($dataToBuildReports['horizontal'])."</h3><br />";
            $tablesToAdd = $this->processArrayImage($dataToBuildReports['horizontal'],[]);
            $completePage .= "<br /><h3>Count vertical tables: ".count($dataToBuildReports['vertical'])."</h3><br />";
            $tablesToAdd = $this->processArrayImage($dataToBuildReports['vertical'],$tablesToAdd,'vertical');
        
            if(count($tablesToAdd)>0)
                foreach ($tablesToAdd as $key => $value) {
                    # code...
                    $completePage .= $value;
                }
        
            $completePage .= "</body></html>";
            //echo "$completePage";
            return ['data'=>$subjectInfo,'tables'=>$tablesToAdd];
        }
        return false;
    }
    
    function segmentSubject($dataToSegment){
        
        if(isset($dataToSegment)){ 

            //$dataToSegment = strtolower($dataToSegment);        
            //echo '<br /><h3>DATATOSEGMENT pre: </h3><br /><pre>' , var_dump($dataToSegment) , '</pre>';
            if(stripos($dataToSegment,'?') !== false)
                $dataToSegment = str_replace('?','',$dataToSegment);
            if(stripos($dataToSegment,'=') !== false)
                $dataToSegment = str_replace('=','',$dataToSegment);
            if(stripos($dataToSegment,'utf-8q') !== false)
                $dataToSegment = str_replace('utf-8q','',$dataToSegment);
            if(stripos($dataToSegment,'UTF-8Q') !== false)
                $dataToSegment = str_replace('UTF-8Q','',$dataToSegment);
            if(stripos($dataToSegment,'_') !== false)
                $dataToSegment = str_replace('_',' ',$dataToSegment);
            if(stripos($dataToSegment,'utf-8Q') !== false)
                $dataToSegment = str_replace('utf-8Q','',$dataToSegment);
            //echo '<br /><h3>DATATOSEGMENT post: </h3><br /><pre>' , var_dump($dataToSegment) , '</pre>';
            
            //$completePage .= "<br /><h3>Raw subject: ".$dataToBuildReports['subject'][0]."</h3><br />";
            //$cleanData = $this->trimSubject($dataToSegment[0]);
            //$completePage .= "<br /><h3>Trim: $cleanData</h3><br />";
            //$dataSegmented = explode('?',$cleanData);
            //$completePage .= "<br /><h3>Segmented data: [".$dataSegmented[0]."] [".$dataSegmented[2]."]</h3><br />";

            //echo "<br />".var_export($dataToSegment,true);
            $dataSegmentedSubject = [];
            if(strpos($dataToSegment, "/") !== false)
                $dataSegmentedSubject = explode("/",$dataToSegment);
/*             else if(strpos($dataSegmented[2], '/ ') !== false)
                $dataSegmentedSubject = explode('/ ',$dataSegmented[2]); */
            //echo "<br />".var_export($dataSegmentedSubject,true);
            if(count($dataSegmentedSubject)>0){
                
                //$completePage .= "<br /><h3>Segmented subject: [".$dataSegmentedSubject[0]."] [".$dataSegmentedSubject[1]."]</h3><br />";
                foreach ($dataSegmentedSubject as $key => $value) {
                    # code...
                    //$dataSegmentedSubject[$key] = str_replace('_', ' ', $value);
                    $dataSegmentedSubject[$key] = $value;
                }
                //$completePage .= "<br /><h3>Formated subject info: [".$dataSegmentedSubject[0]."] [".$dataSegmentedSubject[1]."]</h3><br />";
                //echo "<br />".var_export($dataSegmentedSubject,true);
                return $dataSegmentedSubject;
            }
    
        } 
        
        return false;
    }
    
    function trimSubject($toCleanData){
        $trimElements = ['=?','?='];
        foreach ($trimElements as $key => $value) {
            # code...
            if(strpos($toCleanData, $value) !== false){
                $toCleanData = str_replace($value, '', $toCleanData);
            }
        }
        return $toCleanData;
    }
    
    
    function processArrayImage($dataArray,$tablesToPrint, $mode = 'horizontal'){
        $arrayLength = count($dataArray);
    
        if($arrayLength>0){
            $pageNumbers = 1;
            $plusPage = false;
            if($arrayLength>self::$ELEMENTS_BY_PAGE){
                $pageNumbers = round($arrayLength/self::$ELEMENTS_BY_PAGE);
                $plusPage = (($arrayLength%self::$ELEMENTS_BY_PAGE)!==0)?true:false;
        
            }
            $actualElement = 1;

            //$actualTable = $this->getActualTable($mode, $arrayLength);
            $actualTable = $this->getActualTableByPages($plusPage, $pageNumbers, $mode, $arrayLength);
            
            //echo '<br /><h3>ARRAYLENGTH: </h3><br /><pre>' . var_dump($arrayLength) . '</pre>'; 
            //echo '<br /><h3>MODE: </h3><br />' . var_dump($mode) . '<br />'; 

            foreach ($dataArray as $keyImg => $valueImg) {
                # code...
                //echo "<br /> key:[$keyImgHor]element:[$actualElement]page:[$pageNumbers] valueImg: ".var_export($valueImgHor['dataB64'],true)."<br />";
                switch ($mode) {
                    case 'vertical':
                        # code...
                        $imgToSet = "<img class='picture-element' style='width:400px; height: 430px; overflow: hidden;aspect-ratio: attr(width) / attr(height);' src='data:image/jpeg;base64,".$valueImg['dataB64']."'/>";
                        break;
                    
                    case 'horizontal':
                    default:
                        $imgToSet = "<img class='picture-element' style='width:400px; height: 430px; overflow: hidden;aspect-ratio: attr(width) / attr(height);' src='data:image/jpeg;base64,".$valueImg['dataB64']."'/>";
                        # code...
                        break;
                }
                //echo "<br /><div><h3>key:[$keyImgHor]element:[$actualElement]page:[$pageNumbers]</h3><br />$imgToSet</div><br />";
                switch ($actualElement) {
                    case 1:
                    case 3:
                        # code...
                        $actualTable .= "<tr style='display: flex; flex-wrap: wrap; align-content: center;'>".
                                "<td style='display: flex; align-content: center;'>$imgToSet</td>";
                        break;
                    
                    case 2:
                    case 4:
                        # code...
                        $actualTable .= "<td style='display: flex; align-content: center;margin-left:2.9rem;padding-left:2.6rem;'>$imgToSet</td>"
                                            ."</tr>";
                        break;
                    default:
                        # code...
                        break;
                }
                
                if($actualElement===self::$ELEMENTS_BY_PAGE || $keyImg === $arrayLength-1){                
                    $actualElement = 0;
                    $pageNumbers -= 1;
                    $actualTable .= "</table><br />";
                    //$completePage .= $actualTable;
                    $tablesToPrint[] = $actualTable;
                    //$actualTable = "<br /><table border='0' >";
                    //$actualTable = $this->getActualTable($mode, $arrayLength, $keyImg);
                    $actualTable = $this->getActualTableByPages($plusPage, $pageNumbers, $mode, $arrayLength, $keyImg+1);
                    if($pageNumbers===0.0 && $plusPage){
                        $pageNumbers = 1;
                        $plusPage = false;
                    }
                }
                $actualElement += 1;
            }
        }
        return $tablesToPrint;
    }

    public function getActualTable($mode, $arrayLength, $passedKeys = 0){
/*         echo '<br /><h3>ARRAYLENGTH: </h3><br /><pre>' . var_dump($arrayLength) . '</pre>'; 
        echo '<br /><h3>PASSEDKEYS: </h3><br /><pre>' . var_dump($passedKeys) . '</pre>'; 
        $nextElements = $arrayLength-$passedKeys;
        echo '<br /><h3>NEXTELEMENTS: </h3><br /><pre>' . var_dump($nextElements) . '</pre>'; 
        if(strcmp ('vertical' , $mode )===0)
            return ($nextElements===1||$nextElements===2)?"<br /><table border='0' style='margin-left:6.8rem;padding-left:5.3rem;-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;'>":
                "<br /><table border='0' style='margin-left:7.2rem;padding-left:5.6rem;-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;'>";
        else 
            return "<br /><table border='0' style='-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;'>"; */
        return "<br /><table border='0' style='-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;'>"; 

    }
    public function getActualTableByPages($plusPage, $pageNumbers, $mode = 'horizontal', $arrayLength, $passedKeys = 0){

/*                 if(strcmp ('vertical' , $mode )===0){
                    echo '<br /><h3>MODE: </h3><br /><pre>' . var_dump($mode) . '</pre>'; 
                    echo '<br /><h3>PLUSPAGE: </h3><br /><pre>' . var_dump($plusPage) . '</pre>'; 
                    echo '<br /><h3>PAGENUMBERS: </h3><br /><pre>' . var_dump($pageNumbers) . '</pre>';
                    $trueOrFalse = ($pageNumbers===1.0)?true:false;
                    echo '<br /><h3>TRUEORFALSE: </h3><br /><pre>' . var_dump($trueOrFalse) . '</pre>';
                    if($trueOrFalse && $plusPage){
                        echo '<br /><h3>PAGENUMBERS INSIDE: </h3><br /><pre>' . var_dump($pageNumbers) . '</pre>';

                        return "<table border='0' style='-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;position:relative;'>";
                    } else 
                        return "<br /><table border='0' style='margin-left:7.9rem;padding-left:7.6rem;-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;position:relative;'>";
                } else  */
                $diferenceToImgs = $arrayLength - $passedKeys;
                //echo '<br /><h3>DIFERENCETOIMGS: </h3><br /><pre>' . var_dump($diferenceToImgs) . '</pre>'; 

                if($diferenceToImgs === 2){

                    //echo '<br /><h3>DIFERENCETOIMGS INSIDE: </h3><br /><pre>' . var_dump($diferenceToImgs) . '</pre>'; 
                    return "<br /><table border='0' style='-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'>"; 

                }
                if($diferenceToImgs === 1){

                    //echo '<br /><h3>DIFERENCETOIMGS INSIDE: </h3><br /><pre>' . var_dump($diferenceToImgs) . '</pre>'; 
                    return "<br /><table border='0' style='margin-left:4.5rem;-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'>"; 

                }
                else
                    return "<br /><table border='0' style='margin-left:5.0rem;padding-left:2.1rem;-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'>"; 
                //return "<br /><table border='0' style='-webkit-flex-wrap: wrap; -ms-flex-wrap: wrap; flex-wrap: wrap;'>"; 
        
            }
}



?>