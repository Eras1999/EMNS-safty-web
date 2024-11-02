function getWeather() {
    const apiKey = '5132304b77797700ce12204247536673'; // Your new API key
    const city1 = document.getElementById('city1').value;
    const city2 = document.getElementById('city2').value;

    if (!city1 || !city2) {
        alert('Please enter both cities');
        return;
    }

    const currentWeatherUrl1 = `https://api.openweathermap.org/data/2.5/weather?q=${city1}&appid=${apiKey}&units=metric`;
    const currentWeatherUrl2 = `https://api.openweathermap.org/data/2.5/weather?q=${city2}&appid=${apiKey}&units=metric`;
    const forecastUrl1 = `https://api.openweathermap.org/data/2.5/forecast?q=${city1}&appid=${apiKey}&units=metric`;
    const forecastUrl2 = `https://api.openweathermap.org/data/2.5/forecast?q=${city2}&appid=${apiKey}&units=metric`;

    Promise.all([
        fetch(currentWeatherUrl1),
        fetch(currentWeatherUrl2),
        fetch(forecastUrl1),
        fetch(forecastUrl2)
    ])
    .then(responses => Promise.all(responses.map(res => res.json())))
    .then(data => {
        const [weatherData1, weatherData2, forecastData1, forecastData2] = data;
        displayWeather(weatherData1, weatherData2);
        displayHourlyForecast(forecastData1.list, forecastData2.list);
    })
    .catch(error => {
        console.error('Error fetching weather data:', error);
        alert('Error fetching weather data. Please try again.');
    });
}

function displayWeather(data1, data2) {
    const tempDivInfo = document.getElementById('temp-div');
    const weatherInfoDiv = document.getElementById('weather-info');
    const weatherIcon = document.getElementById('weather-icon');
    const speedLimitDiv = document.getElementById('speed-limit');

    // Clear previous content
    weatherInfoDiv.innerHTML = '';
    tempDivInfo.innerHTML = '';

    if (data1.cod !== 200 || data2.cod !== 200) {
        weatherInfoDiv.innerHTML = `<p>Error fetching weather data.</p>`;
        return;
    }

    const cityName1 = data1.name;
    const temperature1 = Math.round(data1.main.temp); // Celsius
    const description1 = data1.weather[0].description;

    const cityName2 = data2.name;
    const temperature2 = Math.round(data2.main.temp); // Celsius
    const description2 = data2.weather[0].description;

    // Display temperatures
    tempDivInfo.innerHTML = `<p>${cityName1}: ${temperature1}°C</p><p>${cityName2}: ${temperature2}°C</p>`;
    weatherInfoDiv.innerHTML = `<p>${cityName1}: ${description1}</p><p>${cityName2}: ${description2}</p>`;

    // Determine speed limits
    const speedLimits = getSpeedLimits([description1, description2]);
    speedLimitDiv.innerHTML = `<p>Speed Limit: ${cityName1} - ${speedLimits[0]} km/h | ${cityName2} - ${speedLimits[1]} km/h</p>`;
}

function displayHourlyForecast(hourlyData1, hourlyData2) {
    const hourlyForecastDiv = document.getElementById('hourly-forecast');

    // Clear previous forecast
    hourlyForecastDiv.innerHTML = '';

    const next24Hours1 = hourlyData1.slice(0, 8); // Display the next 24 hours for city 1
    const next24Hours2 = hourlyData2.slice(0, 8); // Display the next 24 hours for city 2

    next24Hours1.forEach(item => {
        hourlyForecastDiv.innerHTML += createHourlyItem(item);
    });
    
    next24Hours2.forEach(item => {
        hourlyForecastDiv.innerHTML += createHourlyItem(item);
    });
}

function createHourlyItem(item) {
    const dateTime = new Date(item.dt * 1000); // Convert timestamp to milliseconds
    const hour = dateTime.getHours();
    const temperature = Math.round(item.main.temp); // Celsius
    const iconCode = item.weather[0].icon;
    const iconUrl = `https://openweathermap.org/img/wn/${iconCode}@2x.png`;

    return `
        <div class="hourly-item">
            <span>${hour}:00</span>
            <img src="${iconUrl}" alt="Hourly Weather Icon">
            <span>${temperature}°C</span>
        </div>
    `;
}

function getSpeedLimits(descriptions) {
    const speedLimits = [70, 50]; // Default speed limits
    descriptions.forEach((desc, index) => {
        if (desc.includes('rain')) {
            speedLimits[index] = 30; // Rainy speed limit
        } else if (desc.includes('cloud')) {
            speedLimits[index] = 50; // Cloudy speed limit
        } // Sunny is already set to 70
    });
    return speedLimits;
}
