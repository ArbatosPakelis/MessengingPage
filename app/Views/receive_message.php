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
        <?php
            $ses_data = [
                'id' => $session['id'],
                'name' => $session['name'],
                'isLoggedIn' => $session['isLoggedIn']
            ];
            echo view('navigation_bar', $ses_data)
        ?>
    </div>
    <div class="center">
        <script>
            console.log(<?=json_encode($data)?>);
            console.log(<?=json_encode($session)?>);
        </script>
        <?php  
        if(is_array($data) && isset($data)) : ?>
            <?php 
            $currentTime = time();
            $expiration = strtotime($data[0]['Expire']);
            if($expiration > $currentTime) : ?>
                <div style="display:block;width: 400px !important;background-color: #212124;padding: 20px;">
                    <h2>You received a message</h2>
                    <hr>
                    <div>
                        <label for="content">Message</label>
                        <textarea class="form-control elementBack"rows="5" style="height:100%" id="content" name="content"  readonly><?= $data[0]['Message'] ?></textarea>
                    </div>
                    <?php if(isset($data[0]['File']) && $data[0]['File'] != null && $data[0]['File'] != "") :?>
                        <div class="elementBack" style="margin-top:20px;padding-top:0px;border-radius:5px;width:33px;">
                            <a href="<?= base_url() . 'public/download/' . $data[0]['File'] ?>" class="btn btn-sm"><span class="glyphicon glyphicon-download-alt"></a>
                        </div>
                    <?php endif; ?>
                    <div class="form-row elementBack" style="margin-top:30px;padding:5px;width:100px;border-radius:5px; text-align: center;margin-left:auto;margin-right:auto">
                        <p>Expired in:</p>
                        <div style="padding-top:0px" id="timer"></div>
                    </div>
                </div>
            <?php  else : ?>
                <div style="color: #dee4ea;">
                    <h1>Expired</h1>
                </div>
                <script>
                    // Redirect to base_url() . '/cleanup' when the page loads
                    window.onload = function() {
                        window.location.href = '<?php echo base_url('public/home')?>';
                    };
                </script>
            <?php  endif; ?>
        <?php  else : ?>
            <?php  if($data == "500"): ?>
            <div style="color: #dee4ea;">
                <h1>Message data got corrupted </h1>
            </div>
            <?php  else : ?>
            <div style="color: #dee4ea;">
                <h1>Message has been deleted</h1>
            </div>
            <?php  endif; ?>
        <?php  endif; ?>
        <?php if(is_array($data) && isset($data)) : ?>
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

                    timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }

                startTimer(expireTime);
            </script>
        <?php  endif; ?>
    </div>
</body>
</html>