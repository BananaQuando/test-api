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
                    $('#update_message_target').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                          Projects was successfully updated
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                    `);
                }
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
    });
});