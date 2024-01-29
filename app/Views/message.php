<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
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
                <form style="display:block">
                    <div>
                        <h5>Message</h5>
                        <textarea class="form-control elementBack" placeholder="type message here..." rows="5" style="height:100%" id="content" name="content"></textarea>
                    </div>
                    <div>
                        <h5>Expire</h5>
                        <select class="form-select elementBack" aria-label="Default select example">
                            <option value="1">2min</option>
                            <option value="2">15min</option>
                            <option value="2">1h</option>
                            <option value="3">1d</option>
                        </select>
                    </div>
                    <div>
                        <h5>Email</h5>
                        <textarea class="form-control elementBack" placeholder="type emails here separate them with ;" rows="2" style="height:100%" id="email" name="email"></textarea>
                    </div>
                    <div class="form-row" style="padding-top:30px">
                        <div class="col-sm-6" style="padding-left:0px">
                            <button class="btn elementBack">
                                Submit
                            </button>
                        </div>
                        <div class="col-sm-6" style="padding-right:0px">
                            <input class="form-control elementBack" style="float:right" type="text" placeholder="Link will show here…" readonly>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>