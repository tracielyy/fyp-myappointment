import schedule
import time
import random, string
from pytz import timezone
from datetime import datetime, timedelta
import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore
import Data as data
from Data import doctor


db = firestore.client()

class Count(object):
    
    def __init__(self):
        self._count = 0

    def __str__(self):
        count = self._count
        self._count += 1
        return str(count)

def job(i):
    datenow = datetime.now()
    datetime_tz = datenow + timedelta(hours=8)
    dateformat = datetime_tz.strftime("%d-%m-%Y %H:%M:%S")
    print("Time now: " + str(dateformat) + " " + str(i))

def add_data():
    rand8 = random.randint(1000000,9999999)
    datenow = datetime.now()
    datetime_tz = datenow + timedelta(hours=8)
    dateformat = datetime_tz.strftime("%d-%m-%Y %H:%M:%S")
    print("Time now: " + str(dateformat) + " Data added")
    nric = 'S' + str(rand8) + random.choice(string.ascii_uppercase)
    db.collection('Account_User').document(nric).set(doctor)
    db.collection('Account_User').document(nric).update({'nric' : nric })

count = Count()
# schedule.every().second.do(job,count)

schedule.every(10).seconds.do(add_data)

# schedule.every(10).minutes.do(job)
# schedule.every().day.at("00:00").do(job)

while True:
    schedule.run_pending()
    time.sleep(1)