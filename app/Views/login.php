<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Home page</title>
</head>
<body>
    <div style="padding-top:0px;">
        <?php
            $ses_data = [
                'id' => -1,
                'name' => '',
                'isLoggedIn' => false
            ];
            echo view('navigation_bar', $ses_data)
        ?>
    </div>
    <div class="center" style="padding-top:100px">
        <form id="loginForm">
            <h2>Login</h2>
            <hr>
            <div class="form-row hover-text">
                <label for="username">Username</label>
                <input class="form-control elementBack" placeholder="type username here.." type="text" id="username" name="username">
                <span id="usernameSpan"></span>
            </div>
            <div class="form-row hover-text">
                <label for="password">Password</label>
                <input class="form-control elementBack" placeholder="type password here..." type="password" id="password" name="password">
                <span id="passwordSpan"></span>
            </div>
            <hr>
            <div style="padding-top:0px;">
                <button class="btn elementBack" id="submitButton" style="width:100%;">
                    Log in
                </button>
                <p style="float:left;padding-top:10px">Don't have an account?<br><a class="move" href="signup">Register</a></p>
            </div>
        </form>
        <script>
            $(document).ready(function () 
            {
                $('#submitButton').click(function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    var formData = new FormData($('#loginForm')[0]);
                    $.ajax({
                        url: '<?php echo base_url('public/login')?>',
                        type: 'post',
                        data: formData,
                        success: function (response) {
                            console.log(response);
                            window.location.href = '<?php echo base_url('public/home')?>';
                        },
                        error: function (response) {
                            alert('Error:'+ response.responseJSON.error);
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                });
                $('#submitButton').prop('disabled', true);
                $('#username, #password').on('input', function() {
                    let fieldValue1 = $('#username').val().trim();
                    let fieldValue2 = $('#password').val().trim();
                    let empty = fieldValue1 === "" || fieldValue2 === "";
                    if(empty)
                    {                
                        $('#submitButton').prop('disabled', true);
                    } 
                    else 
                    {
                        $('#submitButton').prop('disabled', false);
                    }
                });
            });
        </script>
    </div>
</body>
</html>