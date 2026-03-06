document.addEventListener("DOMContentLoaded", init);

// Use custom PHP backend API
const API_URL = "https://sijanweather.lovestoblog.com/prototype2/connection.php?q=";

// DOM element references
const elements = {
    searchBox: document.querySelector(".search input"),
    searchBtn: document.querySelector(".search button"),
    city: document.querySelector(".city"),
    temp: document.querySelector(".temp"),
    humidity: document.querySelector(".humidity"),
    wind: document.querySelector(".wind"),
    pressure: document.querySelector(".pressure"),
    time: document.querySelector(".time")
};

// Initialize app with default city and event listeners
function init() {
    const defaultCity = "Enterprise"; // default city
    fetchWeather(defaultCity);
    elements.searchBtn.addEventListener("click", handleSearch);
    elements.searchBox.addEventListener("keypress", event => {
        if (event.key === "Enter") {
            handleSearch();
        }
    });
}

// Handle search action
function handleSearch() {
    const city = elements.searchBox.value.trim();
    if (city) {
        fetchWeather(city);
    }
}

// Main weather fetch function (with online/offline logic)
async function fetchWeather(city) {
    let data;

    if (navigator.onLine) {
        // Online: Fetch from server
        try {
            const response = await fetch(`${API_URL}${encodeURIComponent(city)}`);
            if (!response.ok) {
                throw new Error("City not found");
            }

            data = await response.json();
            data = data[0]; // PHP returns array of one item

            // Save to localStorage
            localStorage.setItem(city.toLowerCase(), JSON.stringify(data));

            updateDisplay(data);
        } catch (err) {
            showError("Error: City not found");
        }
    } else {
        // Offline: Load from localStorage
        const cachedData = localStorage.getItem(city.toLowerCase());

        if (cachedData) {
            data = JSON.parse(cachedData);
            updateDisplay(data);
        } else {
            showError("Offline: No cached data found.");
        }
    }
}

// Update UI with weather data
function updateDisplay(data) {
    elements.city.textContent = data.city;
    elements.temp.textContent = `${Math.round(parseFloat(data.temperature))}°C`;
    elements.humidity.textContent = `${data.humidity}%`;
    elements.wind.textContent = `${data.wind} km/h`;
    elements.pressure.textContent = `${data.pressure} hPa`;

    // Convert timezone to local time
    const timezoneOffset = data.timezone;
    const localTime = new Date(Date.now() + timezoneOffset * 1000);
    const hours = localTime.getUTCHours().toString().padStart(2, '0');
    const minutes = localTime.getUTCMinutes().toString().padStart(2, '0');
    elements.time.textContent = `Local Time: ${hours}:${minutes}`;
}

// Show error message
function showError(message = "Error: City not found") {
    elements.city.textContent = message;
    elements.temp.textContent = "--";
    elements.humidity.textContent = "--";
    elements.wind.textContent = "--";
    elements.pressure.textContent = "--";
    elements.time.textContent = "Local Time: --:--";
}
