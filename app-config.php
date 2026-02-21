<?php
header('Content-Type: application/json; charset=utf-8');
include "includes/config/config.php";

$settings = $db->prepare("SELECT * from ayarlar where id='1'");
$settings->execute(array(0));
$ayar = $settings->fetch(PDO::FETCH_ASSOC);

if (!$ayar) {
    echo json_encode(array('error' => 'settings_not_found'));
    exit;
}

$siteUrl = rtrim($ayar['site_url'], '/') . '/';
$buildFileUrl = function($path) use ($siteUrl) {
    if (!$path) {
        return null;
    }
    return $siteUrl . ltrim($path, '/');
};

echo json_encode(array(
    'app' => array(
        'name' => isset($ayar['app_display_name']) ? $ayar['app_display_name'] : $ayar['site_baslik'],
        'web_url' => isset($ayar['app_web_url']) && $ayar['app_web_url'] ? $ayar['app_web_url'] : $siteUrl,
        'package_name' => isset($ayar['app_package_name']) ? $ayar['app_package_name'] : '',
        'bundle_id' => isset($ayar['app_bundle_id']) ? $ayar['app_bundle_id'] : '',
        'icon_url' => $buildFileUrl(isset($ayar['app_icon']) ? $ayar['app_icon'] : null),
    ),
    'android' => array(
        'version' => isset($ayar['app_android_version']) ? $ayar['app_android_version'] : '',
        'notes' => isset($ayar['app_android_notes']) ? $ayar['app_android_notes'] : '',
        'apk_url' => $buildFileUrl(isset($ayar['app_android_apk']) ? $ayar['app_android_apk'] : null),
    ),
    'ios' => array(
        'version' => isset($ayar['app_ios_version']) ? $ayar['app_ios_version'] : '',
        'notes' => isset($ayar['app_ios_notes']) ? $ayar['app_ios_notes'] : '',
        'ipa_url' => $buildFileUrl(isset($ayar['app_ios_ipa']) ? $ayar['app_ios_ipa'] : null),
    ),
    'push' => array(
        'provider' => isset($ayar['app_push_provider']) ? $ayar['app_push_provider'] : '',
        'app_id' => isset($ayar['app_push_app_id']) ? $ayar['app_push_app_id'] : '',
    ),
    'updated_at' => time()
));
