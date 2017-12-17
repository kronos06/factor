/*
* Script principal para CyD
*/

$(document).ready(function () {
    // Ajax Form Logic
    $("body").on('click', '.submit-button', function () {
        var form = $(this).closest("form");

        // Bloqueamos
        if (!form.validate()) return false;

        var block = $('<div class="block-loading" />');
        form.prepend(block);
    })
    $("body").on('click', '.submit-ajax-button', function () {
        var form = $(this).closest("form");
        var buttons = $("button", form);
        var button = $(this);
        var url = form.attr('action');

        var tipo = 1; // 1 Formulario , 2 Boton Eliminar

        if (button.data('confirm') != undefined)
        {
            if (button.data('confirm') == '') {
                if (!confirm('¿Esta seguro de realizar esta acción?')) return false;
            } else {
                if (!confirm(button.data('confirm'))) return false;
            }
        }

        if (button.hasClass('del')) {
            if (!confirm('Esta seguro de eliminar este item?', 'Confirmar acci�n')) {
                return false;
            } else {
                url = button.val();
                tipo = 2;
            }
        } else if (button.hasClass('confirm')) {
            if (!confirm('Esta seguro de realizar esta acci�n?', 'Confirmar acci�n')) {
                return false;
            } else {
                if (!form.validate()) {
                    return false;
                }
            }
        } else {
            if (!form.validate()) {
                return false;
            }
        }

        // Bloqueamos
        var block = $('<div class="block-loading" />');
        form.prepend(block);

        $(".alert", form).remove();

        form.ajaxSubmit({
            dataType: 'JSON',
            type: 'POST',
            url: url,
            success: function (r) {

                if(r.response == 'login')
                {
                    alert('Su sesión ha expirado, lo vamos a llevar a la auntentificación.')
                    window.location.href = base_url('');
                    return;
                }

                block.remove();
                if (r.response) {
                    if (!form.hasClass('upd') && !button.hasClass('del')) {
                        form.reset();
                    }
                }

                // Mostrar mensaje
                if (r.message != undefined) {
                    if (r.message.length > 0) {
                        var css = "";
                        if (r.response) css = "alert alert-success";
                        else css = "alert-danger";

                        var message = '<div class="alert ' + css + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + r.message + '</div>';
                        form.prepend(message);
                        $('html,body').animate({scrollTop: form.find('.alert').offset().top - 60},'fast');
                    }
                }

                // Ejecutar funciones
                if (r.function != undefined) {
                    setTimeout(r.function, 0);
                }
                // Redireccionar
                if (r.href != undefined) {
                    if (r.href == 'self') window.location.reload(true);
                    else window.location.href = base_url(r.href);
                }
            }
        });

        return false;
    })
})