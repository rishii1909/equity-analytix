$(document).ready(function () {
    $('#modalLoginForm').submit(function (event) {
        event.preventDefault();

        const fields = {};
        $('#modalLoginForm').find(':input').each(function () {
            fields[this.name] = $(this).val();
        });

        const data = {
            'action': 'eq_ajax_login',
            'fields': fields,
        };

        $.ajax({
            url: obj_login.ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                if (result.status === 'success') {
                    location.reload();
                }
                if (result.status === 'error') {
                    let errorText = '';
                    switch (result.error) {
                        case 'invalid_email':
                            errorText = 'Invalid Email.';
                            break;
                        case 'incorrect_password':
                            errorText = 'Incorrect Password';
                    }
                    let div = $('<div class="text-danger"></div>').text(errorText);
                    $(".modal-login__col--input").prepend(div);
                }
            }
        })
    });
});