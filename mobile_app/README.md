# Mobil Uygulama (Flutter WebView)

Bu klasor, e-ticaret sitesi icin WebView tabanli Flutter uygulamasini icerir.

## Kurulum
1) Flutter kurulu olmali.
2) Bu klasorde once platform dosyalarini olusturun:
```
flutter create .
```
3) Bagimliliklari yukleyin:
```
flutter pub get
```

## Calistirma
```
flutter run
```

## Uygulama Ayarlari
Uygulama panelden ayarlari `app-config.php` uzerinden ceker.
- Varsayilan URL: `https://SITENIZ/app-config.php`

## Build
Android: `flutter build apk` veya `flutter build appbundle`
 iOS: `flutter build ipa`

CI/CD icin GitHub Actions / Bitrise / Codemagic kullanabilirsiniz.
