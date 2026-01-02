import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity } from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import api from '../services/api';
import { CONFIG } from '../config';

const { COLORS } = CONFIG;
const DAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

export default function ScheduleScreen() {
  const [schedule, setSchedule] = useState([]);
  const [selectedDay, setSelectedDay] = useState(new Date().getDay() || 7);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadSchedule();
  }, [selectedDay]);

  const loadSchedule = async () => {
    setLoading(true);
    const data = await api.getSchedule(selectedDay);
    if (Array.isArray(data)) setSchedule(data);
    setLoading(false);
  };

  const formatTime = (time) => {
    if (!time) return '';
    const [h, m] = time.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${hour12}:${m} ${ampm}`;
  };

  return (
    <View style={styles.container}>
      <Text style={styles.header}>Schedule</Text>

      {/* Day Tabs */}
      <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.tabs}>
        {DAYS.map((day, index) => (
          <TouchableOpacity
            key={day}
            style={[styles.tab, selectedDay === index + 1 && styles.tabActive]}
            onPress={() => setSelectedDay(index + 1)}
          >
            <Text style={[styles.tabText, selectedDay === index + 1 && styles.tabTextActive]}>
              {day}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Schedule List */}
      <ScrollView style={styles.list} showsVerticalScrollIndicator={false}>
        {loading ? (
          <Text style={styles.emptyText}>Loading...</Text>
        ) : schedule.length === 0 ? (
          <Text style={styles.emptyText}>No shows scheduled</Text>
        ) : (
          schedule.map((item, index) => (
            <View key={index} style={styles.item}>
              <View style={styles.timeContainer}>
                <Text style={styles.time}>{formatTime(item.start_time)}</Text>
                <Text style={styles.timeTo}>{formatTime(item.end_time)}</Text>
              </View>
              <View style={styles.itemInfo}>
                <Text style={styles.showName}>{item.show_name || 'TBA'}</Text>
                {item.dj_name && (
                  <View style={styles.djRow}>
                    <Icon name="person" size={12} color={COLORS.textMuted} />
                    <Text style={styles.djName}>{item.dj_name}</Text>
                  </View>
                )}
              </View>
            </View>
          ))
        )}
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
  header: {
    color: COLORS.text,
    fontSize: 28,
    fontWeight: '700',
    paddingHorizontal: 20,
    paddingTop: 60,
    paddingBottom: 20,
  },
  tabs: {
    paddingHorizontal: 16,
    marginBottom: 16,
  },
  tab: {
    paddingHorizontal: 18,
    paddingVertical: 10,
    backgroundColor: COLORS.surface,
    borderRadius: 20,
    marginRight: 8,
  },
  tabActive: {
    backgroundColor: COLORS.primary,
  },
  tabText: {
    color: COLORS.textMuted,
    fontWeight: '600',
  },
  tabTextActive: {
    color: '#fff',
  },
  list: {
    flex: 1,
    paddingHorizontal: 20,
  },
  item: {
    flexDirection: 'row',
    paddingVertical: 16,
    borderBottomWidth: 1,
    borderBottomColor: COLORS.border,
  },
  timeContainer: {
    width: 70,
  },
  time: {
    color: COLORS.primary,
    fontSize: 14,
    fontWeight: '600',
  },
  timeTo: {
    color: COLORS.textMuted,
    fontSize: 12,
    marginTop: 2,
  },
  itemInfo: {
    flex: 1,
    marginLeft: 16,
  },
  showName: {
    color: COLORS.text,
    fontSize: 16,
    fontWeight: '600',
  },
  djRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 4,
  },
  djName: {
    color: COLORS.textMuted,
    fontSize: 13,
    marginLeft: 6,
  },
  emptyText: {
    color: COLORS.textMuted,
    textAlign: 'center',
    marginTop: 40,
  },
});
