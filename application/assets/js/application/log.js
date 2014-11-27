$(document).ready(function () {
    $('#logsTable').dataTable({
        "order": [[2, "desc"]],
        "aoColumns": [
            null,
            null,
            null
        ],
        "iDisplayLength": 25
    });
});