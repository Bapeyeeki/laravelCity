<template>
  <div class="quiz-polska" :class="{ 'dark': isDarkMode }">
    <button id="toggleTheme" @click="toggleTheme">
      {{ isDarkMode ? 'Tryb jasny' : 'Tryb ciemny' }}
    </button>

    <h1>Jak dużo Polskich miast potrafisz nazwać?</h1>

    <input 
      id="city-input" 
      v-model="cityInput" 
      type="text" 
      class="city-input" 
      placeholder="Podaj nazwę miasta..." 
      autocomplete="off" 
      spellcheck="false" 
      enterkeyhint="done"
    >

    <div class="map-container">
      <div id="city-dots-container">
        <img src="/public/POL_location_map.svg" alt="Mapa Polski" id="map">
        <!-- Punkty miast będą generowane dynamicznie -->
        <div 
          v-for="(city, index) in cities" 
          :key="index"
          class="city-dot"
          :style="getDotStyle(city)"
          @mouseenter="showTooltip($event, city)"
          @mouseleave="hideTooltip"
        ></div>
      </div>
    </div>

    <div 
      id="tooltip" 
      class="tooltip" 
      v-show="tooltip.visible" 
      :style="{ left: tooltip.x + 'px', top: tooltip.y + 'px' }"
    >
      {{ tooltip.text }}
    </div>

    <div class="stats">
      <p class="text-center">
        Nazwałeś <span class="stat">{{ cityCount }} {{ getCityWordForm(cityCount) }}</span> 
        z łączną populacją <span class="stat">{{ formatNumber(totalPopulation) }}</span> 
        (<span class="stat">{{ populationPercent }}</span>% mieszkańców Polski).
      </p>
      <div class="buttons">
        <button id="clear-game" class="error" @click="clearGame">Wyczyść</button>
        <button id="finish-game" class="success" @click="finishGame">Zakończ i zapisz</button>
      </div>
    </div>

    <div id="end-screen" v-show="showEndScreen">
      <h2>Miasta, których nie odgadłeś:</h2>
      <ul id="missed-cities">
        <li v-for="(city, index) in missedCities" :key="index">
          {{ city.city }} ({{ formatNumber(city.populatio) }} mieszkańców)
        </li>
      </ul>
      <button @click="restartGame">Zagraj ponownie</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QuizPolska',
  data() {
    return {
      isDarkMode: false,
      cityInput: '',
      cities: [
        // Przykładowe dane miast dla celów demonstracyjnych
        { city: 'Warszawa', lat: 52.2297, lng: 21.0122, populatio: 1793579 },
        { city: 'Kraków', lat: 50.0647, lng: 19.9450, populatio: 779115 }
      ],
      tooltip: {
        visible: false,
        text: '',
        x: 0,
        y: 0
      },
      totalPolandPopulation: 38386000,
      showEndScreen: false,
      missedCities: [
        { city: 'Łódź', populatio: 682679 },
        { city: 'Wrocław', populatio: 641928 },
        { city: 'Poznań', populatio: 534813 }
      ]
    }
  },
  computed: {
    cityCount() {
      return this.cities.length;
    },
    totalPopulation() {
      return this.cities.reduce((sum, city) => sum + city.populatio, 0);
    },
    populationPercent() {
      return ((this.totalPopulation / this.totalPolandPopulation) * 100).toFixed(2);
    }
  },
  methods: {
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      localStorage.setItem('theme', this.isDarkMode ? 'dark' : 'light');
    },
    // Konwersja współrzędnych geograficznych na współrzędne mapy
    geoToMapCoords(lat, lon) {
      const map = document.getElementById('map');
      if (!map) return { x: 0, y: 0 }; // zabezpieczenie
      
      const mapWidth = map.offsetWidth;
      const mapHeight = map.offsetHeight;
      
      const minLat = 49.0;
      const maxLat = 54.9;
      const minLon = 14.1;
      const maxLon = 24.2;

      const latScale = (lat - minLat) / (maxLat - minLat);
      const lonScale = (lon - minLon) / (maxLon - minLon);

      const x = lonScale * mapWidth;
      const y = (1 - latScale) * mapHeight;

      return { x, y };
    },
    getDotStyle(city) {
      const { x, y } = this.geoToMapCoords(city.lat, city.lng);
      const dotSize = Math.max(10, Math.min(24, city.populatio / 40000));
      
      return {
        width: `${dotSize}px`,
        height: `${dotSize}px`,
        left: `${x - dotSize / 2}px`,
        top: `${y - dotSize / 2}px`,
        zIndex: `${1000 - Math.round(dotSize)}`
      };
    },
    showTooltip(event, city) {
      this.tooltip.text = `${city.city} – ${this.formatNumber(city.populatio)} mieszkańców`;
      this.tooltip.x = event.pageX + 10;
      this.tooltip.y = event.pageY - 30;
      this.tooltip.visible = true;
    },
    hideTooltip() {
      this.tooltip.visible = false;
    },
    getCityWordForm(count) {
      const lastDigit = count % 10;
      const lastTwoDigits = count % 100;

      if (count === 1) return 'miasto';
      if (lastDigit >= 2 && lastDigit <= 4 && !(lastTwoDigits >= 12 && lastTwoDigits <= 14)) {
        return 'miasta';
      }
      return 'miast';
    },
    formatNumber(number) {
      return number.toLocaleString();
    },
    clearGame() {
      this.cities = [];
      this.showEndScreen = false;
    },
    finishGame() {
      this.showEndScreen = true;
    },
    restartGame() {
      this.clearGame();
      this.cities = [
        { city: 'Warszawa', lat: 52.2297, lng: 21.0122, populatio: 1793579 },
        { city: 'Kraków', lat: 50.0647, lng: 19.9450, populatio: 779115 }
      ];
    }
  },
  mounted() {
    // Ładowanie zapisanego motywu
    const savedTheme = localStorage.getItem('theme') || 'light';
    this.isDarkMode = savedTheme === 'dark';
    
    // Obsługa zmiany rozmiaru okna
    window.addEventListener('resize', () => this.$forceUpdate());
  },
  beforeUnmount() {
    window.removeEventListener('resize', () => this.$forceUpdate());
  }
}
</script>

