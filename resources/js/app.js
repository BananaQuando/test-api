require('./bootstrap');


$(document).ready(function () {



    $('#update_projects').on('submit', function (e) {
        e.preventDefault();

        let action = $(this).attr('action');

        $.ajax({
            url: action,
            type: 'PATCH',
            success: function(result) {
                if (typeof result === 'object'){
                    let list = '';
                    result.forEach((el) => {
                        list += `<li class="list-group-item"><a href="/${el.project_name}">${el.project_name}</a></li>`
                    });
                    $('#current-projects').html(list);
                }
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
    });
});