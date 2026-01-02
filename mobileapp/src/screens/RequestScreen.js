import React, { useState } from 'react';
import {
  View, Text, StyleSheet, TextInput, TouchableOpacity,
  KeyboardAvoidingView, Platform, Alert, ScrollView
} from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import api from '../services/api';
import { CONFIG } from '../config';

const { COLORS } = CONFIG;

export default function RequestScreen() {
  const [artist, setArtist] = useState('');
  const [title, setTitle] = useState('');
  const [name, setName] = useState('');
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async () => {
    if (!artist.trim() || !title.trim()) {
      Alert.alert('Error', 'Please enter artist and song title');
      return;
    }

    setLoading(true);
    const result = await api.submitRequest({ artist, title, name, message });
    setLoading(false);

    if (result?.success) {
      Alert.alert('Success', 'Your request has been submitted!');
      setArtist('');
      setTitle('');
      setMessage('');
    } else {
      Alert.alert('Error', result?.error || 'Failed to submit request');
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <ScrollView showsVerticalScrollIndicator={false}>
        <Text style={styles.header}>Request a Song</Text>
        <Text style={styles.subtitle}>
          Want to hear your favorite track? Submit a request!
        </Text>

        <View style={styles.form}>
          <View style={styles.inputGroup}>
            <Text style={styles.label}>Artist *</Text>
            <View style={styles.inputContainer}>
              <Icon name="person-outline" size={20} color={COLORS.textMuted} />
              <TextInput
                style={styles.input}
                placeholder="e.g. Daft Punk"
                placeholderTextColor={COLORS.textMuted}
                value={artist}
                onChangeText={setArtist}
              />
            </View>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Song Title *</Text>
            <View style={styles.inputContainer}>
              <Icon name="musical-notes-outline" size={20} color={COLORS.textMuted} />
              <TextInput
                style={styles.input}
                placeholder="e.g. Around the World"
                placeholderTextColor={COLORS.textMuted}
                value={title}
                onChangeText={setTitle}
              />
            </View>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Your Name</Text>
            <View style={styles.inputContainer}>
              <Icon name="happy-outline" size={20} color={COLORS.textMuted} />
              <TextInput
                style={styles.input}
                placeholder="Anonymous"
                placeholderTextColor={COLORS.textMuted}
                value={name}
                onChangeText={setName}
              />
            </View>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Message / Shoutout</Text>
            <View style={[styles.inputContainer, styles.textareaContainer]}>
              <TextInput
                style={[styles.input, styles.textarea]}
                placeholder="Add a dedication..."
                placeholderTextColor={COLORS.textMuted}
                value={message}
                onChangeText={setMessage}
                multiline
                numberOfLines={3}
              />
            </View>
          </View>

          <TouchableOpacity
            style={[styles.button, loading && styles.buttonDisabled]}
            onPress={handleSubmit}
            disabled={loading}
          >
            <Icon name="send" size={20} color="#fff" />
            <Text style={styles.buttonText}>
              {loading ? 'Submitting...' : 'Submit Request'}
            </Text>
          </TouchableOpacity>
        </View>

        <View style={styles.tips}>
          <Text style={styles.tipsTitle}>Tips</Text>
          <Text style={styles.tip}>• Requests are reviewed by our DJs</Text>
          <Text style={styles.tip}>• Popular songs have higher chances</Text>
          <Text style={styles.tip}>• Keep it family-friendly</Text>
        </View>

        <View style={{ height: 100 }} />
      </ScrollView>
    </KeyboardAvoidingView>
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
  },
  subtitle: {
    color: COLORS.textMuted,
    fontSize: 15,
    paddingHorizontal: 20,
    marginTop: 8,
    marginBottom: 24,
  },
  form: {
    paddingHorizontal: 20,
  },
  inputGroup: {
    marginBottom: 20,
  },
  label: {
    color: COLORS.text,
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 8,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.surface,
    borderRadius: 12,
    paddingHorizontal: 16,
    borderWidth: 1,
    borderColor: COLORS.border,
  },
  input: {
    flex: 1,
    color: COLORS.text,
    fontSize: 16,
    paddingVertical: 14,
    marginLeft: 12,
  },
  textareaContainer: {
    alignItems: 'flex-start',
    paddingTop: 12,
  },
  textarea: {
    height: 80,
    textAlignVertical: 'top',
    marginLeft: 0,
  },
  button: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: COLORS.primary,
    paddingVertical: 16,
    borderRadius: 12,
    marginTop: 8,
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
    marginLeft: 8,
  },
  tips: {
    marginTop: 32,
    paddingHorizontal: 20,
    padding: 20,
    backgroundColor: COLORS.surface,
    marginHorizontal: 20,
    borderRadius: 12,
  },
  tipsTitle: {
    color: COLORS.text,
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 12,
  },
  tip: {
    color: COLORS.textMuted,
    fontSize: 14,
    marginBottom: 6,
  },
});
