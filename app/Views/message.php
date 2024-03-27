<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>message page</title>
</head>
<body>
    <div style="padding-top:0px;">
        <?php
            $ses_data = [
                'id' => $id,
                'name' => $name,
                'isLoggedIn' => $isLoggedIn
            ];
            echo view('navigation_bar', $ses_data)
        ?>
    </div>
    <div class="center">
        <form id="msgForm">
            <h2>Message Form</h2>
            <hr>
            <div>
                <label for="message">Message*</label>
                <textarea class="form-control elementBack" placeholder="type message here..." rows="5" style="height:100%" id="message" name="message"></textarea>
            </div>
            <div class="hover-text">
                <label for="expire">Expire in*</label>
                <select class="form-select elementBack" aria-label="Default select example" id="expire" name="expire">
                    <option value="1">2min</option>
                    <option value="2">15min</option>
                    <option value="3">1h</option>
                    <option value="4">1d</option>
                </select>
                <span class="tooltip-text" id="bottom">Choose a time window during which your message will be accessible.</span>
            </div>
            <div class="hover-text">
                <label for="email">Email</label>
                <textarea class="form-control elementBack" placeholder="type emails here separate them with ;" rows="3" style="height:100%" id="email" name="email"></textarea>
                <span class="tooltip-text" id="bottom">Add one or multiple email addresses, and the generated link will be automatically sent to the provided emails.</span>
            </div>
            <div style="display:block">
                <hr>
                <div style="padding-top:0px;">
                    <button class="btn elementBack" id="submitButton" style="width:100%;">
                        Submit
                    </button>
                </div>
                <hr>
                <div style="padding-top:0px;flex-direction: row;justify-content: center;">
                    <input class="form-control elementBack" id="resp" type="text" placeholder="Link will show here…" readonly>
                </div>
            </div>
        </form>
        <script>
            // Get the input element
            var inputField = document.getElementById("resp");

            // Add click event listener to the input field
            inputField.addEventListener("click", function() {
                // Select the text inside the input field if it exists
                if (inputField.value) {
                    inputField.select();
                }
            });
            $(document).ready(function () {
                $('#submitButton').click(function (e) {
                    e.preventDefault(); // Prevent the default form submission
                    
                    // Perform form validation
                    var fieldValue = $('#message').val().trim();
                    if (fieldValue === "") {
                        // Display an error message or perform other actions for incomplete form
                        alert("Please fill in all required fields.");
                        return; // Exit the function to prevent further execution
                    }

                    var formData = new FormData($('#msgForm')[0]);
                    $.ajax({
                        url: '<?php echo base_url('public/submitM')?>',
                        type: 'post',
                        data: formData,
                        success: function (response) {
                            $('#resp').replaceWith('<input class="form-control elementBack" id="resp" style="float:right" type="text" value="<?php echo base_url() ?>public/'+ response.link +'" readonly>');
                        },
                        error: function (xhr, status, error) {
                            // Ajax request encountered an error
                            console.log(xhr);
                            console.log(status);
                            console.error('Error:', error);
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                });
            });
        </script>
    </div>
</body>
</html>