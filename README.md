# 🔊 Laravel Say Logger

[![Latest Version](https://img.shields.io/packagist/v/jordanpartridge/laravel-say-logger.svg)](https://packagist.org/packages/jordanpartridge/laravel-say-logger)
[![License](https://img.shields.io/packagist/l/jordanpartridge/laravel-say-logger.svg)](https://packagist.org/packages/jordanpartridge/laravel-say-logger)
[![PHP Version](https://img.shields.io/packagist/php-v/jordanpartridge/laravel-say-logger.svg)](https://packagist.org/packages/jordanpartridge/laravel-say-logger)

> **Never miss another error again!** 🎯

Laravel Say Logger brings **audio feedback** to your Laravel application by speaking your log messages using macOS text-to-speech. Different voices for different log levels mean you can **hear** the severity of issues without constantly checking log files.

Perfect for development environments where you want immediate audio feedback about your application's health.

## ✨ Features

- 🎤 **Text-to-Speech Integration** - Uses macOS `say` command
- 🎭 **Voice Differentiation** - Different voices for different log levels
- ⚡ **Async Processing** - Non-blocking, won't slow down your app
- 🔧 **Easy Configuration** - Simple config file setup
- 🎛️ **Toggle Control** - Enable/disable via environment variable
- 🧹 **Message Cleanup** - Strips HTML tags and normalizes whitespace
- 🛡️ **Error Handling** - Graceful fallback if speech fails

## 🚀 Installation

Install via Composer:

```bash
composer require jordanpartridge/laravel-say-logger
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="JordanPartridge\LaravelSayLogger\LaravelSayLoggerServiceProvider" --tag="config"
```

## ⚙️ Configuration

Add the `say` channel to your `config/logging.php`:

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'say'], // Add 'say' here
    ],
    
    // Add the say channel
    'say' => [
        'driver' => 'say',
    ],
],
```

Configure voices in `config/say-logger.php`:

```php
return [
    'enabled' => env('SAY_LOGGER_ENABLED', true),
    'voices' => [
        'debug' => 'Alex',
        'info' => 'Victoria',
        'notice' => 'Fred',
        'warning' => 'Kathy',
        'error' => 'Veena',
        'critical' => 'Moira',
        'alert' => 'Tessa',
        'emergency' => 'Kyoko',
    ],
];
```

### 🎵 Fun Voice Options

Want to make debugging more entertaining? Try these musical and novelty voices:

```php
'voices' => [
    'debug' => 'Bubbles',      // Fun bubbly voice
    'info' => 'Good News',     // Upbeat singing
    'notice' => 'Bells',       // Musical bells
    'warning' => 'Junior',     // Kid voice
    'error' => 'Cellos',       // Singing cellos
    'critical' => 'Bad News',  // Ominous singing
    'alert' => 'Trinoids',     // Alien voice
    'emergency' => 'Organ',    // Dramatic organ
],
```

**Available Voice Categories:**
- **Musical**: `Cellos`, `Organ`, `Bells`, `Good News`, `Bad News`
- **Novelty**: `Boing`, `Bubbles`, `Trinoids`, `Bahh`, `Whisper`
- **Character**: `Junior`, `Princess`, `Albert`, `Kathy`
- **Standard**: `Alex`, `Victoria`, `Daniel`, `Samantha`

See all available voices: `say -v '?'`

Test a voice: `say -v Cellos "Your error message here"`
```

## 🎭 Voice Examples

| Log Level | Voice | Character |
|-----------|-------|-----------|
| `debug` | Alex | Calm male voice |
| `info` | Victoria | Pleasant female voice |
| `warning` | Kathy | Concerned female voice |
| `error` | Veena | Urgent female voice |
| `critical` | Moira | Serious female voice |
| `emergency` | Kyoko | Alert female voice |

## 🎯 Usage

Once configured, your log messages will be automatically spoken:

```php
// These will be spoken by different voices
Log::debug('Cache cleared successfully');          // Alex
Log::info('User logged in successfully');          // Victoria  
Log::warning('Low disk space detected');           // Kathy
Log::error('Database connection failed');          // Veena
Log::critical('Payment processor is down');        // Moira
Log::emergency('Security breach detected');        // Kyoko
```

### Real-World Examples

```php
// Database errors
try {
    DB::connection()->getPdo();
} catch (Exception $e) {
    Log::error('Database connection failed: ' . $e->getMessage());
    // 🔊 Veena: "Database connection failed: SQLSTATE[HY000]..."
}

// API failures
if ($response->failed()) {
    Log::warning('Payment API is responding slowly');
    // 🔊 Kathy: "Payment API is responding slowly"
}

// System monitoring
if (disk_free_space('/') < 1000000) {
    Log::critical('Disk space critically low');
    // 🔊 Moira: "Disk space critically low"
}
```

## 🎛️ Environment Control

Control the package via environment variables:

```bash
# Enable/disable speech
SAY_LOGGER_ENABLED=true

# Or disable for production
SAY_LOGGER_ENABLED=false
```

## 🔧 Advanced Configuration

### Custom Voice Mapping

You can customize which voice speaks for each log level:

```php
'voices' => [
    'error' => 'Samantha',      // Use Samantha for errors
    'warning' => 'Daniel',      // Use Daniel for warnings
    'critical' => 'Fiona',      // Use Fiona for critical
    // ... etc
],
```

### Available macOS Voices

Check available voices on your system:

```bash
say -v "?"
```

Popular voices include: Alex, Victoria, Samantha, Daniel, Fiona, Karen, Moira, Tessa, Veena, Kyoko.

## 🛡️ Error Handling

The package includes robust error handling:

- **Graceful Fallback** - If `say` command fails, logging continues normally
- **Empty Message Protection** - Won't attempt to speak empty messages
- **Process Isolation** - Speech failures won't affect your application

## 🎯 Use Cases

### Development Environment
- **Immediate Error Feedback** - Hear problems as they happen
- **Hands-Free Debugging** - No need to constantly check logs
- **Severity Awareness** - Know how serious an issue is by the voice

### Demo Environments
- **Impressive Demonstrations** - Show clients real-time error handling
- **System Health Monitoring** - Audio feedback for system status

### Local Testing
- **Test Result Feedback** - Hear when tests fail
- **Performance Monitoring** - Audio alerts for slow queries

## 📋 Requirements

- **PHP**: ^8.1
- **Laravel**: ^10.0 || ^11.0
- **macOS**: Required for `say` command
- **Monolog**: ^3.0

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🎉 Credits

- **Jordan Partridge** - Creator and maintainer
- **Laravel Community** - For the amazing framework
- **Apple** - For the macOS `say` command

## 🔮 Future Ideas

- Cross-platform support (Windows SAPI, Linux espeak)
- Rate limiting for repeated messages
- Custom message filtering
- Volume control per log level
- Slack/Discord integration
- Web interface for configuration

---

**Made with ❤️ by [Jordan Partridge](https://partridge.rocks)**

*Never miss another error again!* 🎯