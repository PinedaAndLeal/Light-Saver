#include <ESP8266WiFi.h>
#include <PubSubClient.h>

//variables 
const char* ssid = "ARRIS-1C42";
const char* password = "7343F5EBD37926A6";
const char* mqtt_broker = "broker.hivemq.com";
int LED = 2; //led        
int SENSOR_OUTPUT_PIN = 5; //sensor PIR 
int ADCin;
float Vin;
float ToC;

WiFiClient espClient;
PubSubClient client(espClient);
unsigned long lastMsg = 0;
char OutTopic[] = "http://localhost:8080/api/index.php?data=saver"; //topic API
char InTopic[] = "http://localhost:8080/api/index.php?data=saver"; //topic API
int Ts = 5000; //milliseconds sampling rate   
 
void setup_wifi() {
  delay(10);
  // We start by connecting to a Wifi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  randomSeed(micros());
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address");
  Serial.println(WiFi.localIP());
}

void callback(char* topic, byte* payload, unsigned int length) {
  Serial.print("Message arrived [");
  Serial.print(topic);
  Serial.print("] ");
  for (int i = 0; i < length; i++) {
    Serial.print((char)payload[i]);
  }
  Serial.println();
  // Switch on the LED if an 1 was received as first character
  if ((char)payload[0] == '1') {
    digitalWrite(BUILTIN_LED, LOW); // Turn the LED on (Note that LOW is the voltage level
    // but actually the LED is on
  } else {
    digitalWrite(BUILTIN_LED, HIGH); // Turn the LED off by making the voltage HIGH
  }
}

void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    String clientId = "NMCU-319122732";
    // Attempt to connect
    if (client.connect(clientId.c_str())) {
      Serial.println("connected");
      client.subscribe(InTopic);
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      delay(2000); // Wait 2 seconds before retrying 
    }
  }
}

void setup() {
  pinMode(BUILTIN_LED, OUTPUT); // Initialize the BUILTIN_LED pin as an output
  pinMode(SENSOR_OUTPUT_PIN, INPUT);  
  pinMode(LED, OUTPUT);  
  Serial.begin(74880);
  setup_wifi();
  client.setServer(mqtt_broker, 1883);
  client.setCallback(callback);
}

void loop() {
  //reads the PIR sensor
  int sensorvalue = digitalRead(SENSOR_OUTPUT_PIN);
  Serial.println(sensorvalue);
  if (sensorvalue== HIGH) {
   digitalWrite(LED, HIGH);
    delay(1000);
  }
  else {
    digitalWrite(LED, LOW);
  }
  
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  unsigned long now = millis();
  if (now - lastMsg > Ts) {
    //n
    int n = n+1;
    //temperature
    
	ADCin = analogRead(A0); //10 bit digital conversion
	Vin = 5.0*ADCin / 1023.0; //Converting to voltage
	ToC = 100 * Vin;
    //motion and illumination
    String motion = "ON";
    String illumination = "ON";
    String JSON_msg = "{\"n\":" + String(n,2);
    JSON_msg += ",\"t\":" + String(ToC,2); 
    JSON_msg += ",\"motion\":" + String(motion);
    JSON_msg += ",\"illumination\":" + String(illumination) + "}";
    Serial.print("Publish message: ");
    Serial.println(JSON_msg);
    // MQTT Publish to topic
    char JSON_msg_array[60];
    int JSON_msg_length = JSON_msg.length();
    JSON_msg.toCharArray(JSON_msg_array, 60);
    Serial.println(JSON_msg_array);
    if (client.connected()) {
      client.publish(OutTopic, JSON_msg_array);
      Serial.print("Published to topic:");
      Serial.println(OutTopic);
    } else {
      Serial.print("not connected to broker... couldn't send MQTT message");
    }
    lastMsg = millis();
  }
}
