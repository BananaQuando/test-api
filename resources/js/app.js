require('./bootstrap');


$(document).ready(function () {



    $('#update_projects').on('submit', function (e) {
        e.preventDefault();

        let action = $(this).attr('action');

        $.ajax({
            url: action,
            type: 'PATCH',
            success: function(res) {
                console.log(res)
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
    });
});