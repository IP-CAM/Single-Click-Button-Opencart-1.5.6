$(document).ready(function () {

    $('#content').on('click' ,'.product-list .singleclick, .product-grid .singleclick', function () {
        /*
        var product = $(this).parents('li');
        */
        var product = $(this).parents('.product-list > div, .product-grid > div');
        $('#product_name').val(product.find('.name a').text());
        $('#product_price').val(product.find('.price').text());
        $('#singleclick_title').text(product.find('.name a').text());
        $.colorbox({
            href: "#singleclick_form",
            inline: true,
            width: "650px",
            height: "390px",
            title: " "
        });
    });

    $('.singleclick_button').on('click', function () {
        var product_name = $('#product_name').val();
        var product_price = $('#product_price').val();
        var customer_name = $('#customer_name').val();
        var customer_phone = $('#customer_phone').val();
        var customer_message = $('#customer_message').val();
        $('#result').html('Обрабатываем введенные данные..');
        $.post('/index.php?route=module/singleclick', {
            'product_name': product_name,
            'product_price': product_price,
            'customer_name': customer_name,
            'customer_phone': customer_phone,
            'customer_message': customer_message
        }, function (data) {
            var data = $.parseJSON(data);
            if ('error' in data) {
                $('#singleclick_result').html('<span class="singleclick_error">' + data.error + '</span>');
            } else {
                $('#singleclick_result').html('<span class="singleclick_success">Ваш заказ успешно оформлен!</span><br /><span>Мы перезвоним вам в течение дня. <a onclick="$(window).colorbox.close();">Закрыть</a> это окно?</span>');
            }
        });
    });

});