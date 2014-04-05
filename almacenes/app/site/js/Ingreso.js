
var urlProveedor = 'api/listaProveedores';
var urlProducto = 'api/listaProductosIngreso';
var urlCalculoTotales = 'api/calculoTotalesFinales';
var urlRegistroProveedor = 'api/registrarProveedores';
var urlRegistrarCabecera = 'api/registrarCabeceraIngreso';
var  urlFechaProceso = 'api/fechaProcesoSistema';

$(document).on("ready", function() {

    var urlCodigo = 'api/codigoUnicoIngreso';
    var promise = ajaxCall(urlCodigo,'GET',{});
    promise.then(function(json){
        json = JSON.parse(json);
        $('#id-cabecera-ingreso').val(json.codigo);
        $('#idCabecera-det-ingreso').val(json.codigo);
    });

    /** metodo que carga la fecha por defecto del sistema **/
    establecerFecha();
    /** Metodo que permite el autcompletado **/

    proveedorAutocomplete();
    productoAutocomplete();
    /**Evento que activa el tpl de clientes**/
    crearFormProveedor();

    /** Evento para guardar la cabecera **/
    guardaCabeceraItemsIngreso();

    /*** Metodo que  cierra la venta de nuevo cliente **/
    $('#btn-close-cliente').on('click', function(){
        $('#div-venta-nuevo-cliente').dialog('close');
    });

    /** Metodo que calcula el total en el grid **/

    $('#precioTotal-det-ingreso').on('change', function(){
        calcularTotal();
    });

    $('#cantidad-det-ingreso').on('change', function(){
        calcularTotal();
    });

    $( "#fechaVencimiento-det-ingreso" ).datepicker();

    $( "#cantidad-det-ingreso" ).numeric();
    $( "#precioCompra-det-ingreso" ).numeric('.');
    $( "#precioventa-det-ingreso" ).numeric('.');

    $("#tabla-detalle-ingreso tbody tr").on('click', function(event) {
        $(oTable.fnSettings().aoData).each(function (){
            $(this.nTr).removeClass('row_selected');
        });
        $(event.target.parentNode).addClass('row_selected').css({
            "background-color":"#FFF"
        });
    });
    /* Init DataTables */
    $('#tabla-detalle-ingreso').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "deferRender": true,
        "sPaginationType": "full_numbers",
        "sAjaxSource": "api/listaItems?codigo="+$('#id-cabecera-ingreso').val(),
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            calculoTotalesFinal();
        },
        "fnDrawCallback" : function(nRow, aData){
        },
        "aoColumns": [
            { "sTitle": "CANTIDAD", "sClass": "center" },
            { "sTitle": "DESCRIPCION" , "sClass": "center"},
            { "sTitle": "PRECION VENTA" , "sClass": "center"},
            { "sTitle": "PRECIO TOTAL", "sClass": "center" }
        ],
        "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningúna item disponible en la venta",
            "sInfo":           " ",
            "sInfoEmpty":      " ",
            "sInfoFiltered":   " ",
            "sInfoPostFix":    "",
            "sSearch":         "Consultar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Procesando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Ultimo",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }).makeEditable({
        sUpdateURL: "api/actualizarItemIngreso",
        sAddURL: "api/agregarItemIngreso",
        sDeleteURL: "api/eliminarItemIngreso",
        oAddNewRowButtonOptions: {
            label: "AGREGAR PRODUCTO",
            icons: { primary: 'ui-icon-plus' }
        },
        oDeleteRowButtonOptions: {
            label: "DAR DE BAJA",
            icons: { primary: 'ui-icon-trash' }
        },
        oAddNewRowOkButtonOptions: {
            label: "GUARDAR",
            icons: { primary: 'ui-icon-check' },
            name: "action",
            value: "add-new"
        },
        oAddNewRowCancelButtonOptions: {
            label: "CERRAR",
            class: "back-class",
            name: "action",
            value: "cancel-add",
            icons: { primary: 'ui-icon-close' }
        },
        oAddNewRowFormOptions: {
            title: 'AGREGAR NUEVO PRODUCTO',
            show: "fade",
            hide: "fade"

        }
    });
    $('.btn_ventas').button();
});

function ajaxCall(url,type, data){
    var dfd = $.Deferred();
    dfd = $.ajax({
        url : url,
        async:false,
        data : data,
        type : type
    });
    return dfd.promise();
}


/** Metodo que crea el formulario del cliente **7
 *
 */
