(function () {
    "use strict";

    var treeviewMenu = $('.app-menu');

    // Toggle Sidebar
    $('[data-toggle="sidebar"]').click(function(event) {
        event.preventDefault();
        $('.app').toggleClass('sidenav-toggled');
    });

    // Activate sidebar treeview toggle
    $("[data-toggle='treeview']").click(function(event) {
        event.preventDefault();
        if(!$(this).parent().hasClass('is-expanded')) {
            treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
        }
        $(this).parent().toggleClass('is-expanded');
    });

    // Set initial active toggle
    $("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

    //Activate bootstrip tooltips
    $("[data-toggle='tooltip']").tooltip();

    $('.login-content [data-toggle="flip"]').click(function() {
        $('.login-box').toggleClass('flipped');
        return false;
    });

})();

window.open_modal = function (id, title){
    accion = "agregar";

    $("div.modal form")[0].reset();
    $("div.modal#"+ id).modal("show");
    $("div.modal#"+ id + " h4.modal-title").text(title);
}

window.charge_stores = function (element) {
    $(element).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
    $(element).attr('disabled', true);

    var formData = new FormData($("form#charge_stores_form")[0]);
    formData.append("_token", token);

    $.ajax({
        url: '/stores/charge',
        type: 'POST',
        data: formData,
        contentType: false,
        processData:false,
        cache: false,
        success: function(data){
            if(data.status === "ok"){
                $(element).html('Aceptar');
                $(element).attr('disabled', false);
                swal({
                    title: "Operación Exitosa",
                    text: "El archivo se ha cargado con éxito.",
                    type: "success"
                }, function(){
                    window.location.href = '/stores';
                });
            }else{
                swal({  title: "Ha ocurrido un error",
                    text: data.message,
                    type: "error"});
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            swal({  title: "Ha ocurrido un error",
                text: "Por favor intenta de nuevo.",
                type: "error"});
            $(element).html('Aceptar');
            $(element).attr('disabled', false);
        }

    });

}


window.charge_products = function (element) {
    $(element).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
    $(element).attr('disabled', true);

    var formData = new FormData($("form#charge_stores_form")[0]);
    formData.append("_token", token);

    $.ajax({
        url: '/stores/charge',
        type: 'POST',
        data: formData,
        contentType: false,
        processData:false,
        cache: false,
        success: function(data){
            if(data.status === "ok"){
                $(element).html('Aceptar');
                $(element).attr('disabled', false);
                swal({
                    title: "Operación Exitosa",
                    text: "El archivo se ha cargado con éxito.",
                    type: "success"
                }, function(){
                    window.location.href = '/stores';
                });
            }else{
                swal({  title: "Ha ocurrido un error",
                    text: data.message,
                    type: "error"});
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            swal({  title: "Ha ocurrido un error",
                text: "Por favor intenta de nuevo.",
                type: "error"});
            $(element).html('Aceptar');
            $(element).attr('disabled', false);
        }

    });

}