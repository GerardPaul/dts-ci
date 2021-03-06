$(document).ready(function() {
    var document = $('input#document').val();
    var chat = $('input#chat').val();
    var track = $('input#track').val();

    setInterval(function() {
        getMessages();
        $('input#chat').val('0');
        chat = $('input#chat').val();
		$('#loadingConversation').hide();
    }, 5000);

    setTimeout(function() {
        scrollChat();
    }, 6000);

    $('.chatBox').click(function() {
        $('.chatHeading').css('background', '#428BCA');
        $.post(base_url + "chat/ajaxSeenMessage", {track: track}, function(data){
            if(data.status === 'ok'){
                //alert('success');
            }
        });
    });
    
    $('input#message').focus(function() {
        $('.chatHeading').css('background', '#428BCA');
    });

    $('input#message').keypress(function(e) {
        if (e.which === 13) {
            scrollChat();
            var message = $('input#message').val();
            if (message === '') {
                return false;
            }

            $.post(base_url + "chat/ajaxAddMessage", {document: document, message: message}, function(data) {
                if (data.status === 'ok') {
                    var current = $('#chatBody').html();
                    $('#chatBody').html(current + data.content);
                } else {

                }
            }, "json");

            $('input#message').val('');
            scrollChat();

            return false;
        }
    });

    function getMessages() {
        var previousLength = $('#numMessages').val();
        $.post(base_url + "chat/ajaxGetMessages", {document: document, chat: chat}, function (data) {
            if (data.status === 'ok') {
                var current = $('#chatBody').html();
                $('#chatBody').html(current + data.content);
            } else {

            }
            if (data.content !== '') {
                $('.chatHeading').css('background', '#F94343');
            }
            var currentLength = $('#chatBody > div.col-xs-12').length;
            if (currentLength > previousLength) {
                scrollChat();
            }
            $('#numMessages').val(currentLength);
        }, "json");
    }

    function scrollChat() {
        $(".chatBody").animate({scrollTop: $('#chatBody')[0].scrollHeight}, 900);
    }
});