function crearFormProveedor(){
    $('#id-agregar-nuevo-proveedor-ingreso').on('click', function(){
        $('#div-ingreso-nuevo-proveedor').dialog({
            width: 900,
            height : 300,
            modal:true,
            draggable:true
        });

    });
    procesarFormularioProveedor();
}

/** Este es el metodo que procesa los datos del cliente**/

function procesarFormularioProveedor(){
    $('#form-ingreso-nuevo-proveedor').on('submit', function(e){
        e.preventDefault();
        if($('#form-ingreso-nuevo-proveedor').valid()){
            var datos = $(this).serializeArray();
            var out = ajaxCall(urlRegistroProveedor, 'POST', datos);
                out.then(function(json){
                json = JSON.parse(json);
                    console.log(json);
                    //console.log(json.nombre);
                    //console.log(json.codproveedor);
                $('#div-ingreso-nuevo-proveedor').dialog('close');
                $('#nombre-ingreso').val(json.nombre);
                $('#NIT-ingreso').val(json.nit);
                $('#id-proveedor').val(json.codproveedor);
            });
        }else{
            $('#msg-error-proveedor-form').append('<p> Los datos no estan completos</p>');
        }

    });
}

/** este es el metodo que guarda la cabecera de los items **/
function guardaCabeceraItemsIngreso(){
    $('#btn-imprimir-ingreso').on('click', function(){
        var fecha =$('#fecha-ingreso').val();
        var proveedor =$('#nombre-ingreso').val();
        var nit =$('#NIT-ingreso').val();
        var idproveedor =$('#id-proveedor-ingreso').val();
        var idCabecera =$('#id-cabecera-ingreso').val();
        var total = $('#total-ingreso-final').val();
        if(proveedor !== "" && nit !== ""){
            var datos =  {idCabecera:idCabecera, idproveedor:idproveedor, fecha:fecha, nit:nit, nombre:proveedor, total:total};
            var out = ajaxCall(urlRegistrarCabecera, 'POST', datos);
            out.then(function(json){
                json = JSON.parse(json);
                if(json.completo){
                    generarReporte(json.idCabecera);
                }
            });
        }else{
            $('#msg-error-cabecera-ingreso').empty().append('El nombre o el nit estan vacios!')
        }
    });
}

/** generar el reporte **/
function generarReporte(codigo){
    /*** codigo para generar el reporte */
    $(location).attr('href','app/site/venta_tpl/VentasReporte.php?val='+$('#id-cabecera').val());
   /* var win = window.open('app/site/venta_tpl/VentasReporte.php?val='+codigo,'_blank');
    win.focus();*/
}


/** funcion que establece la fecha actual **/
function establecerFecha(){
    var fechaAjax = ajaxCall(urlFechaProceso, 'GET', {});
    fechaAjax.done(function(res){
        res = JSON.parse(res);
        $('#fecha-ingreso').val(res.fecha_proceso);
    });
}

/** Este es el metodo autocomplete de cliente **/

function proveedorAutocomplete(){
    $('#nombre-ingreso').autocomplete({
        source : urlProveedor,
        minLength: 0,
        width: 320,
        max: 3,
        highlight: false,
        scroll: true,
        scrollHeight: 300,
        select: function(event, ui){
            $('#NIT-ingreso').val(ui.item.nit);
            $('#id-proveedor-ingreso').val(ui.item.id);
        }
    });
}


/** Metodo de autocomplete de producto **/
function productoAutocomplete(){
    $('#nomproducto-det-ingreso').autocomplete({
        source : urlProducto,
        minLength: 0,
        select: function(event, ui){
            $('#formAddNewRow')[0].reset();
            $('#idProducto-det-ingreso').val(ui.item.id);
            $('#precioCompra-det-ingreso').val(ui.item.precio);
            calcularTotal();
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a> <img src='" + item.img + "' width='70' height='50' align='left'> <h4>"+item.value+"</h4> CANT.:"+item.cantidad+" P/U: " + item.precio + "<br> </a>" )
            .appendTo( ul );
    };
}



/** Calculo del Total **/
function calcularTotal(){
    precio = $('#precioventa-det-ingreso').val();
    cantidad = $('#cantidad-det-ingreso').val();
    $('#precioTotal-det-ingreso').val(precio * cantidad);
}
/** CALCULO DE TOTALES FINALES **/
function calculoTotalesFinal(){
    var outer = ajaxCall(urlCalculoTotales,'GET', {codigo:$('#id-cabecera-ingreso').val()});
    outer.then(function(res){
        res = JSON.parse(res);
        $('#total-ingreso-final').val(res.precioTotal);
    });
}



