<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page not found</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="<?=base_url($frontendThemePath.'assets/vendor/fontawesome/css/font-awesome.min.css')?>">
    <link type="text/css" rel="stylesheet" href="<?=base_url($frontendThemePath.'assets/css/theme.css')?>">
</head>
<body>
    <div class="pg-404">
        <div>
            <div class="code">404</div>
            <h2>We couldn't find the page you were looking for. It may have moved or no longer exists.</h2>
            <div class="pg-404-ctas">
                <a href="<?=base_url('frontend')?>" class="pg-btn pg-btn-primary"><i class="fa fa-home"></i> Go Home</a>
                <a href="<?=base_url('frontend/page/contact')?>" class="pg-btn pg-btn-outline"><i class="fa fa-envelope-o"></i> Contact Us</a>
            </div>
        </div>
    </div>
</body>
</html>
