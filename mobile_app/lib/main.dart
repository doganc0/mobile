import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:onesignal_flutter/onesignal_flutter.dart';
import 'app_config.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatefulWidget {
  const MyApp({super.key});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  late Future<AppConfig> _configFuture;

  // Paneldeki app-config.php adresi buraya yazilir
  final String configUrl =
      const String.fromEnvironment('CONFIG_URL', defaultValue: 'https://SITENIZ/app-config.php');

  @override
  void initState() {
    super.initState();
    _configFuture = AppConfig.fetch(configUrl);
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Mobil Uygulama',
      debugShowCheckedModeBanner: false,
      home: FutureBuilder<AppConfig>(
        future: _configFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState != ConnectionState.done) {
            return const Scaffold(
              body: Center(child: CircularProgressIndicator()),
            );
          }
          if (snapshot.hasError || !snapshot.hasData) {
            return const Scaffold(
              body: Center(child: Text('Ayarlar yuklenemedi')),
            );
          }

          final config = snapshot.data!;
          _initPush(config);

          return WebViewPage(
            title: config.appName,
            initialUrl: config.webUrl,
          );
        },
      ),
    );
  }

  void _initPush(AppConfig config) {
    if (config.pushProvider.toLowerCase() != 'onesignal') {
      return;
    }
    if (config.pushAppId.isEmpty) {
      return;
    }
    OneSignal.initialize(config.pushAppId);
    OneSignal.Notifications.requestPermission(true);
  }
}

class WebViewPage extends StatefulWidget {
  final String title;
  final String initialUrl;

  const WebViewPage({
    super.key,
    required this.title,
    required this.initialUrl,
  });

  @override
  State<WebViewPage> createState() => _WebViewPageState();
}

class _WebViewPageState extends State<WebViewPage> {
  late final WebViewController _controller;

  @override
  void initState() {
    super.initState();
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..loadRequest(Uri.parse(widget.initialUrl));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.title),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => _controller.reload(),
          ),
        ],
      ),
      body: WebViewWidget(controller: _controller),
    );
  }
}
