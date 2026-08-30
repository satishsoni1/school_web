
<?php
    if (!function_exists('pgAssetVer')) {
        function pgAssetVer($relPath) {
            $full = FCPATH . $relPath;
            return file_exists($full) ? filemtime($full) : time();
        }
    }
?>
<script src="<?=base_url($frontendThemePath.'assets/vendor/jquery/jquery.min.js?v='.pgAssetVer($frontendThemePath.'assets/vendor/jquery/jquery.min.js'))?>"></script>
<script src="<?=base_url($frontendThemePath.'assets/vendor/bootstrap/bootstrap.min.js?v='.pgAssetVer($frontendThemePath.'assets/vendor/bootstrap/bootstrap.min.js'))?>"></script>
<script src="<?=base_url($frontendThemePath.'assets/vendor/toastr/toastr.min.js?v='.pgAssetVer($frontendThemePath.'assets/vendor/toastr/toastr.min.js'))?>"></script>
<script src="<?=base_url($frontendThemePath.'assets/js/theme.js?v='.pgAssetVer($frontendThemePath.'assets/js/theme.js'))?>"></script>

@yield('footerAssetPush')
