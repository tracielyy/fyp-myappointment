import schedule
import time
from pytz import timezone
from datetime import datetime

def job():
    datenow = datetime.now()
    datetime_tz = timezone('Asia/Singapore').localize(datenow)
    print("Time now: " + str(datetime_tz))

schedule.every(10).minutes.do(job)
schedule.every().day.at("00:00").do(job)

while True:
    schedule.run_pending()
    time.sleep(1)