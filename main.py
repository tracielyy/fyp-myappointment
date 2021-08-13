import schedule
import time
from datetime import datetime, timedelta, date
import smtplib
from email.message import EmailMessage
import firebase_admin
from firebase_admin import credentials, firestore

if not firebase_admin._apps:
    cred = credentials.Certificate('fyp-21-s2-24-firebase-adminsdk-7qer9-ad53d1c1e2.json') 
    default_app = firebase_admin.initialize_app(cred)

db = firestore.client()

def send_email(name,location,email,date,time,type):
    
    EMAIL_ADDRESS = 'fyp.21.s2.24@gmail.com'
    EMAIL_PASSWORD = 'spikpdnhvquhyjcf'

    msg = EmailMessage()
    msg['Subject'] = 'Reminder for ' + type + ' at ' + location
    msg['From'] = EMAIL_ADDRESS
    msg['To'] = email
    msg.set_content('Dear ' + name + ',' +
                    '\n' +
                    '\nThis is a Reminder for your Upcoming Appointment at ' + location +  '.' +
                    '\nType: ' + type +
                    '\nDate: ' + date +
                    '\nTime: ' + time +
                    '\n\nSincerely, ' +
                    '\nFYP-21-S2-24 Team')

    with smtplib.SMTP_SSL('smtp.gmail.com',465) as smtp:
        smtp.login(EMAIL_ADDRESS, EMAIL_PASSWORD)
        smtp.send_message(msg)

def email():
    #Start End Date for check
    tmr = datetime.now() + timedelta(days=1)
    today = datetime.now()

    #Medical Facility List
    mflist = {}
    mfslot = {}
    mf = db.collection('Medical_Facility').stream()
    for location in mf:
        mflist.update({location.get(u'facilityid') : location.get(u'facilityname')})

        #Medical Facility Slots
        checkup = db.collection('Medical_Facility').document(location.id).collection('Check Up').document(tmr.strftime("%d-%m-%Y")).collection('Slots').stream()
        Doccon = db.collection('Medical_Facility').document(location.id).collection('Doctor Consultation').document(tmr.strftime("%d-%m-%Y")).collection('Slots').stream()
        for slots in checkup:
            mfslot.update({slots.get(u'slotid') : slots.get(u'time')})
        for slots in Doccon:
            mfslot.update({slots.get(u'slotid') : slots.get(u'time')})

    #Patient Profile
    patientlist = {}
    appointmentlist = []
    apptlocid = []
    apptidlist = []
    updatelist = []

    user = db.collection('Account_User')
    patient = user.where(u'accountdetails.usertype', u'==', u'Patient').stream()
    for data in patient:
        key = data.get(u'profile.nric')
        patientlist.update({data.get(u'profile.nric') : data.get(u'profile.name.firstname')+'~'+data.get(u'profile.name.lastname')+'~'+data.get(u'credentials.email')})
        appointment = user.document(data.id).collection('Appointment_Record')
        apt = appointment.where(u'appointmentstatus', u'==', u'Upcoming').stream()
        #Appointment Record
        for appt in apt:
            apptidlist.append(appt.id)
            apptlocid.append(appt.get(u'facilityid'))
            for x in apptlocid:
                mfid = x
            appointmentlist.append(key + " " + mfid + " " + appt.get(u'slotid') + " " + appt.get(u'appointmenttype'))
            updatelist.append(data.id + ' ' + appt.id + ' ' + appt.get(u'slotid'))
        
    # print(appointmentlist)
    print('Patient List Updated')
    print('Appointment List Updated')

    #Getting Medical Personnel
    mplist = []
    mp = user.where(u'accountdetails.usertype', u'==', u'Medical Personnel').stream()
    for data in mp:
        mplist.append(data.id)
        
    #Appointment slot for Specialist
    specialistslot = {}
    for key in mplist:
        slot = user.document(key).collection('Appointment_Slots').document(tmr.strftime("%d-%m-%Y")).collection('Slots').stream()
        for slots in slot:
            specialistslot.update({slots.get(u'slotid') : slots.get(u'time')})
    # print(specialistslot)
    print('Slot Updated')

    apptdate = []
    apptid = []
    appttype = []
    nriclist = []
    specid = []
    spectype = []
    for data in appointmentlist:
        appointmentlistid = data.split(' ',4)
        nriclist.append(appointmentlistid[0])
        specid.append(appointmentlistid[2])
        spectype.append(appointmentlistid[3] + ' ' + appointmentlistid[4])
        apptid.append(appointmentlistid[2] + ' ' + appointmentlistid[3])
        appttype.append(appointmentlistid[4])
        apptdetails = appointmentlistid[2].split('~')
        apptdate.append(apptdetails[1])
    # print(spectype)

    pnamelist = {}
    pemaillist = {}
    for ic, details in patientlist.items():
        pinfo = details.split('~')
        pname = pinfo[0] + ' ' + pinfo[1]
        pemail = pinfo[2]
        pnamelist.update({ic : pname})
        pemaillist.update({ic : pemail})  

    for x in range(len(appointmentlist)):
        #Check for Checkup and Doc Consultations
        if apptid[x] in mfslot:
            slotid = apptid[x]
            if apptdate[x] == tmr.strftime("%d-%m-%Y"):
                print(str(pnamelist[nriclist[x]]) + ' ' + str(mflist[apptlocid[x]]) + ' ' + str(pemaillist[nriclist[x]]) + ' ' + str(apptdate[x]) + ' ' + str(mfslot[slotid]) + ' ' + appttype[x])
                send_email(pnamelist[nriclist[x]],mflist[apptlocid[x]],pemaillist[nriclist[x]],apptdate[x],mfslot[slotid],appttype[x])
                print('Email Sent')
        else:
            print('No appointment Found for Checkup or Doctor Consultation')

        #Check for Specialist
        if specid[x] in specialistslot:
            slotid = specid[x]
            print(str(pnamelist[nriclist[x]]) + ' ' + str(mflist[apptlocid[x]]) + ' ' + str(pemaillist[nriclist[x]]) + ' ' + str(apptdate[x]) + ' ' + str(specialistslot[slotid]) + ' ' + spectype[x])
            send_email(pnamelist[nriclist[x]],mflist[apptlocid[x]],pemaillist[nriclist[x]],apptdate[x],specialistslot[slotid],appttype[x])
            print('Email Sent')
        else:
            print('No appointment Found for Specialist')

