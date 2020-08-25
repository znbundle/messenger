(function ($) {

    var updateMessageList = function() {
        $('#messageBox').hide();
        if(chatId) {
            $.ajax({
                type: "GET",
                url: '/message-list/' + chatId,
                success: function (data, textStatus, jqXHR) {
                    $('#messageList').html(data);
                    $('#messageBox').show();
                    var element = document.getElementById("messageList");
                    element.scrollTop = element.scrollHeight;
                    /*if (data == '' && jqXHR.status == 200) {
                        toastr.success('OK');
                        $('#messages').attr('src', $('#messages').attr('src'));
                        //$("#messages").contentWindow.scrollTo(10000,0);
                        $('#messageField').val('');
                    } else {
                        toastr.error("<h4>Error</h4>" + data);
                    }*/
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    toastr.error('<h4>' + textStatus + ': ' + errorThrown + '</h4>' + jqXHR.responseText);

                }
            });
        }
    };

    $('#messageForm').submit(function (e) {
        //toastr.info('Sending...');
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: "POST",
            data: data,
            //url: '/bot.php?token=<?= $botConfig['token'] ?>',
            success: function (data, textStatus, jqXHR) {
                if (data == '' && jqXHR.status == 200) {
                    //toastr.success('OK');
                    $('#messages').attr('src', $('#messages').attr('src'));
                    //$("#messages").contentWindow.scrollTo(10000,0);
                    $('#messageInput').val('');
                    updateMessageList();
                } else {
                    toastr.error("<h4>Error</h4>" + data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                toastr.error('<h4>' + textStatus + ': ' + errorThrown + '</h4>' + jqXHR.responseText);
            }
        });

    });

    updateMessageList();

})(jQuery);