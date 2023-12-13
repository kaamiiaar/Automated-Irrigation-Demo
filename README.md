# Automated-Irrigation-Demo
A demo made for IoT Engineering position at Aglantis and Cisco-La Trobe Centre for AI and IoT

Main webpage: http://kaamiiaar-iot.000webhostapp.com/Demo/index.php

## Description
This project demonstrates an automated irrigation system that utilizes weather and soil moisture data from sensors connected to an ESP8266 board. The system captures environmental data, sends it to a cloud database, and automates irrigation based on soil moisture levels. The project integrates temperature, humidity, and soil moisture sensors, along with a relay module which can be connected to a pump or valve to initiate irrigation.


## Setup
<img width="895" alt="image" src="https://github.com/kaamiiaar/Automated-Irrigation-Demo/assets/47272408/231530da-c85f-4143-9c2a-87375ac0f2b5">

To set up the ESP8266 board with sensors:

- Hardware Assembly: Connect the temperature, humidity, and soil moisture sensors to the ESP8266 board. Attach the relay module for controlling the irrigation system. The relay will then be connected to a water pump/valve to initiate the irrigation.
- Software Setup: Flash the ESP8266 with the provided firmware. Ensure it is configured to connect to your Wi-Fi network.
- Database Connection: Set up the cloud database and ensure the ESP8266 board is programmed to send data to this database.

## Features
#### Data Collection
Leveraging ESP8266 for real-time data acquisition from various sensors.
#### Cloud Integration
Sending sensor data to a cloud-based database (000webhost.com is used as host to view the demo file).
#### Automated Irrigation 
Triggering irrigation processes when soil moisture falls below a set threshold.

## Usage
- Monitoring Data: Check the cloud database for real-time data from the sensors.
- Adjusting Thresholds: Modify the soil moisture threshold settings in the system's configuration to suit your specific irrigation needs.
- System Operation: The system will automatically initiate irrigation when soil moisture levels fall below the set threshold.
- Data Interpretation: Regularly review the environmental data to make informed decisions about irrigation schedules and potential system improvements.

## Potential for Improvement
Developed in one week, this demo has significant scope for enhancements.
#### To Do:
- Improve the arduino code to include multiple sensors and switches.
- Test the code and UI after adding the soil moisture sensor. Currently only tested on one temperature and humidity sensor (DHT11).
- Improve the security of API requests to prevent SQL injection and other vulnerabilities.
- Investigate the potential of integrating a LoRaWAN module onto the ESP8266-12F board. This is essential in agricultural settings.

## Contact
For more information, please contact kamyar1karimi@gmail.com.
