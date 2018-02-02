app.controller('retencion_fuenteController', function ($scope, $route) {

	$scope.$route = $route;
	jQuery(function($) {
		$('[data-toggle="tooltip"]').tooltip();
		
		$('#valor').ace_spinner({value:0,min:0,max:100,step:1, on_sides: true, icon_up:'ace-icon fa fa-plus bigger-110', icon_down:'ace-icon fa fa-minus bigger-110', btn_up_class:'btn-success' , btn_down_class:'btn-danger'});	
		
		// estilos select2 
		$(".select2").css({
		    'width': '100%',
		    allow_single_deselect: true,
		    no_results_text: "No se encontraron resultados",
		    allowClear: true,
		}).select2().on("change", function(e) {
			$(this).closest('form').validate().element($(this));
	    });

	    $("#select_cuenta_debito,#select_cuenta_credito").select2({
		  	allowClear: true
		});
		// fin

	    //inicio validacion anticipos
		$('#form_retencion_fuente').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				nombre_fuente: {
					required: true				
				},
				valor: {
					required: true				
				},
				codigo_formulario: {
					required: true				
				},
				select_cuenta_debito: {
					required: true				
				},
				select_cuenta_credito: {
					required: true				
				},
			},
			messages: {
				nombre_fuente: {
					required: "Por favor, Indique Nombre de la Fuente",
				},
				valor: {
					required: "Por favor, Indique Valor de la Fuente",
				},
				codigo_formulario: {
					required: "Por favor, Indique Código del Formulario",
				},
				select_cuenta_debito: {
					required: "Por favor, Seleccione una Cuenta",
				},
				select_cuenta_credito: {
					required: "Por favor, Seleccione una Cuenta",
				},

			},
			//para prender y apagar los errores
			highlight: function(e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function(e) {
				$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
				$(e).remove();
			},
			submitHandler: function(form) {}
		});
		// Fin

		// funcion validar solo numeros
		function ValidNum() {
		    if (event.keyCode < 48 || event.keyCode > 57) {
		        event.returnValue = false;
		    }
		    return true;
		}
		// fin

		// llenar combo cuenta debito
		function llenar_select_cuenta_debito() {
			$.ajax({
				url: 'data/retencion_fuente/app.php',
				type: 'post',
				data: {llenar_cuenta:'llenar_cuenta'},
				success: function(data) {
					$('#select_cuenta_debito').html(data);
				}
			});
		}
		// fin

		// llenar combo cuenta credito
		function llenar_select_cuenta_credito() {
			$.ajax({
				url: 'data/retencion_fuente/app.php',
				type: 'post',
				data: {llenar_cuenta:'llenar_cuenta'},
				success: function(data) {
					$('#select_cuenta_credito').html(data);
				}
			});
		}
		// fin

		// inicio
		$("#valor").keypress(ValidNum);
		llenar_select_cuenta_debito();
		llenar_select_cuenta_credito();
		// fin

		// reset formularios
		function reset_form() {
			$('#nombre_fuente').val('');
			$('#valor').val(0);
			$('#codigo_formulario').val('');
			$("#select_cuenta_debito").select2('val', 'All');
			$("#select_cuenta_credito").select2('val', 'All');
		}
		// fin

		// cargar ultimo codigo
		$('#btn_abrir').click(function() {
			reset_form();
			$('#btn_0').attr('disabled', false);
			$("#btn_0").text("");
	    	$("#btn_0").append("<i class='ace-icon fa fa-save'></i> Guardar");
		});
		// fin

		// guardar anticipos
		$('#btn_0').click(function() {
			var respuesta = $('#form_retencion_fuente').valid();

			if (respuesta == true) {
				var formulario = $("#form_retencion_fuente").serialize();
				var texto = ($("#btn_0").text()).trim();

				if(texto == "Guardar") {
					var oper = "add";
					$.ajax({
				        url: "data/retencion_fuente/app.php",
				        data: formulario+"&oper=" + oper,
				        type: "POST",
				        success: function(data) {
				        	if(data == '1') {
				        		$.gritter.add({
									title: 'Mensaje',
									text: 'Registro Agregado correctamente <i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>',
									time: 1000				
								});

								$('#btn_0').attr('disabled', true);
								reset_form();
								jQuery("#grid-table").jqGrid().trigger("reloadGrid");
								$('#myModal').modal('hide');
					    	} else {
					    		if(data == '3') {
					    			$.gritter.add({
										title: 'Mensaje',
										text: 'Error... El nombre de la Fuente ya esta Agregado',
										time: 1000				
									});
					    		}
					    		$("#nombre_fuente").val('');
					    	}                                                
				        },
				        error: function (xhr, status, errorThrown) {
					        alert("Hubo un problema!");
					        console.log("Error: " + errorThrown);
					        console.log("Status: " + status);
					        console.dir(xhr);
				        }
				    });
				} else {
					if(texto == "Modificar") {
						var oper = "edit";
						$.ajax({
					        url: "data/retencion_fuente/app.php",
					        data: formulario+"&oper=" + oper,
					        type: "POST",
					        success: function(data) {
					        	if(data == '2') {
					        		$.gritter.add({
										title: 'Mensaje',
										text: 'Registro Modificado correctamente <i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>',
										time: 1000				
									});

									$('#btn_0').attr('disabled', true);
									reset_form();
									jQuery("#grid-table").jqGrid().trigger("reloadGrid");
									$('#myModal').modal('hide');
						    	} else {
						    		if(data == '3') {
						    			$.gritter.add({
											title: 'Mensaje',
											text: 'Error... El Nombre de la Fuente ya fue Agregado',
											time: 1000				
										});
										$("#nombre_fuente").val('');
						    		}
						    	}                                                
					        },
					        error: function (xhr, status, errorThrown) {
						        alert("Hubo un problema!");
						        console.log("Error: " + errorThrown);
						        console.log("Status: " + status);
						        console.dir(xhr);
					        }
					    });
					}	
				}
			}
		});
		// fin
	})

	
	jQuery(function($) {
		var grid_selector = "#grid-table";
	    var pager_selector = "#grid-pager";

	    //cambiar el tamaño para ajustarse al tamaño de la página
	    $(window).on('resize.jqGrid', function() {
	        $(grid_selector).jqGrid('setGridWidth', $(".page-content").width());
	    });
	    //cambiar el tamaño de la barra lateral collapse/expand
	    var parent_column = $(grid_selector).closest('[class*="col-"]');
	    $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
	        if(event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed') {
	            //para dar tiempo a los cambios de DOM y luego volver a dibujar!!!
	            setTimeout(function() {
	                $(grid_selector).jqGrid('setGridWidth', parent_column.width());
	            }, 0);
	        }
	    });

	    jQuery(grid_selector).jqGrid({
	    	datatype: "xml",
	        url: 'data/retencion_fuente/xml_retencion_fuente.php',
			colNames:['ID','NOMBRE FUENTE','VALOR FUENTE','CÓDIGO FORMULARIO','CUENTA BÉBITO','CUENTA CRÉDITO','FECHA CREACIÓN'],
			colModel:[
				{name:'id',index:'id', frozen:true,align:'left',search:false,editable: true, hidden: true, editoptions: {readonly: 'readonly'}},
				{name:'nombre_fuente',index:'nombre_fuente',width:600, editable:true, editoptions:{size:"20", maxlength:"30"}, editrules: {required: true}},
				{name:'valor',index:'valor',width:100, editable:true, editoptions:{size:"", maxlength:""}, editrules: {required: true}},
				{name:'codigo_formulario',index:'codigo_formulario', width:150, editable: true, search:false,editoptions: {}},
				{name:'cuenta_debito',index:'cuenta_debito', width:150, editable: true, search:false,editoptions: {}},
				{name:'cuenta_credito',index:'cuenta_credito', width:150, editable: true, search:false,editoptions: {}},
	            {name:'fecha_creacion',index:'fecha_creacion', width:150,editable: true, search:false, editoptions:{}}
			],
	        rowNum:10,
	        rowList: [10,20,30],
	        height: 330,
	        pager: pager_selector,
	        sortname: 'id',
	        sortorder: 'asc',
	        autoencode: false,
	        rownumbers: true,
	        altRows: true,
	        multiselect: false,
	        multiboxonly: false,
	        viewrecords: true,
	        loadComplete: function() {
	            var table = this;
	            setTimeout(function() {
	                styleCheckbox(table);
	                updateActionIcons(table);
	                updatePagerIcons(table);
	                enableTooltips(table);
	            }, 0);
	        },
	        ondblClickRow: function(rowid) {     	            	            
	            var gsr = jQuery(grid_selector).jqGrid('getGridParam','selrow');                                              
            	var ret = jQuery(grid_selector).jqGrid('getRowData',gsr);
            	var id = ret.id;

				$('#myModal').modal('show');
				$('#btn_0').attr('disabled', false);
				$("#btn_0").text("");
	    		$("#btn_0").append("<i class='ace-icon fa fa-edit'></i> Modificar");

				$('#id').val(ret.id);
				$('#nombre_fuente').val(ret.nombre_fuente);
				$('#codigo_formulario').val(ret.codigo_formulario);
				$('#valor').val(ret.valor);
				$("#select_cuenta_debito").select2('val', ret.cuenta_debito).trigger("change"); 
				$("#select_cuenta_credito").select2('val', ret.cuenta_credito).trigger("change");	            	            
	        },
	        editurl: "data/retencion_fuente/app.php",
	        caption: "LISTA RETENCIÓN FUENTE"
	    });
	    $(window).triggerHandler('resize.jqGrid'); //cambiar el tamaño para hacer la rejilla conseguir el tamaño correcto

	    function aceSwitch(cellvalue, options, cell) {
	        setTimeout(function() {
	            $(cell).find('input[type=checkbox]')
	            .addClass('ace ace-switch ace-switch-5')
	            .after('<span class="lbl"></span>');
	        }, 0);
	    }
	    //enable datepicker
	    function pickDate(cellvalue, options, cell) {
	        setTimeout(function() {
	            $(cell).find('input[type=text]')
	            .datepicker({format:'yyyy-mm-dd', autoclose:true}); 
	        }, 0);
	    }
	    //navButtons
	    jQuery(grid_selector).jqGrid('navGrid', pager_selector, { //navbar options
	        edit: false,
	        editicon: 'ace-icon fa fa-pencil blue',
	        add: false,
	        addicon: 'ace-icon fa fa-plus-circle purple',
	        del: true,
	        delicon: 'ace-icon fa fa-trash-o red',
	        search: false,
	        searchicon: 'ace-icon fa fa-search orange',
	        refresh: true,
	        refreshicon: 'ace-icon fa fa-refresh green',
	        view: false,
	        viewicon: 'ace-icon fa fa-search-plus grey'
	    },
	    {
	    	closeAfterEdit: true,
	        recreateForm: true,
	        viewPagerButtons: false,
	        overlay: false,
	        beforeShowForm: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	            style_edit_form(form);
	        },
	        afterSubmit: function(response) {}
	    },
	    {
	        closeAfterAdd: true,
	        recreateForm: true,
	        viewPagerButtons: false,
	        overlay: false,
	        beforeShowForm: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
	            .wrapInner('<div class="widget-header" />')
	            style_edit_form(form);
	        },
	        afterSubmit: function(response) {}
	    },
	    {
	        //delete record form
	        recreateForm: true,
	        overlay: false,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            if(form.data('styled')) return false;
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	            style_delete_form(form);
	            form.data('styled', true);
	        },
	        onClick: function(e) {}
	    },
	    {
	        recreateForm: true,
	        overlay:false,
	        afterShowSearch: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
	            style_search_form(form);
	        },
	        afterRedraw: function() {
	            style_search_filters($(this));
	        },
	        multipleSearch: false,
	        overlay: false,
	        sopt: ['eq', 'cn'],
	        defaultSearch: 'eq', 
	    },
	    {
	        recreateForm: true,
	        overlay:false,
	        beforeShowForm: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
	        }
	    })

	    function style_edit_form(form) {
	        form.find('input[name=sdate]').datepicker({format:'yyyy-mm-dd' , autoclose:true})
	        form.find('input[name=stock]').addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');

	        var buttons = form.next().find('.EditButton .fm-button');
	        buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
	        buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
	        buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>')
	        
	        buttons = form.next().find('.navButton a');
	        buttons.find('.ui-icon').hide();
	        buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
	        buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');       
	    }

	    function style_delete_form(form) {
	        var buttons = form.next().find('.EditButton .fm-button');
	        buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
	        buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
	        buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
	    }
	    
	    function style_search_filters(form) {
	        form.find('.delete-rule').val('X');
	        form.find('.add-rule').addClass('btn btn-xs btn-primary');
	        form.find('.add-group').addClass('btn btn-xs btn-success');
	        form.find('.delete-group').addClass('btn btn-xs btn-danger');
	    }

	    function style_search_form(form) {
	        var dialog = form.closest('.ui-jqdialog');
	        var buttons = dialog.find('.EditTable')
	        buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'ace-icon fa fa-retweet');
	        buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'ace-icon fa fa-comment-o');
	        buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'ace-icon fa fa-search');
	    }
	    
	    function beforeDeleteCallback(e) {
	        var form = $(e[0]);
	        if(form.data('styled')) return false;
	        
	        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	        style_delete_form(form);
	        
	        form.data('styled', true);
	    }
	    
	    function beforeEditCallback(e) {
	        var form = $(e[0]);
	        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	        style_edit_form(form);
	    }

	    function styleCheckbox(table) {}
	    
	    function updateActionIcons(table) {}
	    
	    function updatePagerIcons(table) {
	        var replacement = {
	            'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
	            'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
	            'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
	            'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
	        };
	        $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function() {
	            var icon = $(this);
	            var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
	            
	            if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
	        })
	    }

	    function enableTooltips(table) {
	        $('.navtable .ui-pg-button').tooltip({container:'body'});
	        $(table).find('.ui-pg-div').tooltip({container:'body'});
	    }

	    $(document).one('ajaxloadstart.page', function(e) {
	        $(grid_selector).jqGrid('GridUnload');
	        $('.ui-jqdialog').remove();
	    });
	});
});