import React from 'react';
import { View, StyleSheet } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import Icon from 'react-native-vector-icons/Ionicons';

import { PlayerProvider } from './context/PlayerContext';
import MiniPlayer from './components/MiniPlayer';
import HomeScreen from './screens/HomeScreen';
import ScheduleScreen from './screens/ScheduleScreen';
import RequestScreen from './screens/RequestScreen';
import MoreScreen from './screens/MoreScreen';
import { CONFIG } from './config';

const { COLORS } = CONFIG;
const Tab = createBottomTabNavigator();

function TabNavigator() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarStyle: {
          backgroundColor: COLORS.card,
          borderTopColor: COLORS.border,
          height: 60,
          paddingBottom: 8,
          paddingTop: 8,
        },
        tabBarActiveTintColor: COLORS.primary,
        tabBarInactiveTintColor: COLORS.textMuted,
        tabBarIcon: ({ focused, color, size }) => {
          let iconName;
          switch (route.name) {
            case 'Home': iconName = focused ? 'radio' : 'radio-outline'; break;
            case 'Schedule': iconName = focused ? 'calendar' : 'calendar-outline'; break;
            case 'Request': iconName = focused ? 'musical-notes' : 'musical-notes-outline'; break;
            case 'More': iconName = focused ? 'menu' : 'menu-outline'; break;
          }
          return <Icon name={iconName} size={24} color={color} />;
        },
      })}
    >
      <Tab.Screen name="Home" component={HomeScreen} />
      <Tab.Screen name="Schedule" component={ScheduleScreen} />
      <Tab.Screen name="Request" component={RequestScreen} />
      <Tab.Screen name="More" component={MoreScreen} />
    </Tab.Navigator>
  );
}

export default function App() {
  return (
    <PlayerProvider>
      <NavigationContainer>
        <View style={styles.container}>
          <TabNavigator />
          <MiniPlayer />
        </View>
      </NavigationContainer>
    </PlayerProvider>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
});
