<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <title>file message page</title>
</head>
<body>
    <div style="padding-top:0px;">
        <?= view('navigation_bar')?>
    </div>
    <div class="container" style="">
        <div class="row" style="margin-bottom:30px">
            <div class="col-sm-2 col-2"></div>
            <div class="col-sm-7 col-sm-6">
                <form style="display:block">
                    <div class="form-row">
                        <h5>Message</h5>
                        <textarea class="form-control elementBack" placeholder="type message here..." rows="5" style="height:100%" id="content" name="content"></textarea>
                    </div>
                    <div class="form-row">
                        <h5>Password</h5>
                        <input class="form-control elementBack" placeholder="type password for zip here..." type="password" id="password" name="password">
                    </div>
                    <div>
                        <div class="custom-file col-sm-6 col-md-6" style="color:#181818;height: 100%;" id="dropZone">
                            <input type="file" class="custom-file-input" id="customFile" multiple onchange="displayFileNames(this.files)">
                            <label for="customFile" class="custom-file-button">
                                <i class="fas fa-upload file-icon"></i>
                            </label>
                        </div>
                        <div>
                            <div class="ml-3 col-sm-6 col-md-6 text-truncate" id="fileInfo" name="fileInfo" style="background-color: #282e33; color: #dee4ea;overflow-wrap: break-word;padding-top:0px;min-height:100px"></div>
                        </div>
                    </div>
                    <div style="padding-top:50px">
                        <h5>Expire</h5>
                        <select class="form-select elementBack" aria-label="Default select example">
                            <option value="1">2min</option>
                            <option value="2">15min</option>
                            <option value="2">1h</option>
                            <option value="3">1d</option>
                        </select>
                    </div>
                    <div class="form-row">
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

    <script>
        const dropZone = document.getElementById('dropZone');
        let allFiles = [];

        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', function () {
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropZone.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            allFiles = allFiles.concat(Array.from(files));
            displayFileNames(allFiles);
        });

        function displayFileNames(files) {
            const fileInfo = document.getElementById('fileInfo');

            // Check if any file is selected
            if (files.length > 0) {
                const fileNames = files.map(file => file.name);
                fileInfo.innerHTML = `<strong>Selected Files:</strong> ${fileNames.join(', ')}`;
            } else {
                fileInfo.innerHTML = ''; // No file selected
            }
        }

        // Additional event listener for the file input
        document.getElementById('customFile').addEventListener('change', function () {
            const files = this.files;
            allFiles = allFiles.concat(Array.from(files));
            displayFileNames(allFiles);
            setEqualHeight();
        });

        function setEqualHeight() {
            const customFileHeight = fileInfo.offsetHeight;
            dropZone.style.height = customFileHeight + 'px';
        }
    </script>
</body>
</html>