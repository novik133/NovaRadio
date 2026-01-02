import React, { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { setupPlayer, playStream, togglePlayback, stopPlayback, updateMetadata, State } from '../services/player';
import TrackPlayer, { usePlaybackState, useProgress } from 'react-native-track-player';
import api from '../services/api';

const PlayerContext = createContext();

export function PlayerProvider({ children }) {
  const [isReady, setIsReady] = useState(false);
  const [currentStation, setCurrentStation] = useState(null);
  const [stations, setStations] = useState([]);
  const [nowPlaying, setNowPlaying] = useState(null);
  const [listeners, setListeners] = useState(0);
  const playbackState = usePlaybackState();

  const isPlaying = playbackState.state === State.Playing;
  const isBuffering = playbackState.state === State.Buffering;

  useEffect(() => {
    async function init() {
      await setupPlayer();
      const stationList = await api.getStations();
      if (stationList?.length) {
        setStations(stationList);
        setCurrentStation(stationList.find(s => s.is_default) || stationList[0]);
      }
      setIsReady(true);
    }
    init();
  }, []);

  useEffect(() => {
    if (!currentStation) return;
    
    const fetchNowPlaying = async () => {
      const data = await api.getNowPlaying(currentStation.id);
      if (data?.now_playing) {
        setNowPlaying(data.now_playing);
        setListeners(data.listeners?.current || 0);
        if (isPlaying) {
          updateMetadata({
            title: data.now_playing.song?.title || 'Live Stream',
            artist: data.now_playing.song?.artist || currentStation.name,
            artwork: data.now_playing.song?.art,
          });
        }
      }
    };

    fetchNowPlaying();
    const interval = setInterval(fetchNowPlaying, 15000);
    return () => clearInterval(interval);
  }, [currentStation, isPlaying]);

  const play = useCallback(async () => {
    if (!currentStation?.stream_url) return;
    await playStream(currentStation.stream_url, {
      title: nowPlaying?.song?.title || 'Live Stream',
      artist: nowPlaying?.song?.artist || currentStation.name,
      artwork: nowPlaying?.song?.art,
    });
  }, [currentStation, nowPlaying]);

  const toggle = useCallback(async () => {
    if (!isPlaying && !isBuffering) {
      await play();
    } else {
      await togglePlayback();
    }
  }, [isPlaying, isBuffering, play]);

  const stop = useCallback(async () => {
    await stopPlayback();
  }, []);

  const switchStation = useCallback(async (station) => {
    const wasPlaying = isPlaying;
    if (wasPlaying) await stopPlayback();
    setCurrentStation(station);
    setNowPlaying(null);
    if (wasPlaying) {
      setTimeout(() => playStream(station.stream_url, { artist: station.name }), 500);
    }
  }, [isPlaying]);

  return (
    <PlayerContext.Provider value={{
      isReady,
      isPlaying,
      isBuffering,
      currentStation,
      stations,
      nowPlaying,
      listeners,
      play,
      toggle,
      stop,
      switchStation,
    }}>
      {children}
    </PlayerContext.Provider>
  );
}

export const usePlayer = () => useContext(PlayerContext);
