//CARGA TABLA DE CATEGORIAS
function fn_CargarTablaCategorias(){
  $("#tbl_Categorias").dataTable().fnDestroy();
  $('#tbl_Categorias tbody').empty();

  $.post('models/Mantenedores/Categorias/sel_Categoria.php', {fn_Funcion:'CargarPanel'}, function(res){
		var json_Categorias = $.parseJSON(res);

    $('#tbl_Categorias').dataTable({
			"sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
			"iDisplayLength": 25,
			"oTableTools": {
				"aButtons": [
					"xls"
				],
				"sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
			},
			"language": {
				"search": "",
				"emptyTable": "No hay registros.",
				"zeroRecords": "No existe ninguna coincidencias en los registros.",
				"sLengthMenu": "_MENU_",
				"oPaginate": {
					"sPrevious": "Ant",
					"sNext": "Sig"
				}
			},
			"data": json_Categorias,
			"aoColumns": [
        {"data":  "categoria_Nombre"},
        {"data":  "categoria_Sigla"},
        {"data":  "categoria_Tipo"},
        {"data":  "categoria_Estado"},
        {"data":  "categoria_Acciones"}
      ],
      columnDefs  : [
        {"targets": 1, "orderable": false},
        {"targets": 2, "orderable": false}
      ],
			"aaSorting": [0, 'asc']
		});
  });
}

//Cambia estado del categoria
function fn_CambiarEstadoCategoria(pvi_CategoriaId, pvs_Estado){
  $.post('models/Mantenedores/Categorias/upd_Categoria.php', {fn_Funcion:'CambiarEstado', pvi_CategoriaId:pvi_CategoriaId, pvs_Estado:pvs_Estado}, function(res){
    if(res == 1){
      fn_Alerta("success", (pvs_Estado == 'D' ? "Desactivada!" : "Activada!"), "Categoria "+(pvs_Estado == 'D' ? "desactivada" : "activada")+" correctamente.", "Aceptar");

      fn_CargarTablaCategorias();
    }else{
      fn_Alerta("error", "Error!", "No se pudo "+(pvs_Estado == 'D' ? "desactivar" : "activar")+" la categoria por el siguiente error: " + res, "Aceptar");
    }
  });
}

//Edita información categoria
function fn_EditarCategoria(pvi_CategoriaId){
  $.post('models/Mantenedores/Categorias/sel_Categoria.php', {fn_Funcion:'EditarCategoria', pvi_CategoriaId:pvi_CategoriaId}, function(res){
    var json_Categoria = $.parseJSON(res);

    $("#lbl_TituloModalCategoria").text("Categoria - Editar");

    window.vgi_CategoriaId = pvi_CategoriaId;
    $("#txt_CategoriaNombre").val(json_Categoria['categoria_Nombre']);
    $("#txt_CategoriaSigla").val(json_Categoria['categoria_Sigla']);
    $("#cmb_CategoriaTipo").val(json_Categoria['categoria_Tipo']);

    $("#mdl_Categoria").modal();
  });
}

$(document).ready(function (){
  //CARGAS PRINCIPALES
  fn_CargarTablaCategorias();

  //ABRIR FORMULARIO CATEGORIA
  $("#btn_AbrirModalCategoria").click(function (){
    window.vgi_CategoriaId = 0;

    $("#lbl_TituloModalCategoria").text("Categoria - Nuevo");

    $("#txt_CategoriaNombre").val('');
    $("#txt_CategoriaSigla").val('');
    $("#cmb_CategoriaTipo").val('');
  });

  //GUARDAR FORMULARIO Categoria
  $("#frm_Categoria").submit(function (e){
    e.preventDefault();

    var json_Categoria = {
      vls_CategoriaNombre  : $("#txt_CategoriaNombre").val(),
      vls_CategoriaSigla   : $("#txt_CategoriaSigla").val(),
      vls_CategoriaTipo    : $("#cmb_CategoriaTipo").val()
    };

    var sql_Respuesta = '';
    if(window.vgi_CategoriaId == 0){ //CREANDO Categoria
      $.ajax({
        url     : 'models/Mantenedores/Categorias/ins_Categoria.php',
        type    : 'post',
        data    : {json_Categoria:json_Categoria, fn_Funcion:'InsertarCategoria'},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }else{ //EDITANDO Categoria
      $.ajax({
        url     : 'models/Mantenedores/Categorias/upd_Categoria.php',
        type    : 'post',
        data    : {json_Categoria:json_Categoria, fn_Funcion:'EditarCategoria', pvi_CategoriaId:window.vgi_CategoriaId},
        async   : false,
        success : function(res){
          sql_Respuesta = res;
        }
      });
    }

    if(sql_Respuesta == 1){
      fn_Alerta("success", "Guardada!", "Categoria guardada correctamente.", "Aceptar");

      $("#mdl_Categoria").modal("toggle");
      fn_CargarTablaCategorias();
    }else{
      fn_Alerta("error", "Error!", "No se pudo guardar la categoria por el siguiente error: " + sql_Respuesta, "Aceptar");
    }
  });
});
