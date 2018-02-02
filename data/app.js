var app = angular.module('scotchApp', ['ngRoute','ngResource','ngStorage']);

app.directive('hcChart', function () {
    return {
        restrict: 'E',
        template: '<div></div>',
        scope: {
            options: '='
        },
        link: function (scope, element) {
            Highcharts.chart(element[0], scope.options);
        }
    };
})

app.directive('hcPieChart', function () {
    return {
        restrict: 'E',
        template: '<div></div>',
        scope: {
            title: '@',
            data: '='
        },
        link: function (scope, element) {
            Highcharts.chart(element[0], {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: scope.title
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    data: scope.data
                }]
            });
        }
    };
})

// configure our routes
app.config(function($routeProvider) {
    $routeProvider
        // route page initial
        .when('/', {
            templateUrl : 'data/inicio/index.html',
            // controller  : 'mainController',
            activetab: 'inicio'
        })
        // route cargos
        .when('/cargos', {
            templateUrl : 'data/cargos/index.html',
            controller  : 'cargosController',
            activetab: 'cargos'
        })
        // route privilegios
        .when('/privilegios', {
            templateUrl : 'data/privilegios/index.html',
            controller  : 'privilegiosController',
            activetab: 'privilegios'
        })
        // route empresa
        .when('/empresa', {
            templateUrl : 'data/empresa/index.html',
            controller  : 'empresaController',
            activetab: 'empresa'
        })
        // route ambiente
        .when('/tipo_ambiente', {
            templateUrl : 'data/tipo_ambiente/index.html',
            controller  : 'tipo_ambienteController',
            activetab: 'tipo_ambiente'
        })
        // route emision
        .when('/tipo_emision', {
            templateUrl : 'data/tipo_emision/index.html',
            controller  : 'tipo_emisionController',
            activetab: 'tipo_emision'
        })
        // route tipo comprobante
        .when('/tipo_comprobante', {
            templateUrl : 'data/tipo_comprobante/index.html',
            controller  : 'tipo_comprobanteController',
            activetab: 'tipo_comprobante'
        })
        // route tipo documento
        .when('/tipo_documento', {
            templateUrl : 'data/tipo_documento/index.html',
            controller  : 'tipo_documentoController',
            activetab: 'tipo_documento'
        })
        // route formas pago
        .when('/formas_pago', {
            templateUrl : 'data/formas_pago/index.html',
            controller  : 'formas_pagoController',
            activetab: 'formas_pago'
        })
        // route porcentajes
        .when('/porcentaje', {
            templateUrl : 'data/porcentaje/index.html',
            controller  : 'porcentajeController',
            activetab: 'porcentaje'
        })
        // route retencion fuente
        .when('/retencion_fuente', {
            templateUrl : 'data/retencion_fuente/index.html',
            controller  : 'retencion_fuenteController',
            activetab: 'retencion_fuente'
        })
        // route retencion iva
        .when('/retencion_iva', {
            templateUrl : 'data/retencion_iva/index.html',
            controller  : 'retencion_ivaController',
            activetab: 'retencion_iva'
        })
        // route tipo producto
        .when('/tipo_producto', {
            templateUrl : 'data/tipo_producto/index.html',
            controller  : 'tipo_productoController',
            activetab: 'tipo_producto'
        })
        // route clientes
        .when('/clientes', {
            templateUrl : 'data/clientes/index.html',
            controller  : 'clientesController',
            activetab: 'clientes'
        })
        // route login
        .when('/login', {
            templateUrl : 'data/login/index.html',
            controller  : 'loginController',
        })
        // route usuarios
        .when('/usuarios', {
            templateUrl : 'data/usuarios/index.html',
            controller  : 'usuariosController',
            activetab: 'usuarios'
        })
        // route cuenta
        .when('/cuenta', {
            templateUrl : 'data/cuenta/index.html',
            controller  : 'cuentaController',
            activetab: 'cuenta'
        })
});

app.factory('Auth', function($location) {
    var user;
    return {
        setUser : function(aUser) {
            user = aUser;
        },
        isLoggedIn : function() {
            var ruta = $location.path();
            var ruta = ruta.replace("/","");
            var accesos = JSON.parse(Lockr.get('users'));
                accesos.push('inicio');
                accesos.push('');

            var a = accesos.lastIndexOf(ruta);
            if (a < 0) {
                return false;    
            } else {
                return true;
            }
        }
    }
});


app.run(['$rootScope', '$location', 'Auth', function ($rootScope, $location, Auth) {
    $rootScope.$on('$routeChangeStart', function (event) {
        var rutablock = $location.path();
        if (!Auth.isLoggedIn()) {
            event.preventDefault();
            swal({
                title: "Lo sentimos acceso denegado",
                type: "warning",
            });
        } else { }
    });
}]);

// consumir servicios sri
app.factory('loaddatosSRI', function($resource) {
    return $resource("http://186.4.167.12/appserviciosnext/public/index.php/getDatos/:id", {
        id: "@id"
    });
});
// fin

    