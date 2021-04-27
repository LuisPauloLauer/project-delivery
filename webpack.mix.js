const mix = require('laravel-mix');

/*node_modules
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix

    /* FrameWork Vue  */
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')

    /*++++++++++++++++++++++++++++   ADMIN    ++++++++++++++++++++++++++++*/
    /*  node_modules
        Styles css Default libraryes scss
    */
    .sass('resources/views/admin/node_modules/scss/style.scss', 'public/admin/node_modules/css/style.css')
    .scripts('node_modules/croppie/croppie.css', 'public/admin/node_modules/css/croppie.css')
    .scripts('node_modules/cropperjs/dist/cropper.min.css', 'public/admin/node_modules/css/cropper.css')

    /*
        Styles css Components and masks and plugins
    */
    .scripts('resources/views/admin/node_modules/css/adminlte.min.css', 'public/admin/node_modules/css/adminlte.min.css')
    .scripts('resources/views/admin/node_modules/css/OverlayScrollbars.min.css', 'public/admin/node_modules/css/OverlayScrollbars.min.css')
    .scripts('resources/views/admin/node_modules/css/chosen.min.css', 'public/admin/node_modules/css/chosen.min.css')
    .scripts('resources/views/admin/node_modules/css/image-uploader.css', 'public/admin/node_modules/css/image-uploader.css')
    .scripts('resources/views/admin/node_modules/css/PDF_ordersToPrint.css', 'public/admin/node_modules/css/PDF_ordersToPrint.css')
    .scripts('resources/views/admin/node_modules/css/dropzone-image-object.css', 'public/admin/node_modules/css/dropzone-image-object.css')
    .scripts('resources/views/admin/node_modules/css/dropzone-image-avatar.css', 'public/admin/node_modules/css/dropzone-image-avatar.css')
    .scripts('resources/views/admin/node_modules/css/dropzone-image-logostore.css', 'public/admin/node_modules/css/dropzone-image-logostore.css')

    /*  node_modules
        Jquery Default libraryes
    */
    .scripts('node_modules/jquery/dist/jquery.js', 'public/admin/node_modules/js/jquery.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.js', 'public/admin/node_modules/js/bootstrap.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.bundle.js', 'public/admin/node_modules/js/bootstrap.bundle.js')
    .scripts('node_modules/popper.js/dist/umd/popper.js', 'public/admin/node_modules/js/popper.js')
    .scripts('node_modules/croppie/croppie.js', 'public/admin/node_modules/js/croppie.js')
    .scripts('node_modules/cropperjs/dist/cropper.min.js', 'public/admin/node_modules/js/cropper.js')

    /*
       Jquery Components and masks and plugins
    */
    .scripts('resources/views/admin/node_modules/js/jquery.overlayScrollbars.min.js', 'public/admin/node_modules/js/jquery.overlayScrollbars.min.js')
    .scripts('resources/views/admin/node_modules/js/adminlte.js', 'public/admin/node_modules/js/adminlte.js')
    .scripts('resources/views/admin/node_modules/js/demo.js', 'public/admin/node_modules/js/demo.js')
    .scripts('resources/views/admin/node_modules/js/jquery.mask.min.js', 'public/admin/node_modules/js/jquery.mask.min.js')
    .scripts('resources/views/admin/node_modules/js/chosen.jquery.min.js', 'public/admin/node_modules/js/chosen.jquery.min.js')
    .scripts('resources/views/admin/node_modules/js/image-uploader.js', 'public/admin/node_modules/js/image-uploader.js')
    .scripts('resources/views/admin/node_modules/js/option-components-default.js', 'public/admin/node_modules/js/option-components-default.js')

    /*
        Validations de forms and rules
    */
    .scripts('resources/views/admin/node_modules/validation/validate_zipcode.js', 'public/admin/node_modules/validation/validate_zipcode.js')


    /*++++++++++++++++++++++++++++   SITE    ++++++++++++++++++++++++++++*/
    /*  node_modules
        Styles css Default libraryes scss
    */
    .sass('resources/views/site/node_modules/scss/style.scss', 'public/site/node_modules/css/style.css')

    /*
        Styles css Components and masks and plugins
    */
    .scripts('resources/views/site/node_modules/css/master-style.css', 'public/site/node_modules/css/master-style.css')

    /*  node_modules
        Jquery Default libraryes
    */
    .scripts('node_modules/jquery/dist/jquery.js', 'public/site/node_modules/js/jquery.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.js', 'public/site/node_modules/js/bootstrap.js')
    .scripts('node_modules/bootstrap/dist/js/bootstrap.bundle.js', 'public/site/node_modules/js/bootstrap.bundle.js')
    .scripts('node_modules/popper.js/dist/umd/popper.js', 'public/site/node_modules/js/popper.js')

    /*
       Jquery Components and masks and plugins
    */
    //.scripts('resources/views/site/node_modules/js/product-modal-shop-cart.js', 'public/site/node_modules/js/product-modal-shop-cart.js');
