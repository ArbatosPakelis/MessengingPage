<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                        <textarea class="form-control elementBack" placeholder="<?= $data[0]['Message'] ?>" rows="5" style="height:100%" id="content" name="content"  readonly></textarea>
                    </div>
                    <?php if(isset($data[0]['File'])) :?>
                        <div class="elementBack" style="margin-top:20px;padding-top:0px;border-radius:5px;width:33px;">
                            <a href="<?= base_url().'public/download?path='.$data[0]['File']; ?>" class="btn btn-sm"><span class="glyphicon glyphicon-download-alt"></a>
                        </div>
                    <?php endif; ?>
                    <div class="form-row" style="padding-top:30px">
                        <p>Clock</p>
                        <div id="timer"></div>
                    </div>
                </form>
                <script>
                    const expireTime = new Date('<?= $data[0]['Expire'] ?>').getTime();
                    function startTimer(targetTime) {
                        const countdown = setInterval(function () {
                        const now = new Date().getTime();
                        const timeDifference = targetTime - now;

                        if (timeDifference <= 0) {
                            clearInterval(countdown);
                            document.getElementById('timer').textContent = 'Expired';
                        } else {
                            displayTime(timeDifference);
                        }
                        }, 1000);
                    }

                    function displayTime(timeDifference) {
                        const timerElement = document.getElementById('timer');
                        const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                        timerElement.textContent = `${hours}:${minutes}:${seconds.toString().padStart(2, '0')}`;
                    }

                    startTimer(expireTime);
                </script>
            </div>
        </div>
    </div>
</body>
</html>