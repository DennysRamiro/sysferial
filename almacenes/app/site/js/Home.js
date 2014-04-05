var urlCliente = 'api/listaClientes';
var urlProducto = 'api/listaProductos';
var urlCalculoTotales = 'api/calculoTotalesFinales';
var urlRegistroCliente = 'api/registrarCliente';
var urlRegistrarCabecera = 'api/registrarCabecera';
//var urlRutaDennys = 'api/rutaDennys';
var  urlFechaProceso = 'api/fechaProcesoSistema'

$(document).on("ready", function() {
    var urlCodigo = 'api/codigoUnicoVenta';
    var promise = ajaxCall(urlCodigo,'GET',{});
    promise.then(function(json){
        json = JSON.parse(json);
        $('#id-cabecera').val(json.codigo);
        $('#idCabecera').val(json.codigo);
    });

  /*  var dennysReturn = ajaxCall(urlRutaDennys, 'GET', {nombre:'dennys', apellido:'Gutierrez'});
    dennysReturn.then(function(res){
        console.log(res);
        datos = JSON.parse(res);
        console.log(datos);
    });*/

    /** metodo que carga la fecha por defecto del sistema **/
    establecerFecha();

    clienteAutocomeplete();

    /** Metodo que permite el autcompletado **/
    productoAutocomplete();

    /**Evento que activa el tpl de clientes**/
    crearFormCliente();

    /** Evento para guardar la cabecera **/
    guardaCabeceraItems();


    /*** Metodo que  cierra la venta de nuevo cliente **/
    $('#btn-close-cliente').on('click', function(){
        $('#div-venta-nuevo-cliente').dialog('close');
    });

    /** Metodo que calcula el total en el grid **/

    $('#precioTotal').on('change', function(){
        calcularTotal();
    });

    $('#cantidad').on('change', function(){
        calcularTotal();
    });
    $("#tabla-detalle-venta tbody tr").on('click', function(event) {
        $(oTable.fnSettings().aoData).each(function (){
            $(this.nTr).removeClass('row_selected');
        });
        $(event.target.parentNode).addClass('row_selected').css({
            "background-color":"#FFF"
        });
    });
    /* Init DataTables */
    $('#tabla-detalle-venta').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "deferRender": true,
        "sPaginationType": "full_numbers",
        "sAjaxSource": "api/listaItems?codigo="+$('#id-cabecera').val(),
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
            { "sTitle": "PRECION UNITARIO" , "sClass": "center"},
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
        sUpdateURL: "api/actualizarItem",
        sAddURL: "api/agregarItem",
        sDeleteURL: "api/eliminarItem",
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
            title: 'AGREGAR NUEVO ITEM',
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
function crearFormCliente(){
    $('#id-agregar-nuevo-cliente').on('click', function(){
        $('#div-venta-nuevo-cliente').dialog({
            width: 800,
            height : 400,
            modal:true,
            draggable:true
        });
    });
    procesarFormularioCliente();
}

/** Este es el metodo que procesa los datos del cliente**/
function procesarFormularioCliente(){
    $('#form-vent-nuevo-cliente').on('submit', function(e){
        e.preventDefault();
        if($('#form-vent-nuevo-cliente').valid()){
            var datos = $(this).serializeArray();
            var out = ajaxCall(urlRegistroCliente, 'POST', datos);
            out.then(function(json){
                json = JSON.parse(json);
                $('#div-venta-nuevo-cliente').dialog('close');
                $('#nombre').val(json.nombre);
                $('#NIT').val(json.nit);
                $('#id-cliente').val(json.codcliente);
            });
        }else{
            $('#msg-error-cliente-form').append('<p> Los datos no estan completos</p>');
        }

    });
}

/** este es el metodo que guarda la cabecera de los items **/
function guardaCabeceraItems(){
    $('#btn-imprimir-venta').on('click', function(){
        var fecha =$('#fecha').val();
        var cliente =$('#nombre').val();
        var nit =$('#NIT').val();
        var idcliente =$('#id-cliente').val();
        var idCabecera =$('#id-cabecera').val();
        var total = $('#total-ventas-final').val();
        if(cliente !== "" && nit !== ""){
            var datos =  {idCabecera:idCabecera, idCliente:idcliente, fecha:fecha, nit:nit, nombre:cliente, total:total};
            var out = ajaxCall(urlRegistrarCabecera, 'POST', datos);
            out.then(function(json){
                json = JSON.parse(json);
                if(json.completo){
                    generarReporte(json.idCabecera);
                }
            });
        }else{
            $('#msg-error-cabecera').empty().append('El nombre o el nit estan vacios!')
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
        $('#fecha').val(res.fecha_proceso);
    });
}

/** Este es el metodo autocomplete de cliente **/

function clienteAutocomeplete(){
    $('#nombre').autocomplete({
        source : urlCliente,
        minLength: 0,
        width: 320,
        max: 4,
        highlight: false,
        scroll: true,
        scrollHeight: 300,
        select: function(event, ui){
            $('#NIT').val(ui.item.nit);
            $('#id-cliente').val(ui.item.id);
        }
    });
}

/** Metodo de autocomplete de producto **/
function productoAutocomplete(){
    $('#descripcion').autocomplete({
        source : urlProducto,
        minLength: 0,
        select: function(event, ui){
            $('#formAddNewRow')[0].reset();
            $('#idProducto').val(ui.item.id);
            $('#precioUnitario').val(ui.item.precio);
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
    precio = $('#precioUnitario').val();
    cantidad = $('#cantidad').val();
    $('#precioTotal').val(precio * cantidad);
}
/** CALCULO DE TOTALES FINALES **/
function calculoTotalesFinal(){
    var outer = ajaxCall(urlCalculoTotales,'GET', {codigo:$('#id-cabecera').val()});
    outer.then(function(res){
        res = JSON.parse(res);
        $('#total-ventas-final').val(res.precioTotal);
    });
}


