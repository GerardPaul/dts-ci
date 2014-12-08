$(document).ready(function () {
    isNotify();
    
    setInterval(function () {
        isNotify();
    }, 300000);

    $('#documents_button').click(function () {

    });

    function isNotify() {
        $.post(base_url + "notification/ajaxCountNotifications", function (data) {
            if (data.status === 'ok') {
                var count = data.num;
                if(count !== '0'){
                    $('#documents_button').html('Documents <span class="badge">'+count+'</span>');
                }
            } else {

            }
        }, "json");
    }
    function getNotifications() {
        var previousLength = $('#numMessages').val();
        $.post(base_url + "notification/ajaxGetNotifications", function (data) {
            if (data.status === 'ok') {
                var current = $('#chatBody').html();
                $('#chatBody').html(current + data.content);
            } else {

            }
            if (data.content != '') {
                $('.chatHeading').css('background', '#F94343');
            }
            var currentLength = $('#chatBody > div.col-xs-12').length;
            if (currentLength > previousLength) {
                scrollChat();
            }
            $('#numMessages').val(currentLength);
        }, "json");
    }
});