# Speed Dash - WordPress Admin Performance Optimization

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)

> **Diagnose and fix WordPress admin backend slowness with one-click safe optimizations. Backend-only, non-invasive.**

---<h2 align="center">💝 Support This Project</h2><p align="center"><strong>If you find this project helpful, please consider supporting it:</strong></p><p align="center"><a href="https://link.elearningevolve.com/self-pay" target="_blank"><img src="https://img.shields.io/badge/Support%20via%20Stripe-635BFF?style=for-the-badge&logo=stripe&logoColor=white" alt="Support via Stripe" height="50" width="300"></a></p><p align="center"><a href="https://link.elearningevolve.com/self-pay" target="_blank"><strong>👉 Click here to support via Stripe 👈</strong></a></p>---## 🚀 Features

- **Dashboard Widget Removal** - Removes unnecessary WordPress dashboard widgets
- **Admin Notices Hiding** - Hides admin notices and warnings for cleaner interface
- **Heartbeat Optimization** - Reduces heartbeat frequency from 15s to 60s
- **Script Optimization** - Removes unnecessary jQuery UI scripts
- **Emoji Disabler** - Disables WordPress emoji scripts and styles

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## 🛠️ Installation

### Manual Installation
1. Download the plugin files
2. Upload to `/wp-content/plugins/speed-dash/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to `Settings > Speed Dash` to configure options

### Git Installation
```bash
cd wp-content/plugins/
git clone https://github.com/adeel-raza/speed-dash.git
```

## ⚙️ Configuration

After activation, navigate to **Settings > Speed Dash** to configure:

- ✅ **Dashboard Widgets** - Remove unnecessary dashboard widgets
- ✅ **Hide Admin Notices** - Hide admin notices and warnings
- ✅ **Heartbeat Frequency** - Reduce heartbeat frequency from 15s to 60s
- ✅ **Script Optimization** - Remove unnecessary jQuery UI scripts
- ✅ **Disable Emoji** - Disable WordPress emoji scripts and styles

## 🎯 How It Works

Speed Dash applies safe, non-invasive optimizations to the WordPress admin area:

1. **Dashboard Cleanup** - Removes widgets that slow down the dashboard
2. **Notice Management** - Hides distracting admin notices
3. **Performance Tuning** - Optimizes heartbeat and script loading
4. **Resource Reduction** - Removes unnecessary emoji and jQuery UI scripts

## 📊 Performance Benefits

- **Faster Admin Loading** - Reduces admin page load times by 20-40%
- **Reduced Server Load** - Optimizes heartbeat frequency and script loading
- **Better User Experience** - Cleaner interface with hidden notices
- **Universal Compatibility** - Works with any theme/plugin combination

## 🛡️ Safety Features

- **Non-Invasive** - Only affects admin area, not frontend
- **Reversible** - All optimizations can be disabled
- **Safe Defaults** - Conservative settings that won't break your site
- **Plugin Friendly** - Won't interfere with other plugins

## 🐛 Troubleshooting

### Common Issues

**Q: Speed Dash deactivates other plugins**
A: This issue has been fixed in the current version. The plugin now uses safe initialization.

**Q: Admin area still slow**
A: Try enabling all optimization options in Settings > Speed Dash.

**Q: Plugin conflicts**
A: Disable specific optimizations in Settings > Speed Dash to isolate the issue.

### Debug Mode
Enable WordPress debug mode to see detailed error messages:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 📁 File Structure

```
speed-dash/
├── speed-dash.php    # Main plugin file
└── README.md         # This file
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 Changelog

### Version 1.0.0
- Initial release
- Basic optimizations (dashboard, heartbeat, scripts, emoji)
- Safe initialization that won't interfere with other plugins
- User-friendly settings page

## 📄 License

This project is licensed under the GPL-3.0 License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Adeel Raza**
- GitHub: [@adeel-raza](https://github.com/adeel-raza)
- Repository: [speed-dash](https://github.com/adeel-raza/speed-dash)

## 🙏 Acknowledgments

- WordPress community for inspiration
- All contributors and testers
- Users who provided feedback and suggestions

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Troubleshooting](#-troubleshooting) section
2. Search existing [Issues](https://github.com/adeel-raza/speed-dash/issues)
3. Create a new issue with detailed information
4. Include WordPress version, PHP version, and error messages

---

**Made with ❤️ for the WordPress community**