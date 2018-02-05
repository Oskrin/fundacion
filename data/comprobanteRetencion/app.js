angular.module('accionApp')	
	.controller('comprobanteRetencionController', function ($scope, $route, $http) {	
		$scope.$route = $route;
		$("#loading2").css("display","none");
		var d = new Date();
		var currDate = d.getDate();
		var currMonth = d.getMonth() + 1;
		var currYear = d.getFullYear();
		var dateStr = currDate + "/" + currMonth + "/" + currYear;
		$scope.soloNumeros = function($event){
	        if(isNaN(String.fromCharCode($event.keyCode))){
	            $event.preventDefault();
	        }
		};	
		$scope.General = [];
		$scope.Detalles = [];
		$scope.FormaPago = [];
		function forceValidate(controls){
			angular.forEach(controls, function(control, name){
		       control.$setDirty();
		  });
		}
		$scope.recipientsList = [];		
		$scope.recipientsEmpresaList = [];	
		$scope.recipientsListTipo = [];	
		$scope.recipientsListCodigoRetencion = [];	
		$scope.recipientsListFormaPago = [];	

		$scope.fecthRecipients = function () {
			$http({
		        url: 'data/parametros/contribuyentes/app.php',
		        method: "POST",
		        data: "tipo=" + "cargarTipoIdentificacion",
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    })
		    .then(function(response) {		 		    	
	    		for(var i = 0; i < response.data.length; i++ ){
	    			temp = {
	    				title : response.data[i].nombre,
	    				id : response.data[i].id,	    				
		    		}			    					    	
			    	$scope.recipientsList.push(temp);
		    	}			    	
		    });			
		}
		$scope.fecthRecipientsEmpresa = function () {
			$http({
		        url: 'data/facturacion/comprobantesRetencion/comprobanteRetencion/app.php',
		        method: "POST",
		        data: "tipo=" + "cargarEmpresa",
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    })
		    .then(function(response) {		 		    	
	    		for(var i = 0; i < response.data.length; i++ ){
	    			temp = {
	    				title : response.data[i].nombre,
	    				id : response.data[i].id,	    				
	    				emision : response.data[i].emision,
	    				establecimiento : response.data[i].establecimiento,
		    		}			    					    	
			    	$scope.recipientsEmpresaList.push(temp);
		    	}			    	
		    });			
		}
		$scope.fecthRecipientsTipo = function () {
			$http({
		        url: 'data/facturacion/comprobantesRetencion/comprobanteRetencion/app.php',
		        method: "POST",
		        data: "tipo=" + "cargarTipo",
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    })
		    .then(function(response) {		 		    	
	    		for(var i = 0; i < response.data.length; i++ ){
	    			temp = {
	    				title : response.data[i].nombre,
	    				id : response.data[i].id,
		    		}			    					    	
			    	$scope.recipientsListTipo.push(temp);
		    	}			    	
		    });			
		}
		$scope.fecthRecipientsCodigoRetencion = function () {
			$http({
		        url: 'data/facturacion/comprobantesRetencion/comprobanteRetencion/app.php',
		        method: "POST",
		        data: "tipo=" + "cargarCodigoRetencion",
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    })
		    .then(function(response) {		 		    	
	    		for(var i = 0; i < response.data.length; i++ ){
	    			temp = {
	    				title : response.data[i].nombre,
	    				id : response.data[i].id,
		    		}			    					    	
			    	$scope.recipientsListCodigoRetencion.push(temp);
		    	}			    	
		    });			
		}
		$scope.fecthRecipientsFormaPago = function () {
			$http({
		        url: 'data/facturacion/comprobantesRetencion/comprobanteRetencion/app.php',
		        method: "POST",
		        data: "tipo=" + "cargarFormaPago",
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    })
		    .then(function(response) {		 		    	
	    		for(var i = 0; i < response.data.length; i++ ){
	    			temp = {
	    				title : response.data[i].nombre,
	    				id : response.data[i].id,
		    		}			    					    	
			    	$scope.recipientsListFormaPago.push(temp);
		    	}			    	
		    });			
		}
		$scope.updateFormIdentificacion = function(selectedItem){					
	    	$scope.General.txt_1 = '';
	    	$scope.General.txt_2 = '';
	    	$scope.General.txt_3 = '';
	    	$scope.General.txt_4 = '';
			$scope.General.txt_5 = '';
			$scope.General.txt_6 = '';
		}
		$scope.updateForm = function(selectedItem){
			if(selectedItem == null){				
		    	$scope.General.txt_7 = '';
		    	$scope.General.txt_8 = '';
			}else{
				angular.forEach($scope.recipientsEmpresaList, function(item){
		        	if(item.id == selectedItem){		        		
				    	$scope.General.txt_8 = item.emision;
				    	$scope.General.txt_7 = item.establecimiento;
		        	}
		      	})
			}		
		}
		$scope.updatePorcentaje = function(selectedItem){						
		    $scope.Detalles.txt_10 = '';
		    $scope.Detalles.txt_11 = '';			
			angular.forEach($scope.recipientsListImpuesto, function(item){
	        	if(item.id == selectedItem){		        		
			    	$scope.Detalles.txt_10 = item.porcentaje;
			    	$scope.Detalles.txt_11 = '';
	        	}
	      	})					
		}
		$scope.changeSelectImpuesto = function(selectedItem){			
			$scope.recipientsListImpuesto = [];
			$http({
		        url: 'data/facturacion/comprobantesRetencion/comprobanteRetencion/app.php',
		        method: "POST",
		        data: "tipo=" + "cargarImpuesto"+"&id="+selectedItem,
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    })
		    .then(function(response) {		 		    	
	    		for(var i = 0; i < response.data.length; i++ ){
	    			temp = {
	    				title : response.data[i].nombre,
	    				id : response.data[i].id,
	    				porcentaje : response.data[i].porcentaje,
		    		}			    					    	
			    	$scope.recipientsListImpuesto.push(temp);
		    	}			    	
		    });	
		}
		$scope.buscarDatos = function() {
			$("#loading2").css("display","block");	
			var data = $.param({			 		
		 		tipoIdentificacion: undefinedFunction($scope.recipients),
		 		identificacion: undefinedFunction($scope.General.txt_1),			 		
				tipo: 'cargarContribuyente'
        	});	
        	$http({
		        url: 'data/parametros/contribuyentes/app.php',
		        method: "POST",
		        data: data,
		        headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }			        
		    })
		    .then(function(response) {			
		    	$("#loading2").css("display","none");
		    	if(response.data.length > 0){
		    		$scope.General.txt_1 = response.data[2],
			    	$scope.General.txt_2 = response.data[5],
			    	$scope.General.txt_3 = response.data[4],
			    	$scope.General.txt_4 = response.data[6],
					$scope.General.txt_5 = response.data[7],
					$scope.General.txt_6 = response.data[3],							
			    	//$scope.updateForm($("#selectEmpresa").val())
			    	$("#select_obligacion").val(response.data[8]);
			    	$("#select_obligacion").trigger('chosen:updated');				    	
		    	}else{
		    		$.gritter.add({			                
		                title: 'Mensaje de Salida',			                
		                text: 'Error.. No se encuentran datos',
		                image: 'dist/images/confirm.png',
		                class_name: 'gritter-light'
		            });
		    	}
		    }, 
		    function(response) { // optional
		        $("#loading2").css("display","none");	
		    });	
    	};
    	$scope.agregarDatos = function() {
			var temp = 0;
			if($("#select_Retencion").val() != '' && $("#select_codigoRetencion").val() != '' && $scope.Detalles.txt_10 != '' && $scope.Detalles.txt_11 != ''){				
				if(fn_cantidad("tabla_comprobante") == 0){
					temp = 0;
				}else{					
					if(fn_recorrer() > 0){						
						temp = 1;
						bootbox.dialog({
							message: "Error! Este Impuesto y código ya existen. Ingrese Nuevamente", 
							buttons: {
								"success" : {
									"label" : "Aceptar",
									"className" : "btn-sm btn-primary"
								}
							}
						});
					}else{
						temp = 0;
					}
				}
				if(temp == 0){
					var retenido = 0;
					retenido = (parseFloat($scope.Detalles.txt_11) * parseFloat($scope.Detalles.txt_10)) / 100;
					retenido = Math.round(retenido * 100) /100;
					var cod = "";
					$("#tabla_comprobante tbody").append("<tr>" + 
						"<td style='display:none;' >" + $("#selectComprobante").val() + "</td>" + 						
	                    "<td align=center >" + $("#selectMes option:selected").text() + ' / ' +$("#selectAnio option:selected").text() + "</td>" +
						"<td style='display:none;' >" + $("#select_Retencion").val() + "</td>" +
	                    "<td align=center >" + $("#select_Retencion option:selected").text().substr(0,50) + "</td>" +
	                    "<td align=center >" + $scope.Detalles.txt_11 + "</td>" +                    
	                    "<td style='display:none;' >" + $("#select_codigoRetencion").val() + "</td>" +
	                    "<td align=center >" + $("#select_codigoRetencion option:selected").text() + "</td>" +
	                    "<td align=center >" + $scope.Detalles.txt_10 + "</td>" +
	                    "<td align=center >" + retenido + "</td>" +
	                    "<td align=center >" + "<a class='elimina'><img src='dist/images/delete.png' /></a>" + "</td>" + 
					"</tr>");
					$scope.changeSelectImpuesto('');
					$scope.recipientsCodigoRetencion = '';   
					$("#select_codigoRetencion").val("");
					$("#select_codigoRetencion").trigger("chosen:updated");	                					             
	                $scope.Detalles.txt_10 = '';
	                $scope.Detalles.txt_11 = '';
	                fn_dar_eliminar();
	                $("#tabla_comprobante tfoot").html("<tr>" + 
						"<td colspan='5' align='right' >Total de la Reteneción $</td>" +
						"<td align='center'>"+fn_calcular()+"</td>" +
					"</tr>");
					$scope.FormaPago.txt_13 = fn_calcular();
            	}
			}else{
				bootbox.dialog({
					message: "Error! LLene todos los datos antes de continuar", 
					buttons: {
						"success" : {
							"label" : "Aceptar",
							"className" : "btn-sm btn-primary"
						}
					}
				});
			}	
    	};	
    	$scope.agregarFormasPago = function(){    		
			var temp = 0;
			var total = 0;
			total = parseFloat(fn_calcular());
			if($("#select_formaPago").val() != '' && $scope.FormaPago.txt_12 != ''){
				if(fn_cantidad("tabla_formaPago") == 0){
					temp = 0;
				}else{
					if(fn_recorrerFormaPago() > 0){
						temp = 1;
						bootbox.dialog({
							message: "Error! Esta Forma de Pago ya esta Ingresada. Ingrese Nuevamente", 
							buttons: {
								"success" : {
									"label" : "Aceptar",
									"className" : "btn-sm btn-primary"
								}
							}
						});
					}else{
						temp = 0;
					}
				}
				if(temp == 0){	
					tempF = parseFloat(fn_calcularFormaPago()) + parseFloat($scope.FormaPago.txt_12);
					if(tempF <= total ){
						var totalFormaPago = 0;
						$("#tabla_formaPago tbody").append("<tr>" + 
							"<td style='display:none;' >" + $("#select_formaPago option:selected").val() + "</td>" +
		                    "<td align=center >" + $("#select_formaPago option:selected").text() + "</td>" +	
		                    "<td align=center >" + $scope.FormaPago.txt_12 + "</td>" +
		                    "<td align=center >" + "<a class='elimina'><img src='dist/images/delete.png' /></a>" + "</td>" + 
						"</tr>");
						$("#select_formaPago").val("");
						$("#select_formaPago").trigger("chosen:updated");
		                $scope.FormaPago.txt_12 = '';
		                
		                fn_dar_eliminarFormaPago();
		                $("#tabla_formaPago tfoot").html("<tr>" + 
							"<td colspan='1' align='right' >Total Forma de Pago $</td>" +
							"<td align='center'>"+fn_calcularFormaPago()+"</td>" +
							"<td align='center'></td>" +
						"</tr>");
					}else{
						bootbox.dialog({
							message: "Error! Los valores Ingresados superan el total del Documento. Ingrese Nuevamente", 
							buttons: {
								"success" : {
									"label" : "Aceptar",
									"className" : "btn-sm btn-primary"
								}
							}
						});	
					}

            	}
			}else{
				bootbox.dialog({
					message: "Error! Seleccione una forma de Pago antes de continuar", 
					buttons: {
						"success" : {
							"label" : "Aceptar",
							"className" : "btn-sm btn-primary"
						}
					}
				});
			}
    	};
    	function reload() {
    		setTimeout(function () {
        	location.reload()
    		}, 100);
		}
    	function generarDatos(){
    		var cont = 0;    		
		    var vect1 = new Array();
		    var vect2 = new Array();
		    var vect3 = new Array();
		    var vect4 = new Array();
		    var vect5 = new Array();
		    var vect6 = new Array();

    		$("#tabla_comprobante tbody tr").each(function(index2) {
		        $(this).children("td").each(function(index2) {		        	
		            switch (index2) {		            	
		                case 0:
		                    vect1[cont] = $(this).text();
		                    break;		                
		                case 2:		                	
		                    vect2[cont] = $(this).text();
		                    break;		                
		                case 4:
		                    vect3[cont] = $(this).text();
		                    break;
		                case 5:
		                    vect4[cont] = $(this).text();
		                    break;		                
		                case 7:
		                    vect5[cont] = $(this).text();
		                    break;
		                case 8:
		                    vect6[cont] = $(this).text();
		                    break;		                              
		            }
		        });
		        cont++;
		    }); 
    		var data = $.param({
    			idEmpresa : $("#selectEmpresa").val(),
    			idIdentificacion : $("#selectTipoIdentificacion").val(),
    			identificacion : $scope.General.txt_1,
    			nombreComercial : $scope.General.txt_2,
    			razonSocial : $scope.General.txt_3,
    			direccion : $scope.General.txt_4,
    			telefono : $scope.General.txt_5,
    			correo : $scope.General.txt_6,
    			obligacion : $("#select_obligacion").val(),
    			fechaEmision : $scope.General.txt_9,
    			idTipoComprobante : $("#selectComprobante").val(),
    			mes : $("#selectMes").val(),
    			anio : $("#selectAnio").val(),
    			numeroDocumento : $scope.General.txt_10,
    			vect1 : vect1,
    			vect2 : vect2,
    			vect3 : vect3,
    			vect4 : vect4,
    			vect5 : vect5,
    			vect6 : vect6,
				tipo: 'Generar Datos'
	    	});	
	    	
			$http({
		        url: 'data/facturacion/comprobantesRetencion/comprobanteRetencion/app.php',
		        method: "POST",
		        data: data,
		        headers: {
	                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
	            }			        
		    })
		    .then(function(response) {		    	
		    	$("#loading2").css("display","none");	
				col_1 = $($("#result").children().children().children().next()[0]).children().children()[0];
				col_2 = $($("#result").children().children().children().next()[0]).children().children()[1];				    	
				$(col_1).html('');
				$(col_2).html('');
		    	if(response.data[0]['estado'] == 1){					
					$(col_1).append("<b>La información se encuentra Guardada</b></br>");
					$(col_1).append("<b>El Documento esta Generado</b></br>");
					$(col_1).append("<b>El Documento se encuentra Validado</b></br>");
					$(col_1).append("<b>Documentos enviados al correo del cliente</b></br>");
					$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
					$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
					$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
					$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
					$('#result').modal('show');
					$("#pdf").on("click",function(){
						window.open('data/facturacion/comprobantesRetencion/generarPDF.php?id='+response.data[0]['id'], '_blank');
						reload();
					});
					$("#cerrar").on("click",function(){
						reload();
					});
				}else{
					if(response.data[0]['estado'] == 2){														
						$(col_1).append("<b>La información se encuentra Guardada</b></br>");
						$(col_1).append("<b>El Documento se encuentra Validado</b></br>");
						$(col_1).append("<b>El Documento esta Generado</b></br>");							
						$(col_1).append("<b>Documentos enviados al correo del cliente</b></br>");
						$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
						$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
						$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
						$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
						$('#result').modal('show');
						$("#pdf").on("click",function(){								
							reload();
						});
						$("#cerrar").on("click",function(){
							reload();
						});
					}else{
						if(response.data[0]['estado'] == 3){																
							$(col_1).append("<b>La información se encuentra Guardada</b></br>");
							$(col_1).append("<b>El Documento esta Generado</b></br>");
							$(col_1).append("<b>El Documento se encuentra Validado</b></br>");
							$(col_1).append("<b>Documentos enviados al email del cliente</b></br>");
							$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
							$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
							$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
							$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
							$('#result').modal('show');
							$("#pdf").on("click",function(){
								window.open('data/facturacion/comprobantesRetencion/generarPDF.php?id='+response.data[0]['id'], '_blank');
								reload();
							});
							$("#cerrar").on("click",function(){
								reload();
							});
						}else{
							if(response.data[0]['estado'] == 4){																		
								$(col_1).append("<b>Error en la base de datos</b></br>");								
								$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
								$('#result').modal('show');
								$("#pdf").on("click",function(){										
									reload();
								});
								$("#cerrar").on("click",function(){
									reload();
								});
							}else{
								if(response.data[0]['estado'] == 5){
									$(col_1).append("<b>Error el Archivo para la firma no existe</b></br>");									
									$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');									
									$('#result').modal('show');
									$("#pdf").on("click",function(){										
										reload();
									});
									$("#cerrar").on("click",function(){
										reload();
									});
								}else{
									if(response.data[0]['estado'] == 6){
										$(col_1).append("<b>Error! La clave del certificado es incorrecta</b></br>");									
										$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');									
										$('#result').modal('show');
										$("#pdf").on("click",function(){										
											reload();
										});
										$("#cerrar").on("click",function(){
											reload();
										});
									}else{
										if(response.data[0]['estado'] == 7){																				
											$(col_1).append("<b>La información se encuentra Guardada</b></br>");
											$(col_1).append("<b>Error en el web service del SRI vuelva a intentarlo </b></br>");
											$(col_1).append("<b>El Documento se encuentra Rechazadp</b></br>");
											$(col_1).append("<b>Documentos enviados al email del cliente</b></br>");
											$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
											$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
											$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
											$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
											$('#result').modal('show');
											$("#pdf").on("click",function(){										
												reload();
											});
											$("#cerrar").on("click",function(){
												reload();
											});
										}else{
											$(col_1).append("<b>La información se encuentra Guardada</b></br>");
											$(col_1).append("<b>Error en el web service del SRI vuelva a intentarlo </b></br>");
											$(col_1).append("<b>El Documento se encuentra Validado</b></br>");
											$(col_1).append("<b>Documentos enviados al email del cliente</b></br>");
											$(col_2).append('<i class="fa fa-check" style="color: green;"></i></br>');
											$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
											$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
											$(col_2).append('<i class="fa fa-times" style="color: red;"></i></br>');
											$('#result').modal('show');
											$("#pdf").on("click",function(){										
												reload();
											});
											$("#cerrar").on("click",function(){
												reload();
											});
								
										}	
									}
								}
									
							}		
						}			
					}		
				}	
    		},
    		function(response) { // optional
		            // failed
		    });
    	}
    	function undefinedFunction(val){
			if(val == 'undefined'){				
				return val = '';
			}else{
				return val;
			}
		}
		function fn_cantidad($tabla){
			cantidad = $("#"+$tabla+" tbody").find("tr").length;
			return cantidad;			
		}
		function fn_dar_eliminar(){
	    	$("a.elimina").click(function(){
	    		id = $(this).parents("tr").find("td").eq(0).html();                                
                $(this).parents("tr").fadeOut("normal", function(){
                    $(this).remove();                                                
                    $("#tabla_comprobante tfoot").html("<tr>" + 
						"<td colspan='5' align='right' >Total de la Reteneción $</td>" +
						"<td align='center'>"+fn_calcular()+"</td>" +
					"</tr>");
                });                   
	    	});	      	    	           
	    };
	    function fn_recorrer(){
			var temp = 0;
			$("#tabla_comprobante tbody tr").each(function (index) {
            	var campo1, campo2, campo3;
            	$(this).children("td").each(function (index2){            		
	                switch (index2){
	                    case 3: 
	                    	campo1 = $(this).text();
	                    	break;
	                    case 5: 
	                    	campo2 = $(this).text();
							break;	                    
						case 7: 
	                    	campo3 = $(this).text();
							break;	                    
	                } 	               
            	});
            	if(campo2 == $("#select_codigoRetencion").val() && campo1 == $("#select_Retencion option:selected").text().substr(0,50) && campo3 == $scope.Detalles.txt_10){
                	temp++;
                }            	            	
        	});
        	return temp;
        }
 		function fn_calcular(){        				
 			var temp = 0;
			$("#tabla_comprobante tbody tr").each(function (index) {
            	var campo1;            	
            	$(this).children("td").each(function (index2){            		
	                switch (index2){
	                    case 8: 
	                    	campo1 = $(this).text();	                    	
	                    	break;	                    
	                } 	               
            	});            	
            	temp = temp + parseFloat(campo1);            	
            	temp = Math.round(temp * 100) /100;            	
        	});
        	return temp.toFixed(2);
        }
        function fn_dar_eliminarFormaPago(){
	    	$("a.elimina").click(function(){
	    		id = $(this).parents("tr").find("td").eq(0).html();                                
                $(this).parents("tr").fadeOut("normal", function(){
                    $(this).remove();                                                
                    $("#tabla_formaPago tfoot").html("<tr>" + 
						"<td colspan='1' align='right' >Total Forma de Pago $</td>" +
						"<td align='center'>"+fn_calcularFormaPago()+"</td>" +
						"<td align='center'></td>" +
					"</tr>");
                });                   
	    	});	      	    	           
	    };
		function fn_recorrerFormaPago(){
			var temp = 0;
			$("#tabla_formaPago tbody tr").each(function (index) {
            	var campo1,campo2,campo3;
            	$(this).children("td").each(function (index2){            		
	                switch (index2){
	                    case 0: 
	                    	campo1 = $(this).text();
	                    	break;
	                    case 1: 
	                    	campo2 = $(this).text();
							break;	                    
						case 2: 
	                    	campo3 = $(this).text();
							break;	                    
	                } 	               
            	});            	
            	if(campo1 == $("#select_formaPago option:selected").val()){
                	temp++;
                }            	            	
        	});
        	return temp;
        }
		function fn_calcularFormaPago(){        	
			var temp = 0;
			$("#tabla_formaPago tbody tr").each(function (index) {
            	var campo1
            	$(this).children("td").each(function (index2){            		
	                switch (index2){
	                    case 2: 
	                    	campo1 = $(this).text();
	                    	break;	                    
	                } 	               
            	});
            	temp = temp + parseFloat(campo1);
            	temp = Math.round(temp * 100) /100;
        	});
        	return temp.toFixed(2);
        }
		$scope.fecthRecipients();
		$scope.fecthRecipientsEmpresa();
		$scope.fecthRecipientsTipo();		
		$scope.fecthRecipientsCodigoRetencion();
		$scope.fecthRecipientsFormaPago();
		jQuery(function($) {
			$('[data-toggle="tooltip"]').tooltip(); 			
			if(!ace.vars['touch']) {			
				$('.chosen-select').chosen({allow_single_deselect:true}); 
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
				}).trigger('resize.chosen');
				$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
					if(event_name != 'sidebar_collapsed') return;
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
				});				
			}
			$('#txt_9').datepicker({
	            autoclose: true,
	            format: "dd/mm/yyyy",
	            todayHighlight: true,
	            language: 'es',
	            startDate: '-28d',
	            endDate: '1d'
	        }).datepicker();
			var $validation = true;		
			$('#fuelux-wizard-container')
			.ace_wizard({
			})
			.on('changed.fu.wizard', function(evt, item) {
	    		$(".chosen-select").off('resize.chosen').on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						var $this = $(this);					 
						$this.next().css({'width': $this.parent().parent().width()}); 
					})
				}).trigger('resize.chosen')
			})
			.on('actionclicked.fu.wizard' , function(e, info){	
				if(info.step == 1){
					forceValidate($scope.formGeneral.$$controls)
					if(!$scope.formGeneral.$valid || $("#selectEmpresa").val() == '' || $("#selectTipoIdentificacion").val() == '' || $("#selectComprobante").val() == ''){
						e.preventDefault();						
						bootbox.dialog({
							message: "Error ! Complete todos los campos antes de continuar", 
							buttons: {
								"danger" : {
									"label" : "Aceptar",
									"className" : "btn-sm btn-danger"
								}
							}
						});	
					}
				}
				if(info.step == 2){										
					$scope.FormaPago.txt_13 = fn_calcular();
				}
			})
			.on('finished.fu.wizard', function(e) {
				if($scope.FormaPago.txt_13 == fn_calcularFormaPago()){
					generarDatos();
					$("#loading2").css("display","block");
						
				}else{
					bootbox.dialog({
						message: "Error! Los valores de forma de pago y total del documento no coincide", 
						buttons: {
							"success" : {
								"label" : "Aceptar",
								"className" : "btn-sm btn-primary"
							}
						}
					});		
				}
			}).on('stepclick.fu.wizard', function(e){
				//e.preventDefault();//this will prevent clicking and selecting steps
			});	
			
	       
		});	
		
	});	
