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

$tokenHeader = '';
if (isset($_SERVER['HTTP_X_APP_TOKEN'])) {
    $tokenHeader = $_SERVER['HTTP_X_APP_TOKEN'];
}
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $tokenHeader = trim(str_replace('Bearer', '', $_SERVER['HTTP_AUTHORIZATION']));
}
$token = $tokenHeader ? $tokenHeader : (isset($_POST['token']) ? $_POST['token'] : '');

if (!$token || $token !== $ayar['app_build_token']) {
    echo json_encode(array('error' => 'unauthorized'));
    exit;
}

$rootPath = __DIR__;
$uploadDir = $rootPath . '/i/uploads/app';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$saveFile = function($fileKey, $allowedExts) use ($uploadDir) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $fileName = $_FILES[$fileKey]['name'];
    $tmpName = $_FILES[$fileKey]['tmp_name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        return null;
    }
    $safeName = time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
    $destPath = $uploadDir . '/' . $safeName;
    if (move_uploaded_file($tmpName, $destPath)) {
        return 'i/uploads/app/' . $safeName;
    }
    return null;
};

$apkPath = $saveFile('apk', array('apk'));
$ipaPath = $saveFile('ipa', array('ipa'));
$iconPath = $saveFile('icon', array('png'));

$fields = array();
$params = array();

if ($apkPath) {
    $fields[] = "app_android_apk=:app_android_apk";
    $params['app_android_apk'] = $apkPath;
}
if ($ipaPath) {
    $fields[] = "app_ios_ipa=:app_ios_ipa";
    $params['app_ios_ipa'] = $ipaPath;
}
if ($iconPath) {
    $fields[] = "app_icon=:app_icon";
    $params['app_icon'] = $iconPath;
}
if (isset($_POST['android_version'])) {
    $fields[] = "app_android_version=:app_android_version";
    $params['app_android_version'] = $_POST['android_version'];
}
if (isset($_POST['ios_version'])) {
    $fields[] = "app_ios_version=:app_ios_version";
    $params['app_ios_version'] = $_POST['ios_version'];
}

if ($fields) {
    $sql = "UPDATE ayarlar SET " . implode(',', $fields) . " WHERE id='1'";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
}

echo json_encode(array(
    'ok' => true,
    'apk' => $apkPath,
    'ipa' => $ipaPath,
    'icon' => $iconPath
));
