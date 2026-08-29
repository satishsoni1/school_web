<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// OneSignal credentials used by Notification_lib (mvc/libraries/Notification_lib.php)
// to push notifications and by app.component.ts (OneSignal.initialize) on the client.
// Moved out of Notice.php, where these were previously hardcoded inline.
$config['onesignal_app_id'] = getenv('ONESIGNAL_APP_ID');
$config['onesignal_api_key'] = getenv('ONESIGNAL_API_KEY');
