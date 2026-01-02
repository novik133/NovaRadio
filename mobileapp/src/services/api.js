import { CONFIG } from './config';

const { API_URL } = CONFIG;

class ApiService {
  async fetch(endpoint) {
    try {
      const response = await fetch(`${API_URL}/api.php?action=${endpoint}`);
      return await response.json();
    } catch (error) {
      console.error(`API Error (${endpoint}):`, error);
      return null;
    }
  }

  async getNowPlaying(stationId) {
    const endpoint = stationId ? `nowplaying&station=${stationId}` : 'nowplaying';
    return this.fetch(endpoint);
  }

  async getStations() {
    return this.fetch('stations');
  }

  async getHistory(stationId, limit = 20) {
    const endpoint = stationId ? `history&station=${stationId}&limit=${limit}` : `history&limit=${limit}`;
    return this.fetch(endpoint);
  }

  async getSchedule(day) {
    const endpoint = day ? `schedule&day=${day}` : 'schedule';
    return this.fetch(endpoint);
  }

  async getShows() {
    return this.fetch('shows');
  }

  async getDJs() {
    return this.fetch('djs');
  }

  async getEvents() {
    return this.fetch('events');
  }

  async getStats() {
    return this.fetch('stats');
  }

  async submitRequest(data) {
    try {
      const response = await fetch(`${API_URL}/ajax.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=song_request&artist=${encodeURIComponent(data.artist)}&title=${encodeURIComponent(data.title)}&name=${encodeURIComponent(data.name || 'App User')}&message=${encodeURIComponent(data.message || '')}`,
      });
      return await response.json();
    } catch (error) {
      return { error: 'Request failed' };
    }
  }
}

export default new ApiService();
