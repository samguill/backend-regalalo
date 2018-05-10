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

    var data = {
        labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        datasets: [
            {
                label: "Productos",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [12, 19, 8, 28, 14, 56, 24, 30, 10, 2, 25, 11]
            },
            {
                label: "Servicios",
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(151,187,205,1)",
                data: [2, 29, 25, 12, 9, 24, 57, 10, 50, 23, 5, 16]
            }
        ]
    };

    if($('#lineChartDemo').length) {
        var ctx = $("#lineChartDemo").get(0).getContext('2d');
        var myChart = new Chart(ctx, {
            type: "line",
            data: data
        });
    }


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
                $(element).html('Aceptar');
                $(element).attr('disabled', false);
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

    var formData = new FormData($("form#charge_products_form")[0]);
    formData.append("_token", token);

    $.ajax({
        url: '/products/charge',
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
                    window.location.href = '/products';
                });
            }else{
                swal({  title: "Ha ocurrido un error",
                    text: data.message,
                    type: "error"});
                $(element).html('Aceptar');
                $(element).attr('disabled', false);
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

window.charge_services = function (element) {
    $(element).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
    $(element).attr('disabled', true);

    var formData = new FormData($("form#charge_services_form")[0]);
    formData.append("_token", token);

    $.ajax({
        url: '/services/charge',
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
                    window.location.href = '/services';
                });
            }else{
                swal({  title: "Ha ocurrido un error",
                    text: data.message,
                    type: "error"});
                $(element).html('Aceptar');
                $(element).attr('disabled', false);
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
};

window.charge_products_admin = function (element) {
    $(element).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
    $(element).attr('disabled', true);

    var formData = new FormData($("form#charge_products_form_admin")[0]);
    formData.append("_token", token);
    formData.append('store_id', $("form#charge_products_form_admin select#store_id").val());

    $.ajax({
        url: '/product/charge',
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
                    window.location.href = '/product';
                });
            }else{
                swal({  title: "Ha ocurrido un error",
                    text: data.message,
                    type: "error"});
                $(element).html('Aceptar');
                $(element).attr('disabled', false);
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