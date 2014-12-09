$(document).ready(function () {
    isNotify();

    setInterval(function () {
        isNotify();
    }, 300000);

    $('#documents_button').click(function () {
        $('#document_dropdown li:not(:nth-last-child(2)):not(:last)').remove();
        getNotifications();
    });

    function isNotify() {
        $.post(base_url + "notification/ajaxCountNotifications", function (data) {
            if (data.status === 'ok') {
                var count = data.num;
                if (count !== '0') {
                    $('#documents_button').html('Documents <span class="badge">' + count + '</span>');
                }
            } else {

            }
        }, "json");
    }
    function getNotifications() {
        $('#document_dropdown').prepend('<li><a href="#">Loading notifications...</a></li>');
        $.post(base_url + "notification/ajaxGetNotifications", function (data) {
            if (data.status === 'ok') {
                $('#document_dropdown li:first').remove();
                var content = data.content;
                $('#document_dropdown').prepend(content);
                $('#documents_button span').remove();
            } else {

            }
        }, "json");
    }

    $('#document_dropdown .notify').click(function (e) {
        var id = this.id;
        alert(id);
        e.preventDefault();
    });
});