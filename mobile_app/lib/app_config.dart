import 'dart:convert';
import 'package:http/http.dart' as http;

class AppConfig {
  final String appName;
  final String webUrl;
  final String pushProvider;
  final String pushAppId;

  AppConfig({
    required this.appName,
    required this.webUrl,
    required this.pushProvider,
    required this.pushAppId,
  });

  static Future<AppConfig> fetch(String configUrl) async {
    final response = await http.get(Uri.parse(configUrl));
    if (response.statusCode != 200) {
      throw Exception('Config load failed');
    }
    final data = jsonDecode(response.body) as Map<String, dynamic>;
    final app = (data['app'] ?? {}) as Map<String, dynamic>;
    final push = (data['push'] ?? {}) as Map<String, dynamic>;

    return AppConfig(
      appName: (app['name'] ?? 'Uygulama') as String,
      webUrl: (app['web_url'] ?? '') as String,
      pushProvider: (push['provider'] ?? '') as String,
      pushAppId: (push['app_id'] ?? '') as String,
    );
  }
}
