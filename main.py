import schedule
import time
import pytz
import datetime

timezone = pytz.timezone('Asia/Singapore')
zone = timezone.localize(datetime.datetime.now())
print(timezone)

def job():
    print("Time now: " + str(zone))

schedule.every(10).minutes.do(job)
schedule.every().day.at("00:00").do(job)

# while True:
#     schedule.run_pending()
#     time.sleep(1)