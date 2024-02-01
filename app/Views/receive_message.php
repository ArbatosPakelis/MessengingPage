<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>message receival page</title>
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
                        <textarea class="form-control elementBack" placeholder="type message here..." rows="5" style="height:100%" id="content" name="content"  readonly></textarea>
                    </div>
                    
                    <?php if(isset($data)) :?>
                        <div class="elementBack" style="margin-top:20px;padding-top:0px;border-radius:5px;width:33px;">
                            <a href="<?= base_url().'public/download?path='.$data['file']['path']; ?>" class="btn btn-sm"><span class="glyphicon glyphicon-download-alt"></a>
                        </div>
                    <?php endif; ?>
                    <div class="form-row" style="padding-top:30px">
                        <p>Clock</p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>