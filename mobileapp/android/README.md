# Android Setup for NovaRadio

## Prerequisites
- Android Studio
- JDK 17
- Android SDK (API 34)

## Setup

1. Open Android Studio
2. Open the `android` folder as a project
3. Let Gradle sync

## Build Debug APK
```bash
cd android
./gradlew assembleDebug
```

## Build Release APK
```bash
cd android
./gradlew assembleRelease
```

APK location: `android/app/build/outputs/apk/`

## Signing for Release

1. Generate keystore:
```bash
keytool -genkeypair -v -storetype PKCS12 -keystore novaradio.keystore -alias novaradio -keyalg RSA -keysize 2048 -validity 10000
```

2. Create `android/gradle.properties`:
```
MYAPP_UPLOAD_STORE_FILE=novaradio.keystore
MYAPP_UPLOAD_KEY_ALIAS=novaradio
MYAPP_UPLOAD_STORE_PASSWORD=*****
MYAPP_UPLOAD_KEY_PASSWORD=*****
```

3. Update `android/app/build.gradle` signingConfigs

## Customization

- App icon: Replace files in `android/app/src/main/res/mipmap-*`
- App name: Edit `android/app/src/main/res/values/strings.xml`
- Package name: Update in `android/app/build.gradle`