<style>
.quiz-polska {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  user-select: none;
  text-align: center;
  min-height: 100vh;
  padding-bottom: 40px;
}

.quiz-polska.dark {
  background-color: #2c2c2c;
  color: #ecf0f1;
}

h1 {
  margin-top: 20px;
  font-size: 24px;
  color: #333;
  padding: 0 20px;
}

.dark h1 {
  color: #ecf0f1;
}

.city-input {
  width: 300px;
  padding: 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid #ccc;
  outline: none;
  background-color: #fff;
  margin: 20px auto;
  display: block;
}

.city-input:focus {
  border-color: #3498db;
}

.dark .city-input {
  background-color: #2c2c2c;
  border: 1px solid #ecf0f1;
  color: #ecf0f1;
}

#toggleTheme {
  position: fixed;
  top: 10px; 
  right: 10px; 
  padding: 8px 16px; 
  background-color: #2c2c2c;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 12px;
  cursor: pointer;
  z-index: 1000;
}

.dark #toggleTheme {
  background-color: #f4f4f4;
  color: #2c2c2c; 
}

.map-container {
  width: 80%;
  max-width: 800px;
  margin: 40px auto;
  position: relative;
  border: 1px solid black;
  padding: 10px;
  background-color: white;
  overflow: hidden;
}

.dark .map-container {
  background-color: #2c2c2c;
  border: 1px solid #ecf0f1;
}

#city-dots-container {
  position: relative;
  width: 100%;
  aspect-ratio: 3/2;
}

#map {
  width: 100%;
  height: auto;
  max-height: 100%;
  max-width: 100%;
  display: block;
}

.city-dot {
  position: absolute;
  background-color: red;
  border-radius: 50%;
  pointer-events: auto;
  opacity: 0.6;
  transition: opacity 0.2s ease;
  z-index: 1;
  border: 1px solid black;
}

.city-dot:hover {
  opacity: 1;
  z-index: 5;
}

.tooltip {
  position: absolute;
  z-index: 9999;
  background-color: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 14px;
  pointer-events: none;
  white-space: nowrap;
}

.stats {
  width: 80%;
  max-width: 800px;
  margin: 20px auto;
  padding: 15px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.dark .stats {
  background-color: #3c3c3c;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
}

.stat {
  font-weight: bold;
  color: #3498db;
}

.dark .stat {
  color: #5dade2;
}

.buttons {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 10px;
}

button.success {
  background-color: darkblue;
  color: white;
  padding: 10px 16px;
  font-size: 14px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button.error {
  background-color: #b51c0c;
  color: white;
  padding: 10px 16px;
  font-size: 14px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button.success:hover {
  background-color: #18185a;
}

button.error:hover {
  background-color: #701107;
}

#end-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #f4f4f4;
  color: #333;
  padding: 40px;
  text-align: center;
  overflow-y: auto;
  z-index: 9999;
  box-sizing: border-box;
}

.dark #end-screen {
  background-color: #2c2c2c;
  color: #ecf0f1;
}

#end-screen button {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px;
}

.dark #end-screen button {
  background-color: #f39c12;
  color: #2c2c2c;
}

#missed-cities {
  max-height: 70vh;
  overflow-y: auto;
  list-style: none;
  padding: 0;
  font-size: 16px;
  margin: 20px auto;
  max-width: 600px;
  text-align: left;
}

#missed-cities li {
  padding: 5px 0;
  border-bottom: 1px solid #ddd;
}

.dark #missed-cities li {
  border-bottom: 1px solid #555;
}

@media (max-width: 768px) {
  .city-input {
    width: 80%;
    max-width: 300px;
  }
  
  h1 {
    font-size: 20px;
  }
  
  .map-container {
    width: 95%;
  }
  
  .stats {
    width: 90%;
    padding: 10px;
  }
}
</style>