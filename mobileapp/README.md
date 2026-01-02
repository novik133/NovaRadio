# NovaRadio Mobile App

Cross-platform mobile app for NovaRadio built with React Native.

## Features

- 🎵 Live streaming with background playback
- 📻 Multi-station support
- 🎨 Now Playing with album art
- 📜 Recently played history
- 📅 Schedule viewer
- 🎧 DJ profiles
- 💬 Song requests
- 🔔 Push notifications
- 🌙 Dark/Light theme
- advancement Car/Android Auto support

## Requirements

- Node.js 18+
- React Native CLI
- Xcode (for iOS)
- Android Studio (for Android)

## Setup

1. Install dependencies:
```bash
cd mobileapp
npm install
```

2. Configure API endpoint in `src/config.js`:
```javascript
export const API_URL = 'https://your-novaradio-site.com';
```

3. iOS setup:
```bash
cd ios && pod install && cd ..
```

4. Run the app:
```bash
# Android
npm run android

# iOS
npm run ios
```

## Building for Production

### Android
```bash
cd android
./gradlew assembleRelease
```
APK will be at `android/app/build/outputs/apk/release/`

### iOS
Open `ios/NovaRadio.xcworkspace` in Xcode and archive.

## Configuration

Edit `src/config.js` to set:
- `API_URL` - Your NovaRadio website URL
- `STREAM_URL` - Default stream URL (optional, fetched from API)
- `APP_NAME` - Display name
- `PRIMARY_COLOR` - Theme color

## License

GPL-3.0 - Part of NovaRadio CMS
