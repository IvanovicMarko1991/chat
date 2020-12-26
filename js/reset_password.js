document.getElementById('reset').addEventListener('click', (e) => {

    let email = document.getElementById('email').value;

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    if (email === '') {
        e.preventDefault();
        document.getElementById('message_email').innerHTML = 'Please add an email';
    }

    if (email != '') {
        e.preventDefault();
        if (validateEmail(email) === false) {
            e.preventDefault();
            document.getElementById('message_email').innerHTML = 'Invalid email';
        }
    }


    $.ajax({
        url: 'register/forgotPassword.php',
        method: 'POST',

        data: {
            email: email
        },
        success: function (response) {
            data = JSON.parse(response);

            console.log(data);
            console.log(data.msg);
            if (data.status === 1) {
                let form = $('form');
                form.remove();
                let pannel = $('.panel-body');
                let message = '<p>' + data.msg + '</p>';
                pannel.append(message);

            } else {
                let form = $('form');
                form.remove();
                let pannel = $('.panel.body');
                pannel.append('failed');
                console.log('failed');
            }
        },
        fail: function (response) {

            console.log(response);
        }

    });

})
