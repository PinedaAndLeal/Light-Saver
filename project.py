# -*- coding: utf-8 -*-
import paho.mqtt.client as paho
import time
import datetime
import pymysql

broker = "broker.hivemq.com"
port = 8000
sub_topic = "http://localhost:8080/api/index.php?data=saver"
pub_topic = "http://localhost:8080/api/index.php?data=saver"
send_ctrl = "Stop"

#callbacks
def on_subscribe(client, userdata, mid, granted_qos):
    print("Subscribed with QoS ", granted_qos, "\n")
    
def on_message(client, userdata, message):
    global send_ctrl
    print("Received inserted message: " ,str(message.payload.decode("utf-8")))
    send_ctrl = str(message.payload.decode("utf-8"))
    
def mysqlconnect():
    # To connect MySQL database
    conn = pymysql.connect(
        host='localhost',
        user='root', 
        password = "password",
        db='lightsaver',
        )
      
    cur = conn.cursor()
    cur.execute("INSERT INTO lightsaver (n,t,motion,illumintion) \
                        VALUES ('%S','%S','%S','%S')")
    output = cur.fetchall()
    # To close the connection
    conn.close()
    
#MAIN
client = paho.Client('client-socks',transport='websockets') #create client object
client.on_subscribe = on_subscribe  #assign function to callbacks
client.on_message = on_message      #assign function to callbacks
print("Connection to broker: ",broker," port: ",port)
client.connect(broker,port)         #establish connection
client.loop_start()
print("Subscribed to: ",sub_topic)  #Subscribed to Control channel
client.subscribe(sub_topic)
while 1:
    if send_ctrl == "Start":
        ts = time.time()
        #Delete Date and Hour chain
        st = datetime.datetime.now().strftime('%d/%m/%y,%H:%M:%S')
        str_ts = str(ts).split('.')[0]
        #Create JSON message
        mystr = "{\"n\:\"1\",\"t\":\""+ str + "\",\"motion\":\"" + str + "\",\"illumintion\":\"" + str + "\",\"lastUpdate\":\"" + str_ts + "\"}"
        print('%s' % mystr)
        #Publish the requested message by the remote client
        client.publish(pub_topic,mystr,mysqlconnect) #publish
        #Wait 2 seconds
        time.sleep(2)