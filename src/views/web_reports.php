<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Alfombras</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <style>
        #contenedor_cargar1
        {
            
            background-color:rgba(204,204,204,0.6);
            height:100%;
            width:100%;
            position:fixed;
            -webkit-transition:all 1s ease;
            -o-transition:all 1s ease;
            transition:all 1s ease;
            z-index:10000;
        }

    </style>
</head>
<body>

<div id="contenedor_cargar1" style="visibility:hidden; opacity:0">
	<div id="cargar1"></div>
</div>
	
<div id="app">   

    <div class="main-content container-fluid">
        <div class="page-title" style="margin: 0 auto;">
            <div class="card" style="margin: 0 auto;">
                <div class="card-header" style="margin: 0 auto;">
                    <h2 class="card-title" style="margin: 0 auto;">Reporte Alfombras</h2>
                </div>
            </div>
        </div>
        <br />
        <section id="input-style">
            <div class="card">
            <div class="card-body">
                <div >
                    <form id="formuploadajax" method="post" enctype="multipart/form-data">
                        <br />
                        <div class="col-sm-7">
                            <div class="form-group">
                            <?php
                                echo '<label class="form-label"for="selSolicitudCliente">Zona Reporte</label>';
                                $opcionesTipoSolicitud = array(
                                    "hipodromo"=>"Hipódromo",
                                    "laz barreras"=>"Laz Barreras",
                                    "centro civico"=>"Centro Cívico",
                                    "lazaro cardenas"=>"Lázaro Cárdenas",
                                    "rosarito"=>"Rosarito",
                                    "benito juarez"=>"Benito Juárez"
                                );

                                echo '<select class="form-select" id="selSolicitudCliente" name="selSolicitudCliente" onChange="">';

                                foreach ($opcionesTipoSolicitud as $opcionKey => $opcion) {

                                    echo '<option value="' . $opcion . '">' . $opcion . '</option>';

                                }
                                echo '</select>';
                            ?>
                            </div>

                            <br />
                            <div class="form-group">
                                <label class="form-label" for="txtFechaReporte">Fecha Reporte</label>
                                <input type="date" id="txtFechaReporte" name="txtFechaReporte" value="" class="form-control round" placeholder="Fecha Reporte">
                            </div>
                            <br />
                            <div class="form-group" style="margin-top: -1.45em;">			
                                <label class="form-label" for="userfile[]">Favor de Cargar el Directorio de Imagenes(jpg, jpeg, png)</label>
                            </div>
                            <div class="form-file" id="upload-evidence" style="margin-top: -0.65em;">
                                <br/>
                                <input class="form-file-input" id="userfile" name="userfile[]" type="file" webkitdirectory multiple />
                                <label class="form-file-label" for="userfile[]">
                                    <span class="form-file-text">Seleccione el Archivo...</span>
                                    <span class="form-file-button btn-primary "><i data-feather="upload"></i></span>
                                </label>
                                <label class="form-file-label" for="userfile[]" style="margin-top:3em;">
                                    <span id="form-file-message" style="color: red;margin-top:0.8em;" ></span>
                                </label>
                            </div>
                            <br />
                            <br />
                            <br />
                        </div>
                        <div>
                            <div class="row">
                                <div class="col-12" id="carreteEvidencias" style="display: flex; text-align: center">
                                </div>
                            </div>
                        </div>
                        <div class="buttons" style="float: right;" id="panelButtons">
                            <button type="button" id="btn-AceptarAbb" class="btn btn-AceptarAbb ml-1" onClick="generarReporte();">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Aceptar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </section>
    </div>
</div>
</body>
<script type="text/javascript" language="javascript">


    document.getElementById("userfile").addEventListener(
        "change",
        (event) => {
            console.debug('subir evidencia para reporte');
            let output = document.getElementById("carreteEvidencias");
            //console.debug(event);
            for (const file of event.target.files) {

                var errorFileMessage = document.getElementById('form-file-message');
                errorFileMessage.innerText = "";
                var anhoCapaImg=118;
                

                const splitedName = file.name.split(".");
                const reader = new FileReader();

                reader.addEventListener("load", function () {
                    // result is a base64 string

                    console.debug('se llama al listener');

                    let imgRender = '<div class="alert alert-dismissible" style="width: '+anhoCapaImg+'px;"><img src="'+reader.result+'" width="85" height="96" id="preview-image'+splitedName[0]+'" ></div>';

                    document.getElementById("carreteEvidencias").innerHTML+=imgRender;

                }, false);

                const readImage = async (file,reader) => {
                    if (file)
                        return reader.readAsDataURL(file);
                }

                let evalPromise = processAsync(file, reader, readImage);
                console.debug(typeof evalPromise);
                console.debug(evalPromise); 
            }
        },
        false,
    );

    const processAsync = async (file, reader, readImage) => {

        return await new Promise((resolve, reject) => readImage(file, reader));

    }

    function generarReporte() {
        
        var animaContenedor=document.getElementById("contenedor_cargar1");
        animaContenedor.style.visibility='';
        animaContenedor.style.opacity='0.9';

        var str = document.getElementById('formuploadajax');
        console.debug(typeof str);
        console.debug(str); 
        console.debug("Data Str"); 
        var dataStr = new FormData(str);
        console.debug(typeof dataStr);
        console.debug(dataStr); 

         $.ajax({
            url: "src/InitMakerPDFCasinoReports.php?option=1", 
            data: dataStr,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(dataR)
            {
                console.debug("server response: ");
                console.debug(dataR);
                var arrResp=JSON.parse(dataR);
                console.debug(arrResp);
                console.debug(arrResp.status);
                console.debug(arrResp.message);
                console.debug(arrResp.data);
                console.debug(arrResp.data.uri);
                console.debug(arrResp.data.name);
                animaContenedor.style.visibility='Hidden';
                animaContenedor.style.opacity=0;
                Swal.fire({
                    title: arrResp.message,
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: true,
                    confirmButtonText: 'Aceptar',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) 
                    {
                        var URLreport = 'https://tunomina.com.mx/chuy/assets/PDFsReportes/'+arrResp.data.name;

                        var buttoPopUp = '<button type="button" class="btn btn-light-secondary" onClick="irPopUp(\''+URLreport+'\');">';
                            buttoPopUp += '<i class="bx bx-x d-block d-sm-none"></i>';
                            buttoPopUp += '<span class="d-none d-sm-block">Ver Reporte</span>';
                        buttoPopUp += '</button>';

                        buttoPopUp += '<button type="button" class="btn btn-light-secondary" onClick="recargarForma();">';
                            buttoPopUp += '<i class="bx bx-x d-block d-sm-none"></i>';
                            buttoPopUp += '<span class="d-none d-sm-block">Limpiar Formulario</span>';
                        buttoPopUp += '</button>';

                        document.getElementById("panelButtons").innerHTML+=buttoPopUp;

                    } 
                });


            },
            error:function(error){

                console.debug("Error at consume: ");
                console.debug(error);
            }
		}); 
    }

    function irPopUp($popUri) {

        window.open($popUri, 'Reporte', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=950,height=500,left = 160,top = 50');
        
    }

    function recargarForma() {
        
        var str = document.getElementById('formuploadajax');
        str.action="https://tunomina.com.mx/chuy/index.php?option=1";
        str.submit();	
    }
</script>
</html>