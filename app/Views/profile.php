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
                'id' => $session['id'],
                'name' => $session['name'],
                'isLoggedIn' => $session['isLoggedIn']
            ];
            echo view('navigation_bar', $ses_data);
        ?>
    </div>
    <div class="centerDiv align-content-center" style="padding-top:100px; width:700px">
        <?php if (count($data) < 1) :?>
            <div class="elementBack" style="text-align: center;padding-bottom: 10px;">
                <h1>History</h1>
                <hr>
                <h3 style="padding:10px">
                    You have no messages made...
                </h3>
            </div>
        <?php else: ?>
            <div style="text-align: center;padding-bottom: 10px;background-color:#222;">
                <h1>History</h1>
                <hr>
                <table style="padding:10px">
                    <thead>
                        <tr style="background-color:#BCD6F00A">
                            <th>Nr.</th>
                            <th>Creation date</th>
                            <th style="width:150px">Expire</th>
                            <th>Password</th>
                            <th>File</th>
                            <th>Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $counter = 1;
                            foreach($data as $row): 
                                // Calculate time left before expiration
                                $expire = strtotime($row['Expire']);
                                $now = time();
                                $timeLeft = $expire - $now;
                                
                                // Calculate days left
                                $daysLeft = floor($timeLeft / (60 * 60 * 24));
                                
                                // Calculate remaining time
                                $remainingTime = ($timeLeft > 0) ? gmdate("H:i:s", $timeLeft) : "Expired";
                                
                                // Display password and file status
                                $passwordStatus = (!empty($row['Password'])) ? '✔' : '❌';
                                $fileStatus = (!empty($row['File'])) ? '✔' : '❌';

                                // Generate encrypted ID
                                $link = base_url() . "public/receiveMessage?data=" . $row['encriptedId'];
                        ?>
                            <tr style="background-color: <?= ($counter % 2 == 0) ? '#BCD6F00A' : 'transparent' ?>;">
                                <td><?= $counter ?></td>
                                <td><?= $row['CreatedAt'] ?></td>
                                <td style="width:150px;white-space: nowrap;" >
                                    <?php if ($timeLeft > 0): ?>
                                        <a href="<?= $link ?>"><span id="timer<?= $counter ?>"><?= $daysLeft . ' days, ' . $remainingTime ?></span></a>
                                    <?php else: ?>
                                        <?= $remainingTime ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $passwordStatus ?></td>
                                <td><?= $fileStatus ?></td>
                                <td><?= $row['Views'] ?></td>
                            </tr>
                        <?php 
                            $counter++;
                            endforeach; 
                        ?>
                    </tbody>
                </table>
                <script>
                    <?php foreach($data as $index => $row): ?>
                        // Set the timer for each row
                        var expire<?= $index ?> = new Date('<?= $row['Expire'] ?>').getTime();

                        var x<?= $index ?> = setInterval(function() {
                            var now = new Date().getTime();
                            var timeLeft = expire<?= $index ?> - now;

                            var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                            var timerString = days + " days, " + ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);

                            document.getElementById("timer<?= $index + 1 ?>").innerHTML = timerString;

                            if (timeLeft < 0) {
                                clearInterval(x<?= $index ?>);
                                document.getElementById("timer<?= $index + 1 ?>").innerHTML = "Expired";
                            }
                        }, 1000);
                    <?php endforeach; ?>
                </script>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>