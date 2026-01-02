# iOS Setup for NovaRadio

## Prerequisites
- macOS
- Xcode 15+
- CocoaPods

## Setup

1. Install pods:
```bash
cd ios
pod install
```

2. Open `NovaRadio.xcworkspace` in Xcode

## Build & Run

1. Select your target device/simulator
2. Press Cmd+R or click Run

## Release Build

1. In Xcode: Product → Archive
2. Distribute App → App Store Connect (or Ad Hoc)

## Customization

- App icon: Assets.xcassets → AppIcon
- App name: Edit `Info.plist` → Bundle display name
- Bundle ID: Project settings → General → Bundle Identifier

## Background Audio

Already configured in `Info.plist`:
- UIBackgroundModes: audio

## Permissions

Add to `Info.plist` if needed:
- NSMicrophoneUsageDescription (for voice messages)
- NSPhotoLibraryUsageDescription (for profile photos)
