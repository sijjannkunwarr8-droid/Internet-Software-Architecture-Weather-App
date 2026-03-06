# 4CS017-Weather-App

**Academic Context:** * **Module**: 4CS017/HJ2: Internet Software Architecture and Databases
* **Institution**: Herald College, Kathmandu, Nepal 

## Overview
This is a browser-based weather application built with HTML, CSS, JavaScript, and PHP. It provides current weather information for any city the user inputs by fetching real-time data from the OpenWeatherMap API. The application features a clean, responsive design that adapts seamlessly to various screen sizes, ensuring a great user experience on both desktop and mobile devices.

## Features
* **Real-Time Data**: Displays temperature, humidity, air pressure, wind speed, sunrise time, sunset time, and a brief weather description.
* **Responsive UI (Adaptive Design)**: Utilizes CSS media queries to adjust the layout for different screen sizes and devices.
* **Offline Functionality**: Caches weather information in the browser's `localStorage`, allowing users to view previously searched city data even when offline.
* **Database Caching**: Uses a PHP backend to interact with a MySQL database. It saves and retrieves weather records instantly, reducing redundant API calls and ensuring faster load times for recently searched cities.
* **Clean and Simple UI**: Features a minimalistic aesthetic with custom icons and a soft-focus background.
* **Error Handling**: Displays user-friendly error messages for invalid city inputs or when the API encounters an issue.

## Technologies Used
* **Frontend**: HTML, CSS, JavaScript
* **Backend**: PHP
* **Database**: MySQL
* **External API**: OpenWeatherMap API

## Links & Demos
* **Live Application**: [Weather App Prototype](https://sijanweather.lovestoblog.com/prototype2/weather.html)
* **Video Demonstration**: [YouTube Demo](https://www.youtube.com/watch?v=BcLKpJGSKtg)

## Known Limitations & Future Improvements
* **Security Flaws**: The current PHP script uses `mysqli_real_escape_string` but lacks prepared statements, leaving it vulnerable to SQL injection. 
* **Exposed API Key**: The OpenWeatherMap API key is currently hardcoded into the PHP script, which is a security risk.
* **API Dependency**: The application is entirely reliant on the OpenWeatherMap API; service downtime will temporarily break the live fetching functionality.
* **Generic Error Messages**: Error alerts could be made more descriptive to help the user understand exactly what went wrong.
* **Basic Offline Mode**: The offline capability is limited strictly to viewing previously stored data states, without broader offline update facilities.

## Author
* **Submitted by**: Sijan Kunwar
* **University ID**: 2551483
