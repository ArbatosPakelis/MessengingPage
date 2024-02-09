<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Home page</title>
</head>
<body>
    <div style="padding-top:0px;">
        <?= view('navigation_bar')?>
    </div>
    <div class="centerDiv align-content-center" style="padding-top:100px; width:700px">
        <p class="centerText">
            Guide
        </p>
        <p>
            Here is a guide to using this page. You can send two types of messages
            of this page. You can send just text messages and you can send messages
            with files attached to them. Please choose the apropriate message type
            you wish to use on the navigation bar.
        </p>
        <br>
        <p>
            To use regular text messages click on the navigation bar to open "Text message"
            page and fill out the form. Type the message you want to send, select how long
            do you wish for message to be available for, type email adresses if you wish
            the link to be sent to those emails for your convenience. Once you filled out
            everything click button "Submit", the message will link will be sent to the given
            emails if you added any and the link will be generated in the page aswell for your
            own use or to test it out.
        </p>
        <br>
        <p>
            To use file messages click on the navigation bar to open "File message" page.
            This page is very similar to the previous one but in additions there is a password
            window where you can put a password for additional security. Password will be
            added inside the generated zip file and will be required if a other user wants to
            extract the files from the zip. You can also choose files you wish to use. You can
            click the button to choose a single file or drag and drop multiple files on the
            button for them to be added. Similarly to other page, once you fill out the form
            and submit it a link will be generated on the page and if you put in any emails
            the link will the sent out to those emails.
        </p>
    </div>
</body>
</html>