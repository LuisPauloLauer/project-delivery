<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Cropping with Cropper JS</title>
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/cropper.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/node_modules/css/cropper.css') }}">


    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/cropper.min.css">
    <link rel="stylesheet" href="css/cropper.css">
    <link rel="stylesheet" href="css/styles.css">


</head>
<body>
<div class="container" id="crop-avatar">

    <!-- Current avatar -->
    <div class="avatar-view" title="Change the avatar">
        <img src="img/img.jpg" alt="Avatar">
    </div>

    <div id="avatar-modal1">
        <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">

            <!-- Upload image and data -->
            <div class="avatar-upload">
                <input type="hidden" class="avatar-src" name="avatar_src">
                <input type="hidden" class="avatar-data" name="avatar_data">
                <label for="avatarInput">Upload</label>
                <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
            </div>
            <!-- Cropping modal -->
            <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="avatar-modal-label">Change Image</h4>
                        </div>
                        <div class="modal-body">
                            <div class="avatar-body">



                                <!-- Crop and preview -->
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"> </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="avatar-preview preview-lg" style="border-radius:90px !important;"></div>
                                        <!-- <div class="avatar-preview preview-md"></div>
                                        <div class="avatar-preview preview-sm"></div>-->
                                        <br>
                                        <h5>Output value(width,height)</h5>
                                        <div id="output"></div>



                                    </div>
                                </div>

                                <div class="row avatar-btns">
                                    <!--<div class="col-md-9">
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees">Rotate Left</button>
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-15">-15deg</button>
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-30">-30deg</button>
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45">-45deg</button>
                                      </div>
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees">Rotate Right</button>
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="15">15deg</button>
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="30">30deg</button>
                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45">45deg</button>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                                    </div>-->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- /.modal -->

        </form>
    </div>
    <!-- Loading state -->
    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
</div>
<!-- jquery -->
<script src="{{ asset('admin/node_modules/js/jquery.js') }}"></script>
<!-- bootstrap -->
<script src="{{ asset('admin/node_modules/js/bootstrap.js') }}"></script>
<!-- bootstrap.bundle -->
<script src="{{ asset('admin/node_modules/js/bootstrap.bundle.js') }}"></script>
<!-- popper -->
<script src="{{ asset('admin/node_modules/js/popper.js') }}"></script>


<script src="{{ asset('admin/node_modules/js/cropper.min.js') }}"></script>
<script src="{{ asset('admin/node_modules/js/cropper.main.js') }}"></script>
</body>
</html>
