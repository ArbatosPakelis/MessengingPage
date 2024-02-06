<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                <form style="display:block" id="fileForm" enctype="multipart/form-data" method="POST">
                    <div class="form-row">
                        <h5>Message</h5>
                        <textarea class="form-control elementBack" placeholder="type message here..." rows="5" style="height:100%" id="message" name="message"></textarea>
                    </div>
                    <div class="form-row">
                        <h5>Password</h5>
                        <input class="form-control elementBack" placeholder="type password for zip here..." type="password" id="password" name="password">
                    </div>
                    <div>
                        <div class="custom-file col-sm-6 col-md-6" style="color:#181818;height: 100%;" id="dropZone">
                            <input type="file" class="custom-file-input" id="customFiles" name="customFiles[]" multiple="multiple" onchange="displayFileNames(this.files)">
                        </div>
                        <div>
                            <div class="ml-3 col-sm-6 col-md-6 text-truncate" id="fileInfo" name="fileInfo" style="background-color: #282e33; color: #dee4ea;overflow-wrap: break-word;padding-top:0px;min-height:100px"></div>
                        </div>
                    </div>
                    <div style="padding-top:50px">
                        <h5>Expire</h5>
                        <select class="form-select elementBack" aria-label="Default select example" id="expire" name="expire">
                            <option value="1">2min</option>
                            <option value="2">15min</option>
                            <option value="2">1h</option>
                            <option value="3">1d</option>
                        </select>
                    </div>
                    <div class="form-row" id="email" name="email">
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
                            <input class="form-control elementBack"  id="resp" style="float:right" type="text" placeholder="Link will show here…" readonly>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#submitButton').click(function (e) {
                e.preventDefault(); // Prevent the default form submission
                // Create a FormData object from the form
                var formData = new FormData($('#fileForm')[0]);

                // Get the existing files from the FormData object
                var existingFiles = formData.getAll('customFiles[]');

                // Iterate through the files to be added
                allFiles.forEach(function(file) {
                    // Check if the file is already present in the FormData object
                    var isDuplicate = existingFiles.some(function(existingFile) {
                        return existingFile.name === file.name && existingFile.size === file.size;
                    });

                    // If the file is not a duplicate, append it to the FormData object
                    if (!isDuplicate) {
                        formData.append('customFiles[]', file);
                    }
                });
                $.ajax({
                    url: '<?php echo base_url('public/submitF')?>',
                    type: 'post',
                    data: formData,
                    success: function (response) {
                        console.log(response);
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
            if (files.length > 1) {
                const fileNames = files.map(file => file.name);
                fileInfo.innerHTML = `<strong>Selected Files:</strong> ${fileNames.join(', ')}`;
            } 
            else if(files.length == 1){
                fileInfo.innerHTML = `<strong>Selected Files:</strong> ${files[0].name}`;
            }else {
                fileInfo.innerHTML = ''; // No file selected
            }
        }

        // Additional event listener for the file input
        document.getElementById('customFiles').addEventListener('change', function () {
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