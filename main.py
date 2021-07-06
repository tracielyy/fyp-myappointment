import schedule
import time
from pytz import timezone
from datetime import datetime
import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore
import Data as data
from Data import doctor

db = firestore.client()

def job():
    datenow = datetime.now()
    datetime_tz = timezone('Asia/Singapore').localize(datenow)
    print("Time now: " + str(datetime_tz))

def add_data(x):
    db.collection('Account_User').document('MedicalPersonnel0' + str(x)).set(doctor)

for i in range(10,15,1):
    i = 10
    schedule.every(30).minutes.do(add_data,i)
print("Data Added")

schedule.every(10).minutes.do(job)
schedule.every().day.at("00:00").do(job)

while True:
    schedule.run_pending()
    time.sleep(1)
    