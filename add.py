import time
import random, string
from datetime import datetime, timedelta, date
import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore

cred = credentials.Certificate("fyp-21-s2-24-firebase-adminsdk-7qer9-ad53d1c1e2.json")
firebase_admin.initialize_app(cred)
db = firestore.client()

def Add():
    doccontimelist = ['08:00','08:20','08:40','09:00','09:20','09:40','10:00','10:20','10:40','11:00','11:20','13:00','13:20'
                ,'13:40','14:00','14:20','14:40','15:00','15:20','15:40','16:00','16:20','16:40','17:00','17:20']

    checkuptimelist = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','13:30',
                        '14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30']
    doclist = []

    mp = db.collection('Account_User').where(u'practitionerinfo.specialisation', u'==', u'General').stream()
    for data in mp:
        mf = data.get('practitionerinfo.facilityids')
        doc = data.id
        doclist.append(doc)
        print(doc)
        # for loc in mf:
        #     print(loc)

    mf = db.collection('Medical_Facility')

    mflist = []
    for data in mf.stream():
        mflist.append(data.id)

    def daterange(start_date, end_date):
        for n in range(int((end_date - start_date).days)):
            yield start_date + timedelta(n)

    start_date = datetime.now()
    end_date = start_date + timedelta(days=31)
    datelist = []
    for dates in daterange(start_date, end_date):
        datelist.append(dates.strftime("%d-%m-%Y"))

    for x in range(len(mflist)):
        for dates in datelist:
            if mflist[x] == 'mf001':
                counter = 1001
            elif mflist[x] == 'mf002':
                counter = 2001
            elif mflist[x] == 'mf003':
                counter = 3001
            elif mflist[x] == 'mf004':
                counter = 4001
            #Doctor Consultation
            mf.document(mflist[x]).collection('Doctor Consultation').document(dates).set({'date': dates})
            for time in doccontimelist:
                dcid = str(counter) + '~' + str(dates) + '~' + 'Doctor Consultation'
                doc_consultation = {u'doctorlist': doclist,
                        u'patientlist': [],
                        u'slotid' : dcid,
                        u'time' : time}
                mf.document(mflist[x]).collection('Doctor Consultation').document(dates).collection('Slots').document(dcid).set(doc_consultation)
                counter += 1
            
            #Check up
            mf.document(mflist[x]).collection('Check up').document(dates).set({'date': dates})
            for time in checkuptimelist:
                cuid = str(counter) + '~' + str(dates) + '~' + 'Check up'
                checkup = {u'doctorlist': doclist,
                        u'patientlist': [],
                        u'slotid' : docid,
                        u'time' : time}
                mf.document(mflist[x]).collection('Check up').document(dates).collection('Slots').document(cuid).set(checkup)
                counter += 1
    
    