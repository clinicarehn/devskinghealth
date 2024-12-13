<?php
session_start(); 
include "../php/funtions.php";

//CONEXION A DB
$mysqli = connect_mysqli();

if( isset($_SESSION['colaborador_id']) == false ){
   header('Location: login.php'); 
}    

$_SESSION['menu'] = "Pacientes";

if(isset($_SESSION['colaborador_id'])){
 $colaborador_id = $_SESSION['colaborador_id'];  
}else{
   $colaborador_id = "";
}

$type = $_SESSION['type'];

$nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);//HOSTNAME	
$fecha = date("Y-m-d H:i:s"); 
$comentario = mb_convert_case("Ingreso al Modulo de Pacientes", MB_CASE_TITLE, "UTF-8");   

if($colaborador_id != "" || $colaborador_id != null){
   historial_acceso($comentario, $nombre_host, $colaborador_id);  
}  

$mysqli->close();//CERRAR CONEXIÓN     
 ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Script Tutorials" />
    <meta name="description" content="Responsive Websites Orden Hospitalaria de San Juan de Dios">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pacientes :: <?php echo SERVEREMPRESA;?></title>
    <?php include("script_css.php"); ?>
</head>

<body>
    <!--Ventanas Modales-->
    <!-- Small modal -->
    <?php include("templates/modals.php"); ?>

    <!--INICIO MODAL-->

    <div class="modal fade" id="modal_pacientes">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pacientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="FormularioAjax" id="formulario_pacientes" data-async data-target="#rating-modal"
                        action="" method="POST" data-form="" autocomplete="off" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input type="hidden" required readonly id="pacientes_id" name="pacientes_id" />
                                <div class="input-group mb-3">
                                    <input type="text" required readonly id="pro" name="pro" class="form-control" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="sb-nav-link-icon"></div><i class="fa fa-plus-square"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row" id="grupo_expediente">
                            <div class="col-md-6 mb-3">
                                <label for="expediente">Expediente</label>
                                <input type="number" name="expediente" class="form-control" id="expediente"
                                    placeholder="Expediente o Identidad">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edad">Edad</label>
                                <input type="text" class="form-control" name="edad" id="edad" maxlength="100"
                                    readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="nombre">Nombre <span class="priority">*<span /></label>
                                <input type="text" required id="name" name="name" placeholder="Nombre"
                                    class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="apellido">Apellido <span class="priority">*<span /></label>
                                <input type="text" required id="lastname" name="lastname" placeholder="Apellido"
                                    class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3" style="display: none;">
                                <label for="fecha">Fecha <span class="priority">*<span /></label>
                                <input type="date" required id="fecha" name="fecha" value="<?php echo date ("Y-m-d");?>"
                                    class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="identidad_clientes">Identidad o RTN</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" id="identidad" name="identidad"
                                        placeholder="Identidad o RTN" maxlength="14"
                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    <div class="input-group-append" id="grupo_editar_rtn">
                                        <span data-toggle="tooltip" data-placement="top" title="Editar RTN"><a
                                                data-toggle="modal" href="#"
                                                class="btn btn-outline-success form-control editar_rtn">
                                                <div class="sb-nav-link-icon"></div><i class="fas fa-edit fa-lg"></i>
                                            </a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label>Fecha de Nacimiento <span class="priority">*<span /></label>
                                <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo date ("Y-m-d");?>"
                                    class="form-control" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="sexo">Sexo <span class="priority">*<span /></label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="sexo" name="sexo" required data-live-search="true"
                                        title="Genero" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="telefono">Teléfono 1 <span class="priority">*<span /></label>
                                <input type="number" id="telefono1" name="telefono1" class="form-control"
                                    placeholder="Primer Teléfono" required maxlength="8"
                                    oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="telefono">Teléfono 2</label>
                                <input type="number" id="telefono2" name="telefono2" class="form-control"
                                    placeholder="Segundo Teléfono" maxlength="8"
                                    oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="pais_id">País</label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="pais_id" name="pais_id" data-live-search="true"
                                        title="País" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="departamento_id">Departamentos</label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="departamento_id" name="departamento_id"
                                        data-live-search="true" title="Departamentos" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="municipio_id">Municipios</label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="municipio_id" name="municipio_id"
                                        data-live-search="true" title="Municipios" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="direccion">Dirección </label>
                                <input type="text" id="direccion" name="direccion" placeholder="Dirección Completa"
                                    placeholder="Dirección" class="form-control" />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="correo">Correo</label>
                                <input type="email" name="correo" id="correo" placeholder="alguien@algo.com"
                                    class="form-control" data-toggle="tooltip" data-placement="top"
                                    title="Este correo será utilizado para enviar las citas creadas y las reprogramaciones, como las notificaciones de las citas pendientes de los usuarios."
                                    maxlength="100" /><label id="validate"></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-8 mb-3">
                                <label for="responsable">Responsable </label>
                                <input type="text" id="responsable" name="responsable" class="form-control"
                                    placeholder="Responsable" maxlength="70"
                                    oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="responsable_id">Parentesco </label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="responsable_id" name="responsable_id"
                                        data-live-search="true" title="Parentesco" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="referido_id">Referido por: </label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="referido_id" name="referido_id"
                                        data-live-search="true" title="Referido por" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary ml-2" form="formulario_pacientes" type="submit" id="reg">
                        <div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mensaje_show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Información Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="mensaje_sistema">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <div class="modal-title" id="mensaje_mensaje_show"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-2" type="submit" id="okay" data-dismiss="modal">
                        <div class="sb-nav-link-icon"></div><i class="fas fa-thumbs-up fa-lg"></i> Okay
                    </button>
                    <button class="btn btn-danger ml-2" type="submit" id="bad" data-dismiss="modal">
                        <div class="sb-nav-link-icon"></div><i class="fas fa-thumbs-up fa-lg"></i> Okay
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregar_expediente_manual">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Identidad y/o Expediente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container"></div>
                <div class="modal-body">
                    <form id="formulario_agregar_expediente_manual">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input type="hidden" required readonly id="pacientes_id" name="pacientes_id"
                                    class="form-control" />
                                <div class="input-group mb-3">
                                    <input type="text" required readonly id="pro" name="pro" class="form-control" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="sb-nav-link-icon"></div><i class="fa fa-plus-square"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row" id="grupo_expediente">
                            <div class="col-md-6 mb-3">
                                <label for="name_manual">Nombre</label>
                                <input type="text" required readonly id="" name="name_manual" class="form-control"
                                    readonly />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edad_manual">Edad</label>
                                <input type="text" required class="form-control" name="edad_manual" id="edad_manual"
                                    maxlength="100" readonly />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="expediente_usuario_manual">Expediente </label>
                                <input type="text" class="form-control" id="expediente_usuario_manual" readonly
                                    name="expediente_usuario_manual" autofocus placeholder="Expediente"
                                    maxlength="100" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="identidad_ususario_manual">Identidad </label>
                                <input type="text" class="form-control" name="identidad_ususario_manual" autofocus
                                    placeholder="Identidad" id="identidad_ususario_manual" maxlength="100" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha_re_manual">Fecha</label>
                                <input type="date" class="form-control" name="fecha_re_manual" id="fecha_re_manual"
                                    value="<?php echo date ("Y-m-d");?>" maxlength="100" readonly />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="expediente_manual">Expediente </label>
                                <input type="text" name="expediente_manual" class="form-control" id="expediente_manual"
                                    maxlength="100" value="0" readonly />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="identidad_manual">Identidad </label>
                                <input type="number" name="identidad_manual" class="form-control" id="identidad_manual"
                                    maxlength="100" value="0" readonly />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="sexo_manual">Sexo <span class="priority">*<span /></label>
                                <div class="input-group mb-3">
                                    <select class="selectpicker" id="sexo_manual" name="sexo_manual" required
                                        data-live-search="true" title="Sexo" data-width="100%" data-size="7">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-check-inline">
                            <p for="end" class="col-sm-9 form-check-label">¿Desea convertir usuario en temporal?</p>
                            <div class="col-sm-5">
                                <input type="radio" class="form-check-input" name="respuesta" id="respuestasi"
                                    value="1"> Sí
                                <input type="radio" class="form-check-input" name="respuesta" id="respuestano" value="2"
                                    checked> No
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary ml-2" type="submit" id="reg_manual"
                        form="formulario_agregar_expediente_manual">
                        <div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar
                    </button>
                    <button class="btn btn-warning ml-2" type="submit" id="convertir_manual"
                        form="formulario_agregar_expediente_manual">
                        <div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Convertir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_profesiones">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Profesiones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container"></div>
                <div class="modal-body">
                    <form class="FormularioAjax" id="formulario_profesiones" action="" method="POST" data-form=""
                        autocomplete="off" enctype="multipart/form-data">
                        <div class="form-row" id="grupo_expediente">
                            <div class="col-md-12 mb-3">
                                <input type="text" required="required" id="profesionales_buscar"
                                    name="profesionales_buscar" class="form-control"
                                    placeholder="Buscar por: Profesionales" id="centros_buscar" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination" id="agrega_registros_profesionales"></ul>
                                </nav>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <center>
                                    <ul class="pagination justify-content-center" id="pagination_profesionales"></ul>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary ml-2" type="submit" id="reg_profesionales"
                        form="formulario_profesiones">
                        <div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <?php include("modals/modals.php");?>

    <!--Fin Ventanas Modales-->
    <!--MENU-->
    <?php include("templates/menu.php"); ?>
    <!--FIN MENU-->

    <br><br><br>
    <div class="container-fluid">
        <ol class="breadcrumb mt-2 mb-4">
            <li class="breadcrumb-item"><a class="breadcrumb-link"
                    href="<?php echo SERVERURL; ?>vistas/inicio.php">Dashboard</a></li>
            <li class="breadcrumb-item active" id="acciones_factura"><span id="label_acciones_factura"></span>Pacientes
            </li>
        </ol>

        <div id="main_facturacion">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-inline" id="form_main">
                        <div class="form-group mx-sm-3 mb-1">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div>Estado
                                </span>
                                <select id="estado" name="estado" class="selectpicker" title="Estado"
                                    data-live-search="true">
                                </select>
                            </div>
                        </div>
                        <div class="form-group mx-sm-3 mb-1">
                            <input type="text" placeholder="Buscar por: Expediente, Nombre, Apellido o Identidad"
                                data-toggle="tooltip" data-placement="top"
                                title="Buscar por: Expediente, Nombre, Apellido o Identidad" id="bs_regis" autofocus
                                class="form-control" size="50" />
                        </div>
                        <div class="form-group mx-sm-3 mb-1">
                            <button class="btn btn-primary ml-1" type="submit" id="nuevo-registro">
                                <div class="sb-nav-link-icon" data-toggle="tooltip" data-placement="top"
                                    title="Registrar Pacientes"></div><i class="fas fa-user-plus fa-lg"></i> Registrar
                                Pacientes
                            </button>
                            <button class="btn btn-primary ml-1" type="submit" id="profesion">
                                <div class="sb-nav-link-icon" data-toggle="tooltip" data-placement="top"
                                    title="Registrar Profesión"></div><i class="fas fa-download fa-lg"></i> Registrar
                                Profesión
                            </button>
                            <button class="btn btn-success ml-1" type="submit" id="reporte">
                                <div class="sb-nav-link-icon" data-toggle="tooltip" data-placement="top"
                                    title="Exportar"></div><i class="fas fa-download fa-lg"></i> Exportar Pacientes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user mr-1"></i>
                    Pacientes
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="registros overflow-auto" id="agrega-registros"></div>
                        </div>
                    </div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>

    </div>
    <?php include("templates/factura.php"); ?>
    <?php include("templates/footer.php"); ?>
    </div>

    <!-- add javascripts -->
    <?php 
		include "script.php"; 
		
		include "../js/main.php"; 
		include "../js/myjava_pacientes.php"; 
		include "../js/select.php"; 	
		include "../js/functions.php"; 
		include "../js/myjava_cambiar_pass.php"; 		
	?>
</body>

</html>