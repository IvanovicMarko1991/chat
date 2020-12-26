document.getElementById('login').addEventListener('click', (e) => {
    e.preventDefault();


    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    if (username === '') {
        e.preventDefault();
        document.getElementById('message-username').innerHTML = 'Please add your username';
    }
    if (password === '') {
        e.preventDefault();
        document.getElementById('message-password').innerHTML = 'Please add your password';
    }

    if (username != '' && password != '') {
        $.ajax({
            url: 'login.php',
            method: 'POST',
            data: {
                login: 'login',
                username: username,
                password: password
            },
            success: function (data) {

                if (data.message) {
                    e.preventDefault();
                    document.getElementById('message-alert').innerHTML = data.message;
                } else if (data.password) {
                    e.preventDefault();
                    document.getElementById('message-alert').innerHTML = data.password;

                } else {
                    window.location.href = '/chat/index.php';
                }
            },
            fail: function (data) {
                console.log(data);
            }
        })
    }

})
