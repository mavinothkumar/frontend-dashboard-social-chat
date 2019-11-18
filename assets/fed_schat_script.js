jQuery(document).ready(function ($) {
    var body = $('body');
    body.on('click', '.fed_wa_footer_chat_container', function (e) {
        $(this).closest('#fed_wa_container').find('.fed_wa_container').removeClass('fed_hide').addClass('fed_wa_opened');
        $(this).closest('#fed_wa_container').find('.fed_wa_close').removeClass('fed_hide');
        $(this).closest('#fed_wa_container').find('.fed_wa_arrow_box').addClass('fed_hide');
        $(this).addClass('fed_hide');
        e.preventDefault();
    });

    body.on('click', '.fed_wa_close', function (e) {
        $(this).closest('#fed_wa_container').find('.fed_wa_container').addClass('fed_hide').removeClass('fed_wa_opened');
        $(this).closest('#fed_wa_container').find('.fed_wa_footer_chat_container').removeClass('fed_hide');
        $(this).closest('#fed_wa_container').find('.fed_wa_arrow_box').removeClass('fed_hide');
        $(this).addClass('fed_hide');
        e.preventDefault();
    });

    body.on('click', '#fed_whatsapp_add_new_user_button', function (e) {
        var click = $(this);

        $('.preview-area').removeClass('hide');
        $.ajax({
            type: 'POST',
            url: click.data('url'),
            data: {},
            success: function (results) {
                $('.preview-area').addClass('hide');
                click.closest('form').find('.fed_whatsapp_users_list').prepend(results.data.html);

                if ($('.fed_whatsapp_user_list').length) {
                    $('#fed_whatsapp_add_user_form_submit').removeClass('hide');
                }

            }
        });


        e.preventDefault();
    });
    body.on('click', '.fed_whatsapp_delete_user_form', function (e) {
        $(this).closest('.fed_whatsapp_user_list').remove();

        e.preventDefault();
    });


});