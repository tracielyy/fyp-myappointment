import schedule
import time
import pytz
import datetime

def job():
    print("Time now: " + str(datetime.now()))

schedule.every(10).minutes.do(job)
schedule.every().day.at("00:00").do(job)

timezone = pytz.timezone("Singapore")
zone = timezone.localize(datetime.datetime.now())

while True:
    schedule.run_pending()
    time.sleep(1)