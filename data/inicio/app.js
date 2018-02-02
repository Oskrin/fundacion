app.controller('mainController', function ($scope, $route, $timeout) {
	$scope.$route = $route;

	jQuery(function($) {

    // cerrar sesion
    $scope.salir = function() {
        loginService.salir();
    } 
    // fin
   
    // funcion informacion
    function informacion() {
        $.ajax({
            type: "POST",
            url: "data/inicio/app.php",
            data: {cargar_informacion:'cargar_informacion'},
            dataType: 'json',
            async: false,
            success: function(data) {
                $scope.usuario = data.usuario;
                $scope.conexion = data.fecha_creacion;               
            }
        });
    }
    // fin

    // funcion chat
    function chat() {
        $.ajax({
            type: "POST",
            url: "data/inicio/app.php",
            data: {cargar_chat:'cargar_chat'},
            dataType: 'json',
            async: false,
            success: function(data) {
                $scope.datos = data;              
            }
        });
    }
    // fin

    // funcion  guardar chat
    function save_chat() {
        if ($('#message').val() == '') {
            $.gritter.add({
                title: 'Error... Ingrese un mensaje',
                class_name: 'gritter-error gritter-center',
                time: 1000,
            });
            $('#message').focus(); 
        } else {
            $.ajax({
                type: "POST",
                url: "data/inicio/app.php",
                data: {guardar_chat:'guardar_chat', mensaje: $('#message').val()},
                dataType: 'json',
                async: false,
                success: function(data) {
                    if (data == 1) {
                        $('#message').val('');
                        $('#message').focus();
                        chat();
                    }
                }
            });
        }    
    }
    // fin

    // scroll final
    function scroll_buttom_chat() {
        // $timeout(function() {
        //     var scroller = document.getElementById("style-5");
        //     scroller.scrollTop = scroller.scrollHeight;
        // }, 0, false);
    }
    // fin

    // enviar chat
    $scope.enviar_chat = function (data, event) {
        save_chat();    
    }
    // fin

    // funcion enter
    $scope.myFunction = function(keyEvent) {
      if (keyEvent.which === 13)
        save_chat();
        // scroll_buttom_chat();
    }
    // fin

    // incio
    informacion();
    scroll_buttom_chat();
    chat();
    // fin

	});	
});