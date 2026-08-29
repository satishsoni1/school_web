<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// OneSignal credentials used by Notification_lib (mvc/libraries/Notification_lib.php)
// to push notifications and by app.component.ts (OneSignal.initialize) on the client.
// Moved out of Notice.php, where these were previously hardcoded inline.

// Default values
$config['onesignal_app_id'] = '';
$config['onesignal_api_key'] = '';

// Load local configuration if available
$local_config = APPPATH . 'config/onesignal_local.php';

if (file_exists($local_config)) {
    require $local_config;
}