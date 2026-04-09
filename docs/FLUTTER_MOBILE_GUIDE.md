# 📱 Panduan Lengkap: Laravel ke Flutter Mobile

## 📚 Daftar Isi
1. [Arsitektur Sistem](#arsitektur-sistem)
2. [Persiapan Backend (Laravel)](#persiapan-backend-laravel)
3. [Setup Project Flutter](#setup-project-flutter)
4. [Implementasi Step-by-Step](#implementasi-step-by-step)
5. [Testing & Deployment](#testing--deployment)

---

## 🏗️ Arsitektur Sistem

### Current (Web Only)
```
Browser → Laravel (Blade) → Database
```

### Target (Web + Mobile)
```
Browser → Laravel (Blade) → Database
   ↓
Mobile App (Flutter) → Laravel API → Database
```

### Teknologi Stack
- **Backend**: Laravel 11+ (PHP 8.2+)
- **Database**: PostgreSQL (Supabase)
- **API**: Laravel Sanctum (Authentication)
- **Mobile**: Flutter 3.16+
- **State Management**: Provider / Riverpod
- **HTTP Client**: Dio / http package

---

## 🔧 Persiapan Backend (Laravel)

### STEP 1: Install Laravel Sanctum

```bash
# Di folder project Laravel
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### STEP 2: Konfigurasi Sanctum

**File: `config/sanctum.php`**
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 
    sprintf('%s%s', 'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1', 
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : '')
)),

'middleware' => [
    'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
],
```

**File: `app/Http/Kernel.php`**
Tambahkan di `$middlewareGroups['api']`:
```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

**File: `app/Models/User.php`**
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // ... rest of code
}
```

### STEP 3: Buat API Routes

**File: `routes/api.php`**
```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PerjanjianController;
use App\Http\Controllers\Api\LaporanController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Perjanjian
    Route::apiResource('perjanjian', PerjanjianController::class);
    Route::get('perjanjian/{id}/print', [PerjanjianController::class, 'getPrintData']);
    
    // Laporan
    Route::apiResource('laporan', LaporanController::class);
    
    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
```

### STEP 4: Buat API Controllers

**File: `app/Http/Controllers/Api/AuthController.php`**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Create token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
```

**File: `app/Http/Controllers/Api/PerjanjianController.php`**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perjanjian;
use Illuminate\Http\Request;

class PerjanjianController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Perjanjian::query();
        
        // Filter berdasarkan role
        if (stripos($user->jabatan, 'direktur') === false) {
            $query->where('user_id', $user->id);
        }
        
        $perjanjians = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $perjanjians
        ]);
    }

    public function show($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $perjanjian
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pihak2_name' => 'required|string',
            'pihak2_jabatan' => 'required|string',
            // ... validasi lainnya
        ]);

        $perjanjian = Perjanjian::create([
            'user_id' => $request->user()->id,
            'pihak1_name' => $request->user()->nama,
            'pihak1_jabatan' => $request->user()->jabatan,
            // ... data lainnya
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perjanjian berhasil dibuat',
            'data' => $perjanjian
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        
        // Validasi authorization
        if ($request->user()->id !== $perjanjian->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $perjanjian->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Perjanjian berhasil diupdate',
            'data' => $perjanjian
        ]);
    }

    public function destroy($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $perjanjian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perjanjian berhasil dihapus'
        ]);
    }

    public function getPrintData($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        
        // Parse JSON data
        $tabelA = json_decode($perjanjian->tabelA, true);
        $tabelB = json_decode($perjanjian->tabelB, true);
        $tabelC = json_decode($perjanjian->tabelC, true);

        return response()->json([
            'success' => true,
            'data' => [
                'perjanjian' => $perjanjian,
                'tabelA' => $tabelA,
                'tabelB' => $tabelB,
                'tabelC' => $tabelC,
            ]
        ]);
    }
}
```

### STEP 5: Test API dengan Postman

```bash
# Start Laravel server
php artisan serve

# Test endpoints:
POST http://127.0.0.1:8000/api/login
{
    "email": "user@example.com",
    "password": "password"
}

GET http://127.0.0.1:8000/api/perjanjian
Authorization: Bearer {token}
```

---

## 📱 Setup Project Flutter

### STEP 1: Install Flutter

```bash
# Download Flutter SDK dari https://flutter.dev
# Extract ke folder (misal: C:\flutter)
# Tambahkan ke PATH environment variable

# Cek instalasi
flutter doctor

# Jika ada yang kurang, install:
# - Android Studio
# - VS Code + Flutter Extension
# - Android SDK
```

### STEP 2: Buat Project Flutter

```bash
# Di folder terpisah dari Laravel
flutter create perjanjian_kinerja_mobile
cd perjanjian_kinerja_mobile

# Test run
flutter run
```

### STEP 3: Install Dependencies

**File: `pubspec.yaml`**
```yaml
dependencies:
  flutter:
    sdk: flutter
  
  # HTTP & API
  dio: ^5.4.0
  http: ^1.1.0
  
  # State Management
  provider: ^6.1.1
  
  # Local Storage
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0
  
  # UI
  google_fonts: ^6.1.0
  flutter_svg: ^2.0.9
  cached_network_image: ^3.3.0
  
  # PDF
  pdf: ^3.10.7
  printing: ^5.11.1
  
  # Utils
  intl: ^0.18.1
  connectivity_plus: ^5.0.2

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
```

```bash
# Install packages
flutter pub get
```

### STEP 4: Struktur Folder Project

```
lib/
├── main.dart
├── config/
│   ├── app_config.dart         # Config API URL, dll
│   └── theme.dart              # Theme & Colors
├── models/
│   ├── user.dart
│   ├── perjanjian.dart
│   └── laporan.dart
├── services/
│   ├── api_service.dart        # HTTP Client setup
│   ├── auth_service.dart       # Login/Logout
│   └── storage_service.dart    # Local storage
├── providers/
│   ├── auth_provider.dart      # Auth state management
│   └── perjanjian_provider.dart
├── screens/
│   ├── auth/
│   │   ├── login_screen.dart
│   │   └── splash_screen.dart
│   ├── home/
│   │   └── home_screen.dart
│   ├── perjanjian/
│   │   ├── perjanjian_list_screen.dart
│   │   ├── perjanjian_detail_screen.dart
│   │   ├── perjanjian_create_screen.dart
│   │   └── perjanjian_print_screen.dart
│   └── profile/
│       └── profile_screen.dart
└── widgets/
    ├── custom_button.dart
    ├── custom_textfield.dart
    └── loading_indicator.dart
```

---

## 🚀 Implementasi Step-by-Step

### STEP 1: Setup Config

**File: `lib/config/app_config.dart`**
```dart
class AppConfig {
  // Ganti dengan IP komputer Anda (bukan localhost!)
  // Cek dengan: ipconfig (Windows) atau ifconfig (Mac/Linux)
  static const String baseUrl = 'http://192.168.1.100:8000/api';
  
  static const String appName = 'Perjanjian Kinerja';
  static const String appVersion = '1.0.0';
}
```

**File: `lib/config/theme.dart`**
```dart
import 'package:flutter/material.dart';

class AppTheme {
  static const Color primaryColor = Color(0xFF009970);
  static const Color secondaryColor = Color(0xFF00B5A0);
  
  static ThemeData get lightTheme {
    return ThemeData(
      primaryColor: primaryColor,
      colorScheme: ColorScheme.fromSeed(seedColor: primaryColor),
      appBarTheme: const AppBarTheme(
        backgroundColor: primaryColor,
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryColor,
          foregroundColor: Colors.white,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
          ),
        ),
      ),
    );
  }
}
```

### STEP 2: Setup Models

**File: `lib/models/user.dart`**
```dart
class User {
  final int id;
  final String nama;
  final String email;
  final String? jabatan;
  final String? nip;
  final String? pangkat;
  
  User({
    required this.id,
    required this.nama,
    required this.email,
    this.jabatan,
    this.nip,
    this.pangkat,
  });
  
  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      nama: json['nama'],
      email: json['email'],
      jabatan: json['jabatan'],
      nip: json['nip'],
      pangkat: json['pangkat'],
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
      'email': email,
      'jabatan': jabatan,
      'nip': nip,
      'pangkat': pangkat,
    };
  }
}
```

**File: `lib/models/perjanjian.dart`**
```dart
class Perjanjian {
  final int id;
  final String nomorPerjanjian;
  final String pihak1Name;
  final String pihak1Jabatan;
  final String pihak2Name;
  final String pihak2Jabatan;
  final String? status;
  final DateTime createdAt;
  
  Perjanjian({
    required this.id,
    required this.nomorPerjanjian,
    required this.pihak1Name,
    required this.pihak1Jabatan,
    required this.pihak2Name,
    required this.pihak2Jabatan,
    this.status,
    required this.createdAt,
  });
  
  factory Perjanjian.fromJson(Map<String, dynamic> json) {
    return Perjanjian(
      id: json['id'],
      nomorPerjanjian: json['nomor_perjanjian'] ?? '',
      pihak1Name: json['pihak1_name'] ?? '',
      pihak1Jabatan: json['pihak1_jabatan'] ?? '',
      pihak2Name: json['pihak2_name'] ?? '',
      pihak2Jabatan: json['pihak2_jabatan'] ?? '',
      status: json['status'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
```

### STEP 3: Setup Services

**File: `lib/services/api_service.dart`**
```dart
import 'package:dio/dio.dart';
import '../config/app_config.dart';
import 'storage_service.dart';

class ApiService {
  late Dio _dio;
  final StorageService _storage = StorageService();
  
  ApiService() {
    _dio = Dio(
      BaseOptions(
        baseUrl: AppConfig.baseUrl,
        connectTimeout: const Duration(seconds: 30),
        receiveTimeout: const Duration(seconds: 30),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      ),
    );
    
    // Add token to every request
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          String? token = await _storage.getToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (error, handler) async {
          if (error.response?.statusCode == 401) {
            // Token expired, logout user
            await _storage.deleteToken();
          }
          return handler.next(error);
        },
      ),
    );
  }
  
  Dio get dio => _dio;
}
```

**File: `lib/services/storage_service.dart`**
```dart
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../models/user.dart';

class StorageService {
  static const String _tokenKey = 'auth_token';
  static const String _userKey = 'user_data';
  
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
  }
  
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }
  
  Future<void> deleteToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
    await prefs.remove(_userKey);
  }
  
  Future<void> saveUser(User user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_userKey, jsonEncode(user.toJson()));
  }
  
  Future<User?> getUser() async {
    final prefs = await SharedPreferences.getInstance();
    String? userData = prefs.getString(_userKey);
    if (userData != null) {
      return User.fromJson(jsonDecode(userData));
    }
    return null;
  }
}
```

**File: `lib/services/auth_service.dart`**
```dart
import 'package:dio/dio.dart';
import 'api_service.dart';
import 'storage_service.dart';
import '../models/user.dart';

class AuthService {
  final ApiService _apiService = ApiService();
  final StorageService _storage = StorageService();
  
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _apiService.dio.post(
        '/login',
        data: {
          'email': email,
          'password': password,
        },
      );
      
      if (response.data['success']) {
        String token = response.data['data']['token'];
        User user = User.fromJson(response.data['data']['user']);
        
        await _storage.saveToken(token);
        await _storage.saveUser(user);
        
        return {'success': true, 'user': user};
      }
      
      return {'success': false, 'message': 'Login gagal'};
    } on DioException catch (e) {
      return {
        'success': false,
        'message': e.response?.data['message'] ?? 'Terjadi kesalahan'
      };
    }
  }
  
  Future<void> logout() async {
    try {
      await _apiService.dio.post('/logout');
    } catch (e) {
      // Ignore error
    } finally {
      await _storage.deleteToken();
    }
  }
  
  Future<bool> isLoggedIn() async {
    String? token = await _storage.getToken();
    return token != null;
  }
}
```

### STEP 4: Buat Screens

**File: `lib/screens/auth/login_screen.dart`**
```dart
import 'package:flutter/material.dart';
import '../../services/auth_service.dart';
import '../home/home_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _authService = AuthService();
  bool _isLoading = false;

  Future<void> _handleLogin() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    final result = await _authService.login(
      _emailController.text,
      _passwordController.text,
    );

    setState(() => _isLoading = false);

    if (result['success']) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (_) => const HomeScreen()),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message'])),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  'Perjanjian Kinerja',
                  style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                        fontWeight: FontWeight.bold,
                        color: Theme.of(context).primaryColor,
                      ),
                ),
                const SizedBox(height: 48),
                TextFormField(
                  controller: _emailController,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.email),
                  ),
                  keyboardType: TextInputType.emailAddress,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Email harus diisi';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _passwordController,
                  decoration: const InputDecoration(
                    labelText: 'Password',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.lock),
                  ),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Password harus diisi';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 24),
                SizedBox(
                  width: double.infinity,
                  height: 48,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _handleLogin,
                    child: _isLoading
                        ? const CircularProgressIndicator(color: Colors.white)
                        : const Text('LOGIN'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
```

**File: `lib/screens/home/home_screen.dart`**
```dart
import 'package:flutter/material.dart';
import '../../services/auth_service.dart';
import '../auth/login_screen.dart';
import '../perjanjian/perjanjian_list_screen.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Perjanjian Kinerja'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await AuthService().logout();
              Navigator.of(context).pushReplacement(
                MaterialPageRoute(builder: (_) => const LoginScreen()),
              );
            },
          ),
        ],
      ),
      body: GridView.count(
        crossAxisCount: 2,
        padding: const EdgeInsets.all(16),
        children: [
          _MenuCard(
            title: 'Perjanjian',
            icon: Icons.description,
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => const PerjanjianListScreen(),
                ),
              );
            },
          ),
          _MenuCard(
            title: 'Laporan',
            icon: Icons.assessment,
            onTap: () {
              // Navigate to Laporan
            },
          ),
          _MenuCard(
            title: 'Notifikasi',
            icon: Icons.notifications,
            onTap: () {
              // Navigate to Notifikasi
            },
          ),
          _MenuCard(
            title: 'Profile',
            icon: Icons.person,
            onTap: () {
              // Navigate to Profile
            },
          ),
        ],
      ),
    );
  }
}

class _MenuCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final VoidCallback onTap;

  const _MenuCard({
    required this.title,
    required this.icon,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 48, color: Theme.of(context).primaryColor),
            const SizedBox(height: 8),
            Text(
              title,
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
          ],
        ),
      ),
    );
  }
}
```

**File: `lib/screens/perjanjian/perjanjian_list_screen.dart`**
```dart
import 'package:flutter/material.dart';
import '../../services/api_service.dart';
import '../../models/perjanjian.dart';

class PerjanjianListScreen extends StatefulWidget {
  const PerjanjianListScreen({Key? key}) : super(key: key);

  @override
  State<PerjanjianListScreen> createState() => _PerjanjianListScreenState();
}

class _PerjanjianListScreenState extends State<PerjanjianListScreen> {
  final ApiService _apiService = ApiService();
  List<Perjanjian> _perjanjians = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadPerjanjians();
  }

  Future<void> _loadPerjanjians() async {
    setState(() => _isLoading = true);
    
    try {
      final response = await _apiService.dio.get('/perjanjian');
      
      if (response.data['success']) {
        List<dynamic> data = response.data['data'];
        setState(() {
          _perjanjians = data.map((e) => Perjanjian.fromJson(e)).toList();
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Daftar Perjanjian'),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _loadPerjanjians,
              child: ListView.builder(
                itemCount: _perjanjians.length,
                itemBuilder: (context, index) {
                  final perjanjian = _perjanjians[index];
                  return Card(
                    margin: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 8,
                    ),
                    child: ListTile(
                      title: Text(perjanjian.nomorPerjanjian),
                      subtitle: Text(
                        '${perjanjian.pihak2Name}\n${perjanjian.pihak2Jabatan}',
                      ),
                      trailing: Chip(
                        label: Text(perjanjian.status ?? 'Draft'),
                      ),
                      onTap: () {
                        // Navigate to detail
                      },
                    ),
                  );
                },
              ),
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // Navigate to create
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}
```

### STEP 5: Main Entry Point

**File: `lib/main.dart`**
```dart
import 'package:flutter/material.dart';
import 'config/theme.dart';
import 'services/auth_service.dart';
import 'screens/auth/login_screen.dart';
import 'screens/home/home_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Perjanjian Kinerja',
      theme: AppTheme.lightTheme,
      home: const SplashScreen(),
      debugShowCheckedModeBanner: false,
    );
  }
}

class SplashScreen extends StatefulWidget {
  const SplashScreen({Key? key}) : super(key: key);

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    await Future.delayed(const Duration(seconds: 2));
    
    bool isLoggedIn = await AuthService().isLoggedIn();
    
    if (mounted) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(
          builder: (_) => isLoggedIn ? const HomeScreen() : const LoginScreen(),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.description,
              size: 100,
              color: Theme.of(context).primaryColor,
            ),
            const SizedBox(height: 24),
            const Text(
              'Perjanjian Kinerja',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 48),
            const CircularProgressIndicator(),
          ],
        ),
      ),
    );
  }
}
```

---

## 🧪 Testing & Deployment

### Testing

```bash
# Run di emulator/device
flutter run

# Test di Chrome (web)
flutter run -d chrome

# Build APK untuk testing
flutter build apk --debug

# APK akan ada di: build/app/outputs/flutter-apk/app-debug.apk
```

### Production Build

```bash
# Build APK release
flutter build apk --release

# Build App Bundle (untuk Play Store)
flutter build appbundle --release

# Build iOS (Mac only)
flutter build ios --release
```

---

## 📝 Checklist Development

### Backend (Laravel)
- [ ] Install & config Laravel Sanctum
- [ ] Buat API routes di `routes/api.php`
- [ ] Buat AuthController (login, logout, user)
- [ ] Buat PerjanjianController (CRUD)
- [ ] Buat LaporanController (CRUD)
- [ ] Test semua endpoint dengan Postman
- [ ] Setup CORS untuk mobile

### Frontend (Flutter)
- [ ] Setup project Flutter
- [ ] Install dependencies
- [ ] Buat struktur folder
- [ ] Setup config (API URL, theme)
- [ ] Buat models (User, Perjanjian, Laporan)
- [ ] Buat services (API, Auth, Storage)
- [ ] Buat login screen
- [ ] Buat home screen
- [ ] Buat list perjanjian
- [ ] Buat detail perjanjian
- [ ] Buat create/edit perjanjian
- [ ] Buat print preview (PDF)
- [ ] Handle offline mode
- [ ] Testing di device

---

## 💡 Tips & Best Practices

1. **Gunakan Environment Variables**
   - Jangan hardcode API URL
   - Buat file `.env` untuk config

2. **Error Handling**
   - Selalu handle error dari API
   - Tampilkan pesan error yang user-friendly

3. **Loading States**
   - Tampilkan loading indicator saat fetch data
   - Disable button saat submit

4. **Offline Support**
   - Cache data dengan `sqflite` atau `hive`
   - Sync saat online kembali

5. **Security**
   - Simpan token di `flutter_secure_storage`
   - Validate input di frontend dan backend
   - Implement refresh token

---

## 🚀 Next Steps

1. **Implementasi fitur lengkap** (sesuai web)
2. **Tambah fitur mobile-specific** (push notification, biometric)
3. **Optimize performance**
4. **Testing menyeluruh**
5. **Deploy ke Play Store/App Store**

---

**Selamat Coding! 🎉**

Jika ada pertanyaan, silakan tanya di setiap step ya!
