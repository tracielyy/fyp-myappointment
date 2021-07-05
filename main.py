import schedule
import time
from datetime import datetime

def job():
    print("Time now: " + datetime.now())

schedule.every(10).minutes.do(job)
schedule.every().day.at("00:00").do(job)

while True:
    schedule.run_pending()
    time.sleep(1)