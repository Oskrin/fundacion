<?php
    include_once('../../admin/class.php');
    $class = new constante();
    date_default_timezone_set('America/Guayaquil');
    setlocale (LC_TIME,"spanish");

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    $search = $_GET['_search'];
    if (!$sidx)
        $sidx = 1;
    
    $count = 0;
    if($_GET['estado'] == "0") {
        $sql = "select COUNT(*) AS count from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id";
    } else {
        $sql = "select COUNT(*) AS count from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id and CR.estado = '".$_GET['estado']."'";
    }
    
    $sql = $class->consulta($sql);      
    while ($row = $class->fetch_array($sql)) {
        $count = $row[0];    
    }    
    if ($count > 0 && $limit > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
    if ($page > $total_pages)
        $page = $total_pages;
    $start = $limit * $page - $limit;
    if ($start < 0)
        $start = 0;
    
    $campoSearch = '';
    $orderBy = $_GET['sidx'];
    if($_GET['sidx'] == 'fechaCreacion') {
        $orderBy = 'CR.fechaCreacion';
    }

    if($_GET['estado'] == "0") {
        if ($search == 'false') {
            $SQL = "select CR.id, CR.numeroAutorizacion,  CR.fechaEmision, U.userName, S.ciudad empresa, CR.estado, CR.fechaAutorizacion,CR.claveAcceso, C.nombreComercial, C.identificacion from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id  ORDER BY $orderBy $sord limit $limit offset $start";
        } else {
            if($_GET['searchField'] == 'empresa')
                $campoSearch = 'S.ciudad';
            if($_GET['searchField'] == 'razonSocial')
                $campoSearch = 'C.nombreComercial';
            if($_GET['searchField'] == 'identificacion')
                $campoSearch = ' C.identificacion';            
            $campo = $_GET['searchField'];
          
            if ($_GET['searchOper'] == 'eq') {
                $SQL = "select CR.id, CR.numeroAutorizacion,  CR.fechaEmision, U.userName, S.ciudad empresa, CR.estado, CR.fechaAutorizacion,CR.claveAcceso, C.nombreComercial, C.identificacion from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id WHERE $campoSearch = '".$_GET['searchString']."'  ORDER BY $orderBy $sord limit $limit offset $start";
            }         
            if ($_GET['searchOper'] == 'cn') {
                $SQL = "select CR.id, CR.numeroAutorizacion,  CR.fechaEmision, U.userName, S.ciudad empresa, CR.estado, CR.fechaAutorizacion,CR.claveAcceso, C.nombreComercial, C.identificacion from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id WHERE $campoSearch like '%$_GET[searchString]%'  ORDER BY $orderBy $sord limit $limit offset $start";
            }
        }  
    } else {
        if ($search == 'false') {
            $SQL = "select CR.id, CR.numeroAutorizacion,  CR.fechaEmision, U.userName, S.ciudad empresa, CR.estado, CR.fechaAutorizacion,CR.claveAcceso, C.nombreComercial, C.identificacion from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id and CR.estado = '".$_GET['estado']."' ORDER BY $orderBy $sord limit $limit offset $start";
        } else {            
            if($_GET['searchField'] == 'empresa')
                $campoSearch = 'S.ciudad';
            if($_GET['searchField'] == 'razonSocial')
                $campoSearch = 'C.nombreComercial';
            if($_GET['searchField'] == 'identificacion')
                $campoSearch = ' C.identificacion';
          
            if ($_GET['searchOper'] == 'eq') {
                $SQL = "select CR.id, CR.numeroAutorizacion,  CR.fechaEmision, U.userName, S.ciudad empresa, CR.estado, CR.fechaAutorizacion,CR.claveAcceso, C.nombreComercial, C.identificacion from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id WHERE $campoSearch = '".$_GET['searchString']."' and CR.estado = '".$_GET['estado']."' ORDER BY $orderBy $sord limit $limit offset $start";
            }         
            if ($_GET['searchOper'] == 'cn') {
                $SQL = "select CR.id, CR.numeroAutorizacion,  CR.fechaEmision, U.userName, S.ciudad empresa, CR.estado, CR.fechaAutorizacion,CR.claveAcceso, C.nombreComercial, C.identificacion from comprobanteretencion CR left join user U on CR.idUsuario = U.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join contribuyente C on CR.idContribuyente = C.id inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id WHERE $campoSearch like '%$_GET[searchString]%' and CR.estado = '".$_GET['estado']."' ORDER BY $orderBy $sord limit $limit offset $start";
            }
        }     
    }

    $resultado = $class->consulta($SQL); 
    $s = '';
    header("Content-Type: text/html;charset=utf-8");   
    $s = "<?xml version='1.0' encoding='utf-8'?>";
    $s .= "<rows>";
    $s .= "<page>" . $page . "</page>";
    $s .= "<total>" . $total_pages . "</total>";
    $s .= "<records>" . $count . "</records>";
    while ($row = $class->fetch_array($resultado)) {
        $s .= "<row id='" . $row[0] . "'>";            
        $s .= "<cell>" . $row[0] . "</cell>";     
        $s .= "<cell>" . $row[1] . "</cell>";     
        if($row[5] == 1) {
            $s .= '<cell><![CDATA[<div class="hidden-sm hidden-xs btn-group"><button id="btn_1" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-success" data-toggle="tooltip" title="Visualizar PDF"><i class="ace-icon fa fa-search bigger-120"></i></button><button id="btn_2" data-ids="'.$row[0].'"  data-xml="'.$row[1].'" class="boton btn btn-xs btn-info" data-toggle="tooltip" title="Descargar XML"><i class="ace-icon fa fa-cloud-download bigger-120"></i></button><button id="btn_3" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-warning" alt="" data-toggle="tooltip" title="Enviar Correo"><i class="ace-icon fa fa-envelope bigger-120"></i></button></div>]]></cell>';
        } else {
            if($row[5] == 2) {
                $s .= '<cell><![CDATA[<div class="hidden-sm hidden-xs btn-group"><button id="btn_1" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-success" data-toggle="tooltip" title="Visualizar PDF"><i class="ace-icon fa fa-search bigger-120"></i></button><button id="btn_4" data-ids="'.$row[0].'" data-xml="'.$row[1].'"  class="boton btn btn-xs btn-danger" data-toggle="tooltip" title="Generar Archivos" ><i class="ace-icon fa fa-file-excel-o bigger-120"></i></button></div>]]></cell>';
            } else {
                if($row[5] == 3) {
                    $s .= '<cell><![CDATA[<div class="hidden-sm hidden-xs btn-group"><button id="btn_1" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-success" data-toggle="tooltip" title="Visualizar PDF"><i class="ace-icon fa fa-search bigger-120"></i></button><button id="btn_2" data-ids="'.$row[0].'"  data-xml="'.$row[1].'" class="boton btn btn-xs btn-info" data-toggle="tooltip" title="Descargar XML"><i class="ace-icon fa fa-cloud-download bigger-120"></i></button><button id="btn_5" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-warning" alt="" data-toggle="tooltip" title="Reenviar Correo"><i class="ace-icon fa fa-envelope bigger-120"></i></button></div>]]></cell>';
                } else {
                    if($row[5] == 7) {
                        $s .= '<cell><![CDATA[<div class="hidden-sm hidden-xs btn-group"><button id="btn_1" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-success" data-toggle="tooltip" title="Visualizar PDF"><i class="ace-icon fa fa-search bigger-120"></i></button><button id="btn_6" data-ids="'.$row[0].'" data-xml="'.$row[1].'"  class="boton btn btn-xs btn-danger" data-toggle="tooltip" title="Rechazado volver a validar Archivos"><i class="ace-icon fa fa-pencil bigger-120"></i></button></div>]]></cell>';
                    } else {
                        if($row[5] == 8) {
                            $s .= '<cell><![CDATA[<div class="hidden-sm hidden-xs btn-group"><button id="btn_7" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-danger" data-toggle="tooltip" title="Error en el WebService volver a Enviar"><i class="ace-icon fa fa-pencil bigger-120"></i></button></div>]]></cell>';
                        } else {    
                            if($row[5] == 9) { 
                                $s .= '<cell><![CDATA[<div class="hidden-sm hidden-xs btn-group"><button id="btn_9" data-ids="'.$row[0].'" data-xml="'.$row[1].'" class="boton btn btn-xs btn-danger" data-toggle="tooltip" title="Firmar y Generar"><i class="ace-icon fa fa-check-square-o bigger-120"></i></button></div>]]></cell>';
                            }     
                        }
                    }
                }
            }
        } 

        if($row[5] == 1) {
            $s .= "<cell>" . "AUTORIZADO". "</cell>";
        } else {
            if($row[5] == 2) {
                $s .= "<cell>" . "AUTORIZADO ,ERROR AL GENERADAR DOCUMENTOS". "</cell>";
            } else {
                if($row[5] == 3) {
                    $s .= "<cell>" . "AUTORIZADO CORREO NO ENVIADO". "</cell>";
                } else {
                    if($row[5] == 7) {
                        $s .= "<cell>" . "RECHAZADO". "</cell>";
                    } else {
                        if($row[5] == 8) {
                            $s .= "<cell>" . "SIN FIRMAR, ERROR EN EL WEB SERVICE". "</cell>";
                        } else {
                            if($row[5] == 9) {
                                $s .= "<cell>" . "SIN FIRMAR, ARCHIVO SOLO GUARDADO". "</cell>";
                            }   
                        }
                    }
                }
            }               
        }  
                  
        $s .= "<cell>" . $row[2] . "</cell>";     
        $s .= "<cell>" . $row[3] . "</cell>";  
        $s .= "<cell>" . $row[4] . "</cell>";  
        /*$s .= "<cell>" . $row[5] . "</cell>";  */                       
        $s .= "<cell>" . $row[6] . "</cell>";  
        $s .= "<cell>" . $row[8] . "</cell>";
        $s .= "<cell>" . $row[9] . "</cell>";
        $s .= "</row>";
    }

    $s .= "</rows>";
    echo $s;    
?>