def missed():
    #Start End Date for check
    tmr = datetime.now() + timedelta(days=1)
    today = datetime.now()

    #Medical Facility List
    mflist = {}
    mfslot = {}
    tdslot = {}
    mf = db.collection('Medical_Facility').stream()
    for location in mf:
        mflist.update({location.get(u'facilityid') : location.get(u'facilityname')})

        #Past Slot
        tdcheckup = db.collection('Medical_Facility').document(location.id).collection('Check Up').document(today.strftime("%d-%m-%Y")).collection('Slots').stream()
        tdDoccon = db.collection('Medical_Facility').document(location.id).collection('Doctor Consultation').document(today.strftime("%d-%m-%Y")).collection('Slots').stream()
        for slots in tdcheckup:
            tdslot.update({slots.get(u'slotid') : slots.get(u'time')})
        for slots in tdDoccon:
            tdslot.update({slots.get(u'slotid') : slots.get(u'time')})

        #Medical Facility Slots
        checkup = db.collection('Medical_Facility').document(location.id).collection('Check Up').document(tmr.strftime("%d-%m-%Y")).collection('Slots').stream()
        Doccon = db.collection('Medical_Facility').document(location.id).collection('Doctor Consultation').document(tmr.strftime("%d-%m-%Y")).collection('Slots').stream()
        for slots in checkup:
            mfslot.update({slots.get(u'slotid') : slots.get(u'time')})
        for slots in Doccon:
            mfslot.update({slots.get(u'slotid') : slots.get(u'time')})

    #Patient Profile
    patientlist = {}
    appointmentlist = []
    apptlocid = []
    updatelist = []
    apptidlist = []

    user = db.collection('Account_User')
    patient = user.where(u'accountdetails.usertype', u'==', u'Patient').stream()
    for data in patient:
        key = data.get(u'profile.nric')
        patientlist.update({data.get(u'profile.nric') : data.get(u'profile.name.firstname')+'~'+data.get(u'profile.name.lastname')+'~'+data.get(u'credentials.email')})
        appointment = user.document(data.id).collection('Appointment_Record')
        apt = appointment.where(u'appointmentstatus', u'==', u'Upcoming').stream()
        #Appointment Record
        for appt in apt:
            apptidlist.append(appt.id)
            apptlocid.append(appt.get(u'facilityid'))
            for x in apptlocid:
                mfid = x
            appointmentlist.append(key + " " + mfid + " " + appt.get(u'slotid') + " " + appt.get(u'appointmenttype'))
            updatelist.append(data.id + ' ' + appt.id + ' ' + appt.get(u'slotid'))
        #Update Appointment
    for apptid in updatelist:
        data = apptid.split(' ',2)
        time = tdslot.get(data[2],None)
        if time is not None:
            apptid += ' ' + time
            if time < today.strftime('%H:%M'):
                user.document(data[0]).collection('Appointment_Record').document(data[1]).update({u'appointmentstatus': 'Missed'})
                # print(apptid)
    print('Appointment Status Updated')

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
            print('Doctor Consultation Slots Added')
            
            #Check up
            mf.document(mflist[x]).collection('Check up').document(dates).set({'date': dates})
            for time in checkuptimelist:
                cuid = str(counter) + '~' + str(dates) + '~' + 'Check up'
                checkup = {u'doctorlist': doclist,
                        u'patientlist': [],
                        u'slotid' : cuid,
                        u'time' : time}
                mf.document(mflist[x]).collection('Check up').document(dates).collection('Slots').document(cuid).set(checkup)
                counter += 1
            print('Check up Slots Added')
    

def main():
    schedule.every(30).minutes.do(missed)
    schedule.every().day.at('10:00').do(email)
    schedule.every(30).days.do(Add)

    while True:
        schedule.run_pending()
        time.sleep(1)

if __name__ == "__main__":
    main()
