import React, { useState, useEffect } from 'react';
import {
  View, Text, StyleSheet, Image, TouchableOpacity, ScrollView,
  ActivityIndicator, Dimensions, StatusBar
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import { usePlayer } from '../context/PlayerContext';
import api from '../services/api';
import { CONFIG } from '../config';

const { COLORS } = CONFIG;
const { width } = Dimensions.get('window');

export default function HomeScreen({ navigation }) {
  const { isPlaying, isBuffering, nowPlaying, currentStation, stations, toggle, switchStation, listeners } = usePlayer();
  const [history, setHistory] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadHistory();
    const interval = setInterval(loadHistory, 30000);
    return () => clearInterval(interval);
  }, [currentStation]);

  const loadHistory = async () => {
    if (!currentStation) return;
    const data = await api.getHistory(currentStation.id, 10);
    if (Array.isArray(data)) setHistory(data);
    setLoading(false);
  };

  const artwork = nowPlaying?.song?.art;
  const title = nowPlaying?.song?.title || 'Loading...';
  const artist = nowPlaying?.song?.artist || currentStation?.name || '';

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor={COLORS.background} />
      
      <ScrollView showsVerticalScrollIndicator={false}>
        {/* Now Playing Hero */}
        <LinearGradient colors={[COLORS.primary + '40', COLORS.background]} style={styles.hero}>
          <View style={styles.artworkContainer}>
            <Image
              source={artwork ? { uri: artwork } : require('../assets/placeholder.png')}
              style={styles.artwork}
            />
            {isPlaying && (
              <View style={styles.liveIndicator}>
                <View style={styles.liveDot} />
                <Text style={styles.liveText}>LIVE</Text>
              </View>
            )}
          </View>

          <Text style={styles.title} numberOfLines={2}>{title}</Text>
          <Text style={styles.artist} numberOfLines={1}>{artist}</Text>

          <View style={styles.stats}>
            <Icon name="people" size={16} color={COLORS.textMuted} />
            <Text style={styles.statsText}>{listeners} listening</Text>
          </View>

          {/* Play Button */}
          <TouchableOpacity onPress={toggle} style={styles.playButton} activeOpacity={0.8}>
            <LinearGradient colors={[COLORS.primary, COLORS.primaryDark]} style={styles.playButtonGradient}>
              {isBuffering ? (
                <ActivityIndicator color="#fff" size="large" />
              ) : (
                <Icon name={isPlaying ? 'pause' : 'play'} size={40} color="#fff" />
              )}
            </LinearGradient>
          </TouchableOpacity>
        </LinearGradient>

        {/* Station Selector */}
        {stations.length > 1 && (
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Stations</Text>
            <ScrollView horizontal showsHorizontalScrollIndicator={false}>
              {stations.map(station => (
                <TouchableOpacity
                  key={station.id}
                  style={[styles.stationChip, currentStation?.id === station.id && styles.stationChipActive]}
                  onPress={() => switchStation(station)}
                >
                  <Text style={[styles.stationChipText, currentStation?.id === station.id && styles.stationChipTextActive]}>
                    {station.name}
                  </Text>
                </TouchableOpacity>
              ))}
            </ScrollView>
          </View>
        )}

        {/* Recently Played */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Recently Played</Text>
          {loading ? (
            <ActivityIndicator color={COLORS.primary} style={{ marginTop: 20 }} />
          ) : history.length === 0 ? (
            <Text style={styles.emptyText}>No history available</Text>
          ) : (
            history.map((item, index) => (
              <View key={index} style={styles.historyItem}>
                <Image
                  source={item.song?.art ? { uri: item.song.art } : require('../assets/placeholder.png')}
                  style={styles.historyArt}
                />
                <View style={styles.historyInfo}>
                  <Text style={styles.historyTitle} numberOfLines={1}>
                    {item.song?.title || item.title || 'Unknown'}
                  </Text>
                  <Text style={styles.historyArtist} numberOfLines={1}>
                    {item.song?.artist || item.artist || 'Unknown'}
                  </Text>
                </View>
                <Text style={styles.historyTime}>
                  {item.played_at ? new Date(item.played_at * 1000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}
                </Text>
              </View>
            ))
          )}
        </View>

        <View style={{ height: 100 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  hero: {
    alignItems: 'center',
    paddingTop: 60,
    paddingBottom: 40,
    paddingHorizontal: 20,
  },
  artworkContainer: {
    position: 'relative',
  },
  artwork: {
    width: width * 0.6,
    height: width * 0.6,
    borderRadius: 20,
    backgroundColor: COLORS.surface,
  },
  liveIndicator: {
    position: 'absolute',
    top: 12,
    right: 12,
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.accent,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
  },
  liveDot: {
    width: 6,
    height: 6,
    borderRadius: 3,
    backgroundColor: '#fff',
    marginRight: 6,
  },
  liveText: {
    color: '#fff',
    fontSize: 11,
    fontWeight: '700',
  },
  title: {
    color: COLORS.text,
    fontSize: 24,
    fontWeight: '700',
    marginTop: 24,
    textAlign: 'center',
  },
  artist: {
    color: COLORS.textMuted,
    fontSize: 16,
    marginTop: 8,
  },
  stats: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 12,
  },
  statsText: {
    color: COLORS.textMuted,
    fontSize: 14,
    marginLeft: 6,
  },
  playButton: {
    marginTop: 24,
  },
  playButtonGradient: {
    width: 72,
    height: 72,
    borderRadius: 36,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: COLORS.primary,
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.4,
    shadowRadius: 16,
    elevation: 8,
  },
  section: {
    paddingHorizontal: 20,
    marginTop: 24,
  },
  sectionTitle: {
    color: COLORS.text,
    fontSize: 18,
    fontWeight: '700',
    marginBottom: 16,
  },
  stationChip: {
    paddingHorizontal: 20,
    paddingVertical: 10,
    backgroundColor: COLORS.surface,
    borderRadius: 20,
    marginRight: 10,
    borderWidth: 1,
    borderColor: COLORS.border,
  },
  stationChipActive: {
    backgroundColor: COLORS.primary,
    borderColor: COLORS.primary,
  },
  stationChipText: {
    color: COLORS.textMuted,
    fontWeight: '600',
  },
  stationChipTextActive: {
    color: '#fff',
  },
  historyItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: COLORS.border,
  },
  historyArt: {
    width: 48,
    height: 48,
    borderRadius: 8,
    backgroundColor: COLORS.surface,
  },
  historyInfo: {
    flex: 1,
    marginLeft: 12,
  },
  historyTitle: {
    color: COLORS.text,
    fontSize: 15,
    fontWeight: '500',
  },
  historyArtist: {
    color: COLORS.textMuted,
    fontSize: 13,
    marginTop: 2,
  },
  historyTime: {
    color: COLORS.textMuted,
    fontSize: 12,
  },
  emptyText: {
    color: COLORS.textMuted,
    textAlign: 'center',
    marginTop: 20,
  },
});
