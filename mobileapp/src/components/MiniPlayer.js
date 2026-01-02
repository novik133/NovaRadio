import React from 'react';
import { View, Text, TouchableOpacity, Image, StyleSheet, ActivityIndicator } from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import { usePlayer } from '../context/PlayerContext';
import { CONFIG } from '../config';

const { COLORS } = CONFIG;

export default function MiniPlayer({ onPress }) {
  const { isPlaying, isBuffering, nowPlaying, currentStation, toggle, listeners } = usePlayer();

  const artwork = nowPlaying?.song?.art;
  const title = nowPlaying?.song?.title || 'Tap to play';
  const artist = nowPlaying?.song?.artist || currentStation?.name || 'NovaRadio';

  return (
    <TouchableOpacity onPress={onPress} activeOpacity={0.9}>
      <LinearGradient
        colors={[COLORS.surface, COLORS.card]}
        style={styles.container}
      >
        <Image
          source={artwork ? { uri: artwork } : require('../assets/placeholder.png')}
          style={styles.artwork}
        />
        
        <View style={styles.info}>
          <Text style={styles.title} numberOfLines={1}>{title}</Text>
          <Text style={styles.artist} numberOfLines={1}>{artist}</Text>
        </View>

        <View style={styles.listeners}>
          <Icon name="people" size={14} color={COLORS.textMuted} />
          <Text style={styles.listenersText}>{listeners}</Text>
        </View>

        <TouchableOpacity onPress={toggle} style={styles.playButton}>
          {isBuffering ? (
            <ActivityIndicator color="#fff" size="small" />
          ) : (
            <Icon name={isPlaying ? 'pause' : 'play'} size={28} color="#fff" />
          )}
        </TouchableOpacity>
      </LinearGradient>
    </TouchableOpacity>
  );
}

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    paddingHorizontal: 16,
    borderTopWidth: 1,
    borderTopColor: COLORS.border,
  },
  artwork: {
    width: 48,
    height: 48,
    borderRadius: 8,
    backgroundColor: COLORS.surface,
  },
  info: {
    flex: 1,
    marginLeft: 12,
  },
  title: {
    color: COLORS.text,
    fontSize: 15,
    fontWeight: '600',
  },
  artist: {
    color: COLORS.textMuted,
    fontSize: 13,
    marginTop: 2,
  },
  listeners: {
    flexDirection: 'row',
    alignItems: 'center',
    marginRight: 16,
  },
  listenersText: {
    color: COLORS.textMuted,
    fontSize: 12,
    marginLeft: 4,
  },
  playButton: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: COLORS.primary,
    justifyContent: 'center',
    alignItems: 'center',
  },
});
