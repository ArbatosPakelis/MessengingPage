<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Registration page</title>
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
        <form id="signupForm">
            <h2>Register</h2>
            <hr>
            <div class="form-row">
                <label for="username">Username</label>
                <input class="form-control elementBack" placeholder="type unique name here.." type="text" id="username" name="username">
                <span id="usernameSpan" class="spnHide"></span>
            </div>
            <div class="form-row">
                <label for="password">Password</label>
                <input class="form-control elementBack" placeholder="type password here..." type="password" id="password" name="password">
                <span id="passwordSpan" class="spnHide"></span>
            </div>
            <div class="form-row">
                <label for="cpassword">Confirm Password</label>
                <input class="form-control elementBack" placeholder="type password again here..." type="password" id="cpassword" name="cpassword">
                <span id="cpasswordSpan" class="spnHide"></span>
            </div>
            <div class="form-row">
                <label for="email">Email</label>
                <input class="form-control elementBack" placeholder="type your email here..." type="text" id="email" name="email">
                <span id="emailSpan" class="spnHide"></span>
            </div>
            <hr>
            <div style="padding-top:0px;">
                <button class="btn elementBack" id="submitButton" style="width:100%;">
                    Submit
                </button>
                <p style="float:left;padding-top:10px">Already have an account?<br><a class="move" href="login">Login</a></p>
            </div>
        </form>
        <script>
            $(document).ready(function () 
            {
                $('#submitButton').click(function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    var formData = new FormData($('#signupForm')[0]);
                    $.ajax({
                        url: '<?php echo base_url('public/signup')?>',
                        type: 'post',
                        data: formData,
                        success: function (response) {
                            console.log("success");
                            window.location.href = '<?php echo base_url('public/login')?>';
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
                $('#username, #password, #cpassword, #email').on('input', function() 
                {
                    isValidForm();
                });
            });
            function isValidForm() 
            {
                let fieldValue1 = $('#username').val().trim();
                let fieldValue2 = $('#password').val().trim();
                let fieldValue3 = $('#cpassword').val().trim();
                let fieldValue4 = $('#email').val();
                
                let empty = fieldValue1 === "" || fieldValue2 === "" || fieldValue3 === "" || fieldValue4 === "";
                const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=?.\\|'"><,?{}~`])[A-Za-z\d!@#$%^&*()_\-+=?.\\|'"><,?{}~`]{8,}$/;
                let passwordValid = passwordRegex.test(fieldValue2);
                let notMatchingPassword = fieldValue2 != fieldValue3;
                const emailRegex =/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let emailValid = emailRegex.test(fieldValue4);

                // is everything valid
                if (empty || !passwordValid|| notMatchingPassword || !emailValid) {
                    // show message that password is not valid
                    if(!passwordValid && fieldValue2 != "")
                    {
                        $('#passwordSpan').removeClass('spnHide').addClass('spnErr');
                        $('#passwordSpan').html(
                            `Password is invalid<br>
                            Password needs to contain: <br>
                            - At least 8 characters<br>
                            - At least one letter<br>
                            - At least one digit
                            - At least one special character
                            (?=.*[!@#$%^&*()_\-+=?.\\|'"><,?{}~])
                        `);
                    }
                    else
                    {
                        $('#passwordSpan').removeClass('spnErr').addClass('spnHide');
                        $('#passwordSpan').text("");
                    }                    
                    
                    // show message that passwords don't match
                    if(notMatchingPassword  && fieldValue2 != "" && fieldValue3 != "")
                    {
                        $('#cpasswordSpan').removeClass('spnHide').addClass('spnErr');
                        $('#cpasswordSpan').text("Passwords do not match");
                    }
                    else
                    {
                        $('#cpasswordSpan').removeClass('spnErr').addClass('spnHide');
                        $('#cpasswordSpan').text("");
                    }

                    // show message that email is invalid
                    if(!emailValid && fieldValue4 != "")
                    {
                        $('#emailSpan').removeClass('spnHide').addClass('spnErr');
                        $('#emailSpan').html(
                            `Email is invalid <br>
                            email needs to contain: <br>
                            - one or more characters that are not whitespace before "@"<br>
                            - one symbol "@"<br>
                            - one or more characters that are not whitespace, "@" or a dot after symbol "@" but before a dot<br>
                            - a dot;<br>
                            - one or more characters that are not whitespace or "@" after a dot<br>
                            Example: example@example.com
                        `);
                    }
                    else
                    {
                        $('#emailSpan').removeClass('spnErr').addClass('spnHide');
                        $('#emailSpan').text("");
                    }
                    
                    $('#submitButton').prop('disabled', true);
                } else {
                    // everything is valid and you can register
                    $('#passwordSpan').removeClass('spnErr').addClass('spnHide');
                    $('#passwordSpan').text("");
                    $('#cpasswordSpan').removeClass('spnErr').addClass('spnHide');
                    $('#cpasswordSpan').text("");
                    $('#emailSpan').removeClass('spnErr').addClass('spnHide');
                    $('#emailSpan').text("");
                    $('#submitButton').prop('disabled', false);
                }
            }
        </script>
    </div>
</body>
</html>