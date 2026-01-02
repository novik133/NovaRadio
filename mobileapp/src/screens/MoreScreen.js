import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity } from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import api from '../services/api';
import { CONFIG } from '../config';

const { COLORS } = CONFIG;

export default function MoreScreen({ navigation }) {
  const [shows, setShows] = useState([]);
  const [djs, setDjs] = useState([]);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    const [showsData, djsData] = await Promise.all([
      api.getShows(),
      api.getDJs(),
    ]);
    if (Array.isArray(showsData)) setShows(showsData.slice(0, 6));
    if (Array.isArray(djsData)) setDjs(djsData.slice(0, 6));
  };

  const MenuItem = ({ icon, title, onPress }) => (
    <TouchableOpacity style={styles.menuItem} onPress={onPress}>
      <View style={styles.menuIcon}>
        <Icon name={icon} size={22} color={COLORS.primary} />
      </View>
      <Text style={styles.menuTitle}>{title}</Text>
      <Icon name="chevron-forward" size={20} color={COLORS.textMuted} />
    </TouchableOpacity>
  );

  return (
    <ScrollView style={styles.container} showsVerticalScrollIndicator={false}>
      <Text style={styles.header}>More</Text>

      {/* Quick Links */}
      <View style={styles.section}>
        <MenuItem icon="calendar-outline" title="Schedule" onPress={() => navigation.navigate('Schedule')} />
        <MenuItem icon="musical-note-outline" title="Request Song" onPress={() => navigation.navigate('Request')} />
        <MenuItem icon="chatbubble-outline" title="Chat" onPress={() => {}} />
        <MenuItem icon="heart-outline" title="Dedications" onPress={() => {}} />
      </View>

      {/* Shows */}
      {shows.length > 0 && (
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Shows</Text>
          <ScrollView horizontal showsHorizontalScrollIndicator={false}>
            {shows.map(show => (
              <View key={show.id} style={styles.showCard}>
                <Image
                  source={show.image ? { uri: show.image } : require('../assets/placeholder.png')}
                  style={styles.showImage}
                />
                <Text style={styles.showName} numberOfLines={1}>{show.name}</Text>
                {show.genre && <Text style={styles.showGenre}>{show.genre}</Text>}
              </View>
            ))}
          </ScrollView>
        </View>
      )}

      {/* DJs */}
      {djs.length > 0 && (
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Our DJs</Text>
          <ScrollView horizontal showsHorizontalScrollIndicator={false}>
            {djs.map(dj => (
              <View key={dj.id} style={styles.djCard}>
                <Image
                  source={dj.image ? { uri: dj.image } : require('../assets/placeholder.png')}
                  style={styles.djImage}
                />
                <Text style={styles.djName}>{dj.name}</Text>
              </View>
            ))}
          </ScrollView>
        </View>
      )}

      {/* App Info */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>About</Text>
        <View style={styles.aboutCard}>
          <Text style={styles.appName}>{CONFIG.APP_NAME}</Text>
          <Text style={styles.appVersion}>Version 1.0.0</Text>
          <Text style={styles.appCopyright}>© 2026 NovaRadio CMS</Text>
        </View>
      </View>

      <View style={{ height: 120 }} />
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  header: {
    color: COLORS.text,
    fontSize: 28,
    fontWeight: '700',
    paddingHorizontal: 20,
    paddingTop: 60,
    paddingBottom: 20,
  },
  section: {
    marginBottom: 24,
  },
  sectionTitle: {
    color: COLORS.text,
    fontSize: 18,
    fontWeight: '700',
    paddingHorizontal: 20,
    marginBottom: 16,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: COLORS.border,
  },
  menuIcon: {
    width: 40,
    height: 40,
    borderRadius: 10,
    backgroundColor: COLORS.primary + '20',
    justifyContent: 'center',
    alignItems: 'center',
  },
  menuTitle: {
    flex: 1,
    color: COLORS.text,
    fontSize: 16,
    marginLeft: 14,
  },
  showCard: {
    width: 140,
    marginLeft: 20,
  },
  showImage: {
    width: 140,
    height: 140,
    borderRadius: 12,
    backgroundColor: COLORS.surface,
  },
  showName: {
    color: COLORS.text,
    fontSize: 14,
    fontWeight: '600',
    marginTop: 10,
  },
  showGenre: {
    color: COLORS.textMuted,
    fontSize: 12,
    marginTop: 2,
  },
  djCard: {
    alignItems: 'center',
    marginLeft: 20,
  },
  djImage: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: COLORS.surface,
  },
  djName: {
    color: COLORS.text,
    fontSize: 13,
    fontWeight: '500',
    marginTop: 8,
  },
  aboutCard: {
    marginHorizontal: 20,
    padding: 20,
    backgroundColor: COLORS.surface,
    borderRadius: 12,
    alignItems: 'center',
  },
  appName: {
    color: COLORS.primary,
    fontSize: 20,
    fontWeight: '700',
  },
  appVersion: {
    color: COLORS.textMuted,
    fontSize: 14,
    marginTop: 4,
  },
  appCopyright: {
    color: COLORS.textMuted,
    fontSize: 12,
    marginTop: 8,
  },
});
