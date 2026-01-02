import TrackPlayer, { Capability, Event, State } from 'react-native-track-player';

let isSetup = false;

export async function setupPlayer() {
  if (isSetup) return true;
  
  try {
    await TrackPlayer.setupPlayer({
      waitForBuffer: true,
    });
    
    await TrackPlayer.updateOptions({
      capabilities: [
        Capability.Play,
        Capability.Pause,
        Capability.Stop,
      ],
      compactCapabilities: [Capability.Play, Capability.Pause],
      notificationCapabilities: [Capability.Play, Capability.Pause],
    });
    
    isSetup = true;
    return true;
  } catch (error) {
    console.error('Player setup error:', error);
    return false;
  }
}

export async function playStream(streamUrl, metadata = {}) {
  await TrackPlayer.reset();
  await TrackPlayer.add({
    id: 'livestream',
    url: streamUrl,
    title: metadata.title || 'Live Stream',
    artist: metadata.artist || 'NovaRadio',
    artwork: metadata.artwork || undefined,
    isLiveStream: true,
  });
  await TrackPlayer.play();
}

export async function updateMetadata(metadata) {
  try {
    await TrackPlayer.updateNowPlayingMetadata({
      title: metadata.title || 'Live Stream',
      artist: metadata.artist || 'NovaRadio',
      artwork: metadata.artwork || undefined,
    });
  } catch (e) {}
}

export async function togglePlayback() {
  const state = await TrackPlayer.getState();
  if (state === State.Playing) {
    await TrackPlayer.pause();
  } else {
    await TrackPlayer.play();
  }
}

export async function stopPlayback() {
  await TrackPlayer.stop();
  await TrackPlayer.reset();
}

export { TrackPlayer, Event, State };
