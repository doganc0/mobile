<?php echo !defined("GUVENLIK") ? die("Vaoww! Bu ne cesaret?") : null;?>
<?php
if($yetki['demo'] != '1' ) {

    if($_POST && isset($_POST['settingsUpdate'])  ) {
        $siteurl = $_POST['site_url'];
        $panel_url = $_POST['panel_url'];
        $baslik = $_POST['site_baslik'];
        $site_desc = $_POST['site_desc'];
        $site_tags = $_POST['site_tags'];
        $facebook_pixel_id = isset($_POST['facebook_pixel_id']) ? $_POST['facebook_pixel_id'] : '';
        $toptan_seri_satis_aktif = isset($_POST['toptan_seri_satis_aktif']) ? $_POST['toptan_seri_satis_aktif'] : '0';
        $kargo_karsi_odeme_aktif = isset($_POST['kargo_karsi_odeme_aktif']) ? $_POST['kargo_karsi_odeme_aktif'] : '0';
        $site_width = $_POST['site_width'];
        $site_bg = $_POST['site_bg_color'];
        $sekme = $_POST['sekme_degistir_yazi'];
        $lazy = $_POST['lazy'];
        $site_captcha = $_POST['site_captcha'];
        $totop = $_POST['totop'];
        $totop_bg = $_POST['totop_bg'];
        $totop_icon = $_POST['totop_icon'];
        $totop_bottom = $_POST['totop_bottom'];
        $totop_radius = $_POST['totop_radius'];
        $app_android_version = isset($_POST['app_android_version']) ? $_POST['app_android_version'] : '';
        $app_android_notes = isset($_POST['app_android_notes']) ? $_POST['app_android_notes'] : '';
        $app_ios_version = isset($_POST['app_ios_version']) ? $_POST['app_ios_version'] : '';
        $app_ios_notes = isset($_POST['app_ios_notes']) ? $_POST['app_ios_notes'] : '';
        $app_push_provider = isset($_POST['app_push_provider']) ? $_POST['app_push_provider'] : '';
        $app_push_app_id = isset($_POST['app_push_app_id']) ? $_POST['app_push_app_id'] : '';
        $app_push_rest_key = isset($_POST['app_push_rest_key']) ? $_POST['app_push_rest_key'] : '';
        $app_display_name = isset($_POST['app_display_name']) ? $_POST['app_display_name'] : '';
        $app_web_url = isset($_POST['app_web_url']) ? $_POST['app_web_url'] : '';
        $app_package_name = isset($_POST['app_package_name']) ? $_POST['app_package_name'] : '';
        $app_bundle_id = isset($_POST['app_bundle_id']) ? $_POST['app_bundle_id'] : '';
        $app_build_webhook = isset($_POST['app_build_webhook']) ? $_POST['app_build_webhook'] : '';
        $app_build_token = isset($_POST['app_build_token']) ? $_POST['app_build_token'] : '';
        
        /* Renk Replace */
        $site_bg  = $_POST['site_bg_color'];
        $eski   = '#';
        $yeni   = '';
        $site_bg = str_replace($eski, $yeni, $site_bg);

        $totop_bg  = $_POST['totop_bg'];
        $eski   = '#';
        $yeni   = '';
        $totop_bg = str_replace($eski, $yeni, $totop_bg);

        $totop_icon  = $_POST['totop_icon'];
        $eski   = '#';
        $yeni   = '';
        $totop_icon = str_replace($eski, $yeni, $totop_icon);
        /*  <========SON=========>>> Renk Replace SON */



        if($siteurl && $panel_url  && $totop_bottom && $baslik && ($site_width == '1' || $site_width == '0')){
            // toptan_seri_satis_aktif alanının varlığını kontrol et, yoksa ekle
            try {
                $alanKontrol = $db->query("SHOW COLUMNS FROM ayarlar LIKE 'toptan_seri_satis_aktif'");
                if($alanKontrol->rowCount() == 0) {
                    $db->exec("ALTER TABLE `ayarlar` ADD `toptan_seri_satis_aktif` TINYINT(1) DEFAULT '1' COMMENT 'Toptan seri satış aktif/pasif (1=Aktif, 0=Pasif)'");
                }
            } catch(Exception $e) {
                // Alan zaten varsa veya başka bir hata varsa devam et
            }
            
            // kargo_karsi_odeme_aktif alanının varlığını kontrol et, yoksa ekle
            try {
                $alanKontrol2 = $db->query("SHOW COLUMNS FROM ayarlar LIKE 'kargo_karsi_odeme_aktif'");
                if($alanKontrol2->rowCount() == 0) {
                    $db->exec("ALTER TABLE `ayarlar` ADD `kargo_karsi_odeme_aktif` TINYINT(1) DEFAULT '1' COMMENT 'Kargo karşı ödeme aktif/pasif (1=Aktif, 0=Pasif)'");
                }
            } catch(Exception $e) {
                // Alan zaten varsa veya başka bir hata varsa devam et
            }

            // facebook_pixel_id alanının varlığını kontrol et, yoksa ekle
            try {
                $alanKontrol3 = $db->query("SHOW COLUMNS FROM ayarlar LIKE 'facebook_pixel_id'");
                if($alanKontrol3->rowCount() == 0) {
                    $db->exec("ALTER TABLE `ayarlar` ADD `facebook_pixel_id` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Facebook Pixel ID'");
                }
            } catch(Exception $e) {
                // Alan zaten varsa veya başka bir hata varsa devam et
            }
            
            // mobil uygulama alanlarını kontrol et, yoksa ekle
            try {
                $appColumnChecks = array(
                    "app_display_name" => "VARCHAR(120) NULL DEFAULT NULL",
                    "app_web_url" => "VARCHAR(255) NULL DEFAULT NULL",
                    "app_package_name" => "VARCHAR(150) NULL DEFAULT NULL",
                    "app_bundle_id" => "VARCHAR(150) NULL DEFAULT NULL",
                    "app_icon" => "VARCHAR(255) NULL DEFAULT NULL",
                    "app_android_apk" => "VARCHAR(255) NULL DEFAULT NULL",
                    "app_android_version" => "VARCHAR(32) NULL DEFAULT NULL",
                    "app_android_notes" => "TEXT NULL",
                    "app_ios_ipa" => "VARCHAR(255) NULL DEFAULT NULL",
                    "app_ios_version" => "VARCHAR(32) NULL DEFAULT NULL",
                    "app_ios_notes" => "TEXT NULL",
                    "app_push_provider" => "VARCHAR(50) NULL DEFAULT NULL",
                    "app_push_app_id" => "VARCHAR(200) NULL DEFAULT NULL",
                    "app_push_rest_key" => "VARCHAR(200) NULL DEFAULT NULL",
                    "app_build_webhook" => "VARCHAR(255) NULL DEFAULT NULL",
                    "app_build_token" => "VARCHAR(255) NULL DEFAULT NULL"
                );
                foreach ($appColumnChecks as $column => $definition) {
                    $check = $db->query("SHOW COLUMNS FROM ayarlar LIKE '{$column}'");
                    if ($check->rowCount() == 0) {
                        $db->exec("ALTER TABLE `ayarlar` ADD `{$column}` {$definition}");
                    }
                }
            } catch(Exception $e) {
                // Alan zaten varsa veya başka bir hata varsa devam et
            }

            $appAndroidApk = isset($ayar['app_android_apk']) ? $ayar['app_android_apk'] : '';
            $appIosIpa = isset($ayar['app_ios_ipa']) ? $ayar['app_ios_ipa'] : '';
            $appIcon = isset($ayar['app_icon']) ? $ayar['app_icon'] : '';

            $rootPath = dirname(__FILE__, 5);
            $uploadDir = $rootPath . '/i/uploads/app';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $uploadFile = function($fileKey, $allowedExts) use ($uploadDir) {
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

            $uploadedAndroidApk = $uploadFile('app_android_apk', array('apk'));
            if ($uploadedAndroidApk) {
                $appAndroidApk = $uploadedAndroidApk;
            }
            $uploadedIosIpa = $uploadFile('app_ios_ipa', array('ipa'));
            if ($uploadedIosIpa) {
                $appIosIpa = $uploadedIosIpa;
            }
            $uploadedIcon = $uploadFile('app_icon', array('png'));
            if ($uploadedIcon) {
                $appIcon = $uploadedIcon;
            }

            $guncelle = $db->prepare("UPDATE ayarlar SET
                    site_baslik=:site_baslik,
                    panel_url=:panel_url,
                    site_url=:site_url,
                    protokol=:protokol,
                    demo_mod=:demo_mod,
                    site_desc=:site_desc,
                    site_tags=:site_tags,
                    facebook_pixel_id=:facebook_pixel_id,
                    toptan_seri_satis_aktif=:toptan_seri_satis_aktif,
                    kargo_karsi_odeme_aktif=:kargo_karsi_odeme_aktif,
                    site_width=:site_width,
                    site_bg_color=:site_bg_color,
                    sekme_degistir_yazi=:sekme_degistir_yazi,
                    site_captcha=:site_captcha,
                    lazy=:lazy,
                    uye_log=:uye_log,
                    yonetici_log=:yonetici_log,
                    login_log=:login_log,
                    totop=:totop,
                    totop_bg=:totop_bg,
                    totop_icon=:totop_icon,
                    totop_bottom=:totop_bottom,
                    totop_radius=:totop_radius,
                    app_display_name=:app_display_name,
                    app_web_url=:app_web_url,
                    app_package_name=:app_package_name,
                    app_bundle_id=:app_bundle_id,
                    app_icon=:app_icon,
                    app_android_apk=:app_android_apk,
                    app_android_version=:app_android_version,
                    app_android_notes=:app_android_notes,
                    app_ios_ipa=:app_ios_ipa,
                    app_ios_version=:app_ios_version,
                    app_ios_notes=:app_ios_notes,
                    app_push_provider=:app_push_provider,
                    app_push_app_id=:app_push_app_id,
                    app_push_rest_key=:app_push_rest_key,
                    app_build_webhook=:app_build_webhook,
                    app_build_token=:app_build_token
             WHERE id='1'      
            ");
            $sonuc = $guncelle->execute(array(
                'site_baslik' => $baslik,
                'panel_url' => $panel_url,
                'site_url' => $siteurl,
                'protokol' => $_POST['protokol'],
                'demo_mod' => $_POST['demo_mod'],
                'site_desc' => $site_desc,
                'site_tags' => $site_tags,
                'facebook_pixel_id' => $facebook_pixel_id,
                'toptan_seri_satis_aktif' => $toptan_seri_satis_aktif,
                'kargo_karsi_odeme_aktif' => $kargo_karsi_odeme_aktif,
                'site_width' => $site_width,
                'site_bg_color' => $site_bg,
                'sekme_degistir_yazi' => $sekme,
                'site_captcha' => $site_captcha,
                'lazy' => $lazy,
                'uye_log' => $_POST['uye_log'],
                'yonetici_log' => $_POST['yonetici_log'],
                'login_log' => $_POST['login_log'],
                'totop' => $totop,
                'totop_bg' => $totop_bg,
                'totop_icon' => $totop_icon,
                'totop_bottom' => $totop_bottom,
                'totop_radius' => $totop_radius,
                'app_display_name' => $app_display_name,
                'app_web_url' => $app_web_url,
                'app_package_name' => $app_package_name,
                'app_bundle_id' => $app_bundle_id,
                'app_icon' => $appIcon,
                'app_android_apk' => $appAndroidApk,
                'app_android_version' => $app_android_version,
                'app_android_notes' => $app_android_notes,
                'app_ios_ipa' => $appIosIpa,
                'app_ios_version' => $app_ios_version,
                'app_ios_notes' => $app_ios_notes,
                'app_push_provider' => $app_push_provider,
                'app_push_app_id' => $app_push_app_id,
                'app_push_rest_key' => $app_push_rest_key,
                'app_build_webhook' => $app_build_webhook,
                'app_build_token' => $app_build_token
            ));
            if($sonuc){
                if (isset($_POST['appBuild']) && $app_build_webhook) {
                    $configUrl = rtrim($ayar['site_url'], '/') . '/app-config.php';
                    $payload = json_encode(array(
                        'event_type' => 'mobile_app_build',
                        'client_payload' => array(
                            'app_display_name' => $app_display_name,
                            'app_web_url' => $app_web_url,
                            'app_package_name' => $app_package_name,
                            'app_bundle_id' => $app_bundle_id,
                            'app_icon' => $appIcon,
                            'app_android_version' => $app_android_version,
                            'app_ios_version' => $app_ios_version,
                            'app_push_provider' => $app_push_provider,
                            'app_push_app_id' => $app_push_app_id,
                            'config_url' => $configUrl
                        )
                    ));
                    $ch = curl_init($app_build_webhook);
                    $headers = array('Content-Type: application/json');
                    if (!empty($app_build_token)) {
                        $headers[] = 'Authorization: Bearer ' . $app_build_token;
                    }
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_exec($ch);
                    curl_close($ch);
                }
                header('Location:'.$ayar['panel_url'].'pages.php?page=settings');
                $_SESSION['main_alert'] = 'success';
            }else{
                $errorInfo = $guncelle->errorInfo();
                echo 'Veritabanı Hatası: ' . (isset($errorInfo[2]) ? $errorInfo[2] : 'Bilinmeyen hata');
                echo '<br><a href="'.$ayar['panel_url'].'pages.php?page=settings">Geri Dön</a>';
            }
        }else{
            header('Location:'.$ayar['panel_url'].'pages.php?page=settings');
            $_SESSION['main_alert'] = 'zorunlu';
        }
    }else{
        header('Location:'.$ayar['site_url'].'404');
    }

}else{
    header('Location:'.$ayar['panel_url'].'pages.php?page=settings');
    $_SESSION['main_alert'] = 'demo';
}
?>