$(document).ready(function() {
    var document = $('input#document').val();
    var chat = $('input#chat').val();
    
    setInterval(function(){
        getMessages();
        $('input#chat').val('0');
        chat = $('input#chat').val();
    }, 5000);
    
    
    function scrollChat(){
        $("#chatBody").animate({ scrollTop: $('#chatBody')[0].scrollHeight}, 900);
    }
    
    setTimeout(function(){
        scrollChat();
    },6000);
    
	$('input#message').focus(function(){
		$('.chatHeading').css('background', '#428BCA');
	});
	
    $('input#message').keypress(function(e){
        if(e.which == 13){
            var message = $('input#message').val();
            if (message === '') {
                return false;
            }

            $.post(base_url + "chat/ajaxAddMessage", {document: document, message: message}, function(data) {
                if (data.status == 'ok') {
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

    function getMessages(){
        $.post(base_url + "chat/ajaxGetMessages", {document: document, chat: chat}, function(data) {
            if (data.status === 'ok') {
                var current = $('#chatBody').html();
                $('#chatBody').html(current + data.content);
            } else {

            }
            if(data.content != ''){
				$('.chatHeading').css('background', '#F94343');
			}
        }, "json");
    }

});