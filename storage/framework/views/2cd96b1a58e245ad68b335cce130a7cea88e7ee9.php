<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<%= BASE_URL %>favicon.ico">
    <link rel="icon" href="/images/favicon.ico">
   
    <title><?php echo e(config('app.name', 'Bapp')); ?></title>
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/main.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/vuesax.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/iconfont.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/material-icons/material-icons.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/prism-tomorrow.css'))); ?>">
    <link rel="stylesheet" href="<?php echo e(asset(mix('css/app.css'))); ?>">
  </head>
  <body>
    <noscript>
      <strong>We're sorry but Vuesax - Vuejs Admin Dashboard Template doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
    </div>
    <!-- <script src="js/app.js"></script> -->
    <script src="<?php echo e(asset(mix('js/app.js'))); ?>"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCk6Qet4uRb2EaiEoBgw-eOXedRMUuoudA&libraries=places,drawing&region=cl"></script>
          
    <script>
      <?php if(auth()->guard()->check()): ?>
        window.Permissions = <?php echo json_encode(Auth::user()->allPermissions, true); ?>;
      <?php else: ?>
        window.Permissions = [];
      <?php endif; ?>
    </script>

  </body>
 
</html>
<?php /**PATH C:\xampp\htdocs\vuesax\resources\views/application.blade.php ENDPATH**/ ?>