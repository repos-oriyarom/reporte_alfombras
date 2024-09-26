<?php

$header = "<head><div class='title-container'>
            %ImageLogo%
            <div class='title-txt-container'>".
                "<h3>Reporte Fotográfico</h3>".
                "<h4>%MonthSpaceYear%</h4>".
            "</div>".
        "</div></head>";

$header_test = "<div class='title-container'>
        %ImageLogo%
        <div class='title-txt-container'>".
            "<h3>Reporte Fotográfico</h3>".
            "<h4>%MonthSpaceYear%</h4>".
        "</div>".
    "</div>";

$body_init = "<div style='display: flex;flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'><div class='information-container'>".
        "<br />";
$body_end = "</div></div>";

$tableContainer = "<div id='report-content'><div id='subsidiary-txt-container'><h5>%subsidiary%</h5></div>".
                "<br /><div id='report-img-container' style='margin-left: 1.4rem;flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'>%TableImgDocumentalReport%</div>".
                "</div>";


$body = "<body style='display: flex;flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'><div class='information-container'>".
            "<div id='subsidiary-txt-container'><h5>%subsidiary%</h5></div>".
            "<br />".
            "<div id='report-img-container' style='margin-left: 1.4rem;flex-wrap: wrap; align-content: center; align-items: center; text-align: center;'>%TableImgDocumentalReport%</div>".
        "</div></body>";

$footer = "<div class='basedoc-container'><p>Prueba pie de pagina {PAGENO}<p></div>";

function getImageToLogo($dependency){
    if(isset($dependency))
        switch ($dependency) {
            case 'HIPODROMO':
            case 'LAZ BARRERAS':
            case 'LAZ BARRERAZ':
                # code...
                include("logos/Asecco.logo.php");
                $imgLogoTag = "<div class='logo-img-container'>".
                    "<img  style='padding-top:0.2rem; padding-right:7.0rem;width: 130px;  height: 100px;' src='data:image/jpeg;base64,$logoAsecco'/></div>";

                return $imgLogoTag;
                break;
            
            default:
                # code...
                include("logos/LimpMex.logo.php");
                $imgLogoTag = "<div class='logo-img-container'>".
                    "<img src='data:image/jpeg;base64,$logoLimpMex'/></div>";

                return $imgLogoTag;
                break;
        }
}

function baseImagesToReport($dependency){

    $baseCleanProducts = [];
    include('logos/BaseCleanProducts.logo.php');
    include('logos/BaseCleanProduct2.logo.php');
/*     foreach ($baseProductsImages as $key => $imgToRender) {
        # code...
        $baseCleanProducts[] = "<body><div class='information-container'>".
            "<div id='subsidiary-txt-container'><h5>%subsidiary%</h5></div>".
            "<div><img src='data:image/png;base64,$imgToRender'/><div></div></body>";
    } */
    $baseCleanProducts[] = "<div style='margin-top:20rem;'>".
    "<div id='subsidiary-txt-container' style='padding-top:7rem;'><h5>$dependency</h5></div>".
    "<div><img style='padding-top:4rem; width: 590px;  height: 780px;' src='data:image/jpeg;base64,$baseProductsImages1'/><div></div>";
    $baseCleanProducts[] = "<div style='margin-top:20rem;'>".
    "<div id='subsidiary-txt-container' style='padding-top:7rem;'><h5>$dependency</h5></div>".
    "<div><img style='padding-top:4rem; width: 590px;  height: 780px;' src='data:image/jpeg;base64,$baseProductsImages2'/><div></div>";
    return $baseCleanProducts;

}

?>