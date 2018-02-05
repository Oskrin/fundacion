<?php 
	session_start();
	if(!$_SESSION) {
		header('Location: login/');
	}
?> 
<!DOCTYPE html>
<html ng-app="scotchApp" lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>.:FACTURACIÓN.:</title>
		<meta name="description" content="3 styles with inline editable feature" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="dist/css/font-awesome.min.css" />
		<link rel="stylesheet" href="dist/css/style.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="dist/css/animate.min.css" />
		<link rel="stylesheet" href="dist/css/jquery.gritter.min.css" />
		<link rel="stylesheet" href="dist/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="dist/css/chosen.min.css" />
		<link rel="stylesheet" href="dist/css/select2.min.css" />
		<link rel="stylesheet" href="dist/css/ui.jqgrid.min.css" />
		<link rel="stylesheet" href="dist/css/bootstrap-timepicker.min.css" />
		<link rel="stylesheet" href="dist/css/daterangepicker.min.css" />
		<link rel="stylesheet" href="dist/css/bootstrap-datetimepicker.min.css" />
		<link rel="stylesheet" href="dist/css/bootstrap-datetimepicker-standalone.css" />
		<link rel="stylesheet" href="dist/css/bootstrap-editable.min.css" />
		<link rel="stylesheet" href="dist/css/daterangepicker.min.css" />
		<link rel="stylesheet" href="dist/css/sweetalert.css" />

		<link rel="stylesheet" href="dist/css/jquery-ui.custom.min.css" />
		<link href="dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
		
		<!-- text fonts -->
		<link rel="stylesheet" href="dist/css/fontdc.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="dist/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<script src="dist/js/ace-extra.min.js"></script>

		<!-- Angular js -->
		<script src="dist/angular-1.5.0/angular.js"></script>
		<script src="dist/angular-1.5.0/angular-route.js"></script>
		<script src="dist/angular-1.5.0/angular-animate.js"></script>
		<script src="dist/angular-1.5.0/ui-bootstrap-tpls-1.1.2.min.js"></script>
		<script src="dist/angular-1.5.0/angular-resource.js"></script>
		<script src="dist/js/ngStorage.min.js"></script>

		<!-- controlador procesos angular -->
  		<script src="data/app.js"></script>
  		<script src="data/inicio/app.js"></script>
  		<script src="data/cargos/app.js"></script>
  		<script src="data/privilegios/app.js"></script>
  		<script src="data/empresa/app.js"></script>
  		<script src="data/tipo_ambiente/app.js"></script>
  		<script src="data/tipo_emision/app.js"></script>
  		<script src="data/tipo_comprobante/app.js"></script>
  		<script src="data/tipo_documento/app.js"></script>
  		<script src="data/tipo_producto/app.js"></script>
  		<script src="data/tipo_impuesto/app.js"></script>
  		<script src="data/tipo_retencion/app.js"></script>
  		<script src="data/tarifa_impuesto/app.js"></script>
  		<script src="data/tarifa_retencion/app.js"></script>
  		<script src="data/formas_pago/app.js"></script>
  		<script src="data/porcentaje/app.js"></script>
  		<script src="data/cargar_xml/app.js"></script>
  		<script src="data/validar_comprobantes/app.js"></script>
  		<script src="data/clientes/app.js"></script>
  		<script src="data/usuarios/app.js"></script>
  		<script src="data/cuenta/app.js"></script>

  		<style type="text/css">
			.control {
				background: #eff3f8;
				/*background: #87b87f;*/
				/*background: #4caf50;*/
				height: 60px;
			}

			.dimensiones {
				margin-top: 13px;
			}

			.posicion{ 
				margin-top: 9px;
				float: right;
				margin-left: -5px;
				margin-right: 10px;
			}

			.menu_superior {
				display: inline-block;
			    font-size: 16px;
			    color: #FFF;
			    text-align: center;
			    width: 20px;
			}
		</style>
	</head>

	<body ng-controller="mainController" class="no-skin">
		<div id="navbar" class="navbar navbar-default navbar-fixed-top">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			<div class="navbar-container" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<small>
							FACTURACIÓN ELECTRÓNICA
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" href="" class="dropdown-toggle">
								<img class="nav-user-photo" src=<?php  print_r('data/usuarios/'. $_SESSION['user']['imagen']); ?> alt="" />
								<span class="user-info">
									<small>Bienvenido,</small>
									<?php print_r($_SESSION['user']['name']); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#/cuenta">
										<i class="ace-icon fa fa-user"></i>
										Cuenta
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="login/exit.php">
										<i class="ace-icon fa fa-power-off"></i>
										Salir
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<ul class="nav nav-list">
					<li ng-class="{active: $route.current.activetab == 'inicio'}">
						<a href="#/">
							<i class="menu-icon fa fa-home"></i>
							<span class="menu-text"> Inicio </span>
						</a>

						<b class="arrow"></b>
					</li>

					<li ng-class = "{'active open': 
												$route.current.activetab == 'empresa'
									}">
						<a href="" class="dropdown-toggle">
							<i class="menu-icon fa fa-building"></i>
							Empresa
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li ng-class="{active: $route.current.activetab == 'empresa'}">
								<a href="#/empresa">
									<i class="menu-icon fa fa-caret-right"></i>
									Registro Empresa
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>

					<li ng-class = "{'active open':
												$route.current.activetab == 'tipo_ambiente' ||
												$route.current.activetab == 'tipo_emision' || 	
												$route.current.activetab == 'tipo_comprobante' ||
												$route.current.activetab == 'tipo_documento' ||
												$route.current.activetab == 'tipo_producto' ||
												$route.current.activetab == 'tipo_impuesto' ||
												$route.current.activetab == 'tipo_retencion' ||
												$route.current.activetab == 'tarifa_impuesto' ||
												$route.current.activetab == 'tarifa_retencion' ||
												$route.current.activetab == 'formas_pago' ||
												$route.current.activetab == 'porcentaje' 
												
									}">
						<a href="" class="dropdown-toggle">
							<i class="menu-icon fa fa-cog"></i>
							Parametros
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li ng-class="{active: $route.current.activetab == 'tipo_ambiente'}">
								<a href="#/tipo_ambiente">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Ambiente
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tipo_emision'}">
								<a href="#/tipo_emision">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Emisión
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tipo_comprobante'}">
								<a href="#/tipo_comprobante">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Comprobante
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tipo_documento'}">
								<a href="#/tipo_documento">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Documento
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tipo_producto'}">
								<a href="#/tipo_producto">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Producto
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tipo_impuesto'}">
								<a href="#/tipo_impuesto">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Impuesto
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tipo_retencion'}">
								<a href="#/tipo_retencion">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipo Retención
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tarifa_impuesto'}">
								<a href="#/tarifa_impuesto">
									<i class="menu-icon fa fa-caret-right"></i>
									Tarifa Impuesto
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'tarifa_retencion'}">
								<a href="#/tarifa_retencion">
									<i class="menu-icon fa fa-caret-right"></i>
									Tarifa Retención
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'formas_pago'}">
								<a href="#/formas_pago">
									<i class="menu-icon fa fa-caret-right"></i>
									Formas Pago
								</a>

								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'porcentaje'}">
								<a href="#/porcentaje">
									<i class="menu-icon fa fa-caret-right"></i>
									Porcentaje IVA
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>



					<li ng-class = "{'active open': 
												$route.current.activetab == 'validar_comprobantes' ||
												$route.current.activetab == 'cargar_xml'
									}">
						<a href="" class="dropdown-toggle">
							<i class="menu-icon fa fa-shopping-cart"></i>
							<span class="menu-text">
								Facturación
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li ng-class="{active: $route.current.activetab == 'cargar_xml'}">
								<a href="#/cargar_xml">
									<i class="menu-icon fa fa-caret-right"></i>
									Cargar XML
								</a>
								<b class="arrow"></b>
							</li>
						</ul>

						<ul class="submenu">
							<li ng-class="{active: $route.current.activetab == 'validar_comprobantes'}">
								<a href="#/validar_comprobantes">
									<i class="menu-icon fa fa-caret-right"></i>
									Validad Comprobantes
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>

					<li ng-class = "{'active open': 
												$route.current.activetab == 'cargos' ||
												$route.current.activetab == 'usuarios' ||
												$route.current.activetab == 'privilegios'
									}">
						<a href="" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>
							Usuarios
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li ng-class="{active: $route.current.activetab == 'cargos'}">
								<a href="#/cargos">
									<i class="menu-icon fa fa-caret-right"></i>
									Perfiles
								</a>
								<b class="arrow"></b>
							</li>

							<li ng-class="{active: $route.current.activetab == 'usuarios'}">
								<a href="#/usuarios">
									<i class="menu-icon fa fa-caret-right"></i>
									Registro Usuario
								</a>
								<b class="arrow"></b>
							</li>

							<!-- <li ng-class="{active: $route.current.activetab == 'privilegios'}">
								<a href="#/privilegios">
									<i class="menu-icon fa fa-caret-right"></i>
									Privilegios
								</a>
								<b class="arrow"></b>
							</li> -->
						</ul>
					</li>
				</ul>

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<div class="main-content ng-view" id="main-container"></div>

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							Applicación &copy; 2017-2018
						</span>
					</div>
				</div>
			</div>

			<a href="" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div>

		<script type="text/javascript">
			window.jQuery || document.write("<script src='dist/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='dist/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		
		<script src="dist/js/jquery-ui.min.js"></script>
		<script src="dist/js/jquery.ui.touch-punch.min.js"></script>
		<script src="dist/js/jquery.easypiechart.min.js"></script>
		<script src="dist/js/jquery.sparkline.min.js"></script>
		<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
    	<script type="text/javascript" src="https://code.highcharts.com/modules/exporting.js"></script>

		<script src="dist/js/fileinput.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<script src="dist/js/jquery.form.js"></script>
		<script src="dist/js/chosen.jquery.min.js"></script>

		<script src="dist/js/jquery.validate.min.js"></script>
		<script src="dist/js/jquery.gritter.min.js"></script>
		<script src="dist/js/bootbox.min.js"></script>
		<script src="dist/js/fuelux/fuelux.wizard.min.js"></script>
		<script src="dist/js/additional-methods.min.js"></script>
		
		<script src="dist/js/jquery.hotkeys.min.js"></script>
		<script src="dist/js/bootstrap-wysiwyg.min.js"></script>
		<script src="dist/js/select2.min.js"></script>
		<script src="dist/js/fuelux/fuelux.spinner.min.js"></script>
		<script src="dist/js/fuelux/fuelux.tree.min.js"></script>
		<script src="dist/js/x-editable/bootstrap-editable.min.js"></script>
		<script src="dist/js/x-editable/ace-editable.min.js"></script>
		<script src="dist/js/jquery.maskedinput.min.js"></script>
		<script src="dist/js/bootbox.min.js"></script>
		<script src="dist/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="dist/js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="dist/js/date-time/moment.min.js"></script>
		<script src="dist/js/date-time/daterangepicker.min.js"></script>
		<script src="dist/js/date-time/bootstrap-datetimepicker.min.js"></script>
		
		<!-- script de las tablas -->
		<script src="dist/js/jqGrid/jquery.jqGrid.min.js"></script>
		<script src="dist/js/jqGrid/i18n/grid.locale-en.js"></script>
		<script src="dist/js/dataTables/jquery.dataTables.min.js"></script>
		<script src="dist/js/dataTables/jquery.dataTables.bootstrap.min.js"></script>
		<script src="dist/js/dataTables/dataTables.tableTools.min.js"></script>
		<script src="dist/js/dataTables/dataTables.colVis.min.js"></script>

		<!-- ace scripts -->
		<script src="dist/js/ace-elements.min.js"></script>
		<script src="dist/js/ace.min.js"></script>
		<script src="dist/js/lockr.min.js"></script>
		<script src="dist/js/sweetalert.min.js"></script>
		<script src="dist/js/jquery.blockUI.js"></script>	
	</body>
</html>
