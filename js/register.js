const registerButton = document.querySelector("#register");

registerButton.addEventListener('click', function (e) {

    let username = document.getElementById('username').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let rePassword = document.getElementById('confirm_password').value;
    let faculty = document.getElementById('faculty').value;
    let member_type = document.getElementById('member_type').value

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    e.preventDefault();
    if (username === '') {
        e.preventDefault();
        document.getElementById('message_username').innerHTML = 'Please add an username';
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
    if (password === '') {
        e.preventDefault();
        document.getElementById('message_password').innerHTML = 'Please enter a password';
    }
    if (password != '') {
        e.preventDefault();
        if (password.length < 6) {
            e.preventDefault();
            document.getElementById('message_password').innerHTML = 'Please enter a password that contains more than 6 characters';
        }
    } else {
        document.getElementById('message_password').innerHTML = '';
    }

    if (rePassword === '') {
        e.preventDefault();
        document.getElementById('message_confirm').innerHTML = 'Please re-enter the password';
    } else {
        e.preventDefault();
        document.getElementById('message_confirm').innerHTML = '';
    }


    if (password !== rePassword) {
        e.preventDefault();
        document.getElementById('message_password').innerHTML = 'Password not the same';
        document.getElementById('message_confirm').innerHTML = 'Password not the same';
    } else {
        e.preventDefault();
        document.getElementById('message_password').innerHTML = '';
        document.getElementById('message_confirm').innerHTML = '';

    }

    if (username != '' && email != '' && password != '' && rePassword != '' && password === rePassword) {

        $.ajax({
            url: 'register/register.php',
            method: 'POST',
            data: {
                username: username,
                email: email,
                password: password,
                confirm_password: rePassword,
                faculty: faculty,
                member_type: member_type
            },
            success: function (data) {
                dataMessage = JSON.parse(data);
                if (dataMessage.success) {

                    $('form').remove();
                    let messageSuccess = '<p><b>' + dataMessage.success + '</b></p>'
                    $('.panel-body').append(messageSuccess);
                    let link = '/chat/login.php?message=success';
                    let linkLogin = '<a href="' + link + '">Log In</a>'
                    $('.panel-body').append(linkLogin);
                } else {

                    document.getElementById('message_username').innerHTML = dataMessage.fail;
                }

            },
            fail: function (data) {
                console.log('fail');
                alert('Interl Sever Error');

            }

        });
    }


});
