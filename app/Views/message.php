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
        <?= view('navigation_bar')?>
    </div>
    <div class="container" style="">
        <div class="row">
            <div class="col-sm-2 col-2"></div>
            <div class="col-sm-7 col-sm-6">
                <form style="display:block" id="msgForm">
                    <div>
                        <h5>Message</h5>
                        <textarea class="form-control elementBack" placeholder="type message here..." rows="5" style="height:100%" id="message" name="message"></textarea>
                    </div>
                    <div>
                        <h5>Expire</h5>
                        <select class="form-select elementBack" aria-label="Default select example" id="expire" name="expire">
                            <option value="1">2min</option>
                            <option value="2">15min</option>
                            <option value="3">1h</option>
                            <option value="4">1d</option>
                        </select>
                    </div>
                    <div>
                        <h5>Email</h5>
                        <textarea class="form-control elementBack" placeholder="type emails here separate them with ;" rows="2" style="height:100%" id="email" name="email"></textarea>
                    </div>
                    <div class="form-row" style="padding-top:30px">
                        <div class="col-sm-6" style="padding-left:0px">
                            <button class="btn elementBack" id="submitButton">
                                Submit
                            </button>
                        </div>
                        <div class="col-sm-6" style="padding-right:0px">
                            <input class="form-control elementBack" id="resp" style="float:right" type="text" placeholder="Link will show here…" readonly>
                        </div>
                    </div>
                </form>
                <script>
                    $(document).ready(function () {
                        $('#submitButton').click(function (e) {
                            e.preventDefault(); // Prevent the default form submission
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
        </div>
    </div>
</body>
</html>