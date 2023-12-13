// This code is currently configured for one weather sensor and one soil moisture sensor. 
// To accommodate additional sensors, modifications will be necessary.
// Author: Kamyar Karimi
// Date: 2023-12-13

#include "DHT.h"
#include <ESP8266WiFi.h>

// WiFi Credentials (NEEDS TO BE CHANGED)
const char* host = "kaamiiaar-iot.000webhostapp.com";
const char* ssid = "ADD YOUR SSID HERE";
const char* password = "ADD YOUR PASSWORD HERE";

// Temperature and Humidty Sensor (DHT11)
#define DPIN 2 //D4 on ESP-12F
#define DTYPE DHT11
DHT dht(DPIN, DTYPE);

// Soil Moisture Sensor (YL-69)
#define APIN A0 //A0 on ESP-12F

// Soil Moisture Thresholds
#define THRESH_MAX 800
#define THRESH_MIN 400

// Relay Module
#define RELAY_PIN 12

void setup() {
  Serial.begin(115200);
  delay(100);
  dht.begin();
  pinMode(RELAY_PIN, OUTPUT);
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("Netmask: ");
  Serial.println(WiFi.subnetMask());
  Serial.print("Gateway: ");
  Serial.println(WiFi.gatewayIP());  
}

void loop() {
  // Read sensor data
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  int m = analogRead(APIN);

  if (isnan(h) || isnan(t)){
    Serial.println("Failed to read from DHT sensor");
    return;
  }  

  // Connect to server
  Serial.print("connecting to ");
  Serial.println(host);

  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)){
    Serial.println("Connection failed");
    return;
  }

  // Send weather data to server
  String url =  "/api/weather/insert.php?temp="+String(t)+"&hum="+String(h)+"&moist="+String(m);

  Serial.print("Requesting URL ");
  Serial.println(url);

  client.print(String("GET ") + url+" HTTP/1.1\r\n"+
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");

  // Turn on/off relay based on soil moisture
  String url_switch;
  if (!manual_control){
    if (m > THRESH_MAX) {
        url_switch = "/api/switch/update.php?id=1&status='off'";
        digitalWrite(relayPin, LOW);
    } else if (m < THRESH_MIN) {
        url_switch = "/api/switch/update.php?id=1&status='on'";
        digitalWrite(relayPin, HIGH);
    }
  } else {
    if (manual_status == 'on') {
        url_switch = "/api/switch/update.php?id=1&status='on'";
        digitalWrite(relayPin, HIGH);        
    } else {
        url_switch = "/api/switch/update.php?id=1&status='off'";
        digitalWrite(relayPin, LOW);
    }
  }

  Serial.print("Requesting Switch URL ");
  Serial.println(url_switch);

  client.print(String("GET ") + url_switch+" HTTP/1.1\r\n"+
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");

  delay(500);

  while (client.available()){
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }
  
  Serial.println();
  Serial.println("closing connection");
  delay(3000);

}
