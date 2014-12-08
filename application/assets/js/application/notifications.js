$(document).ready(function () {
    isNotify();
    
    setInterval(function () {
        isNotify();
    }, 300000);

    $('#documents_button').click(function () {
        $('#document_dropdown li:not(:nth-last-child(2)):not(:last)').remove();
        $('#documents_button span').remove();
        getNotifications();
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
        $.post(base_url + "notification/ajaxGetNotifications", function (data) {
            if (data.status === 'ok') {
                var content = data.content;
                $('#document_dropdown').prepend(content);
            } else {

            }
        }, "json");
    }
});