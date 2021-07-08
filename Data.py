import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore
import time, datetime

cred = credentials.Certificate("fyp-21-s2-24-firebase-adminsdk-7qer9-ad53d1c1e2.json")
firebase_admin.initialize_app(cred)
db = firestore.client()

doctor_slots = db.collection('Account_User').document('Medical_Personnel-iBnhkCP6HAhM0MvxeI4O').collection('Appointment_Slots').document('22-06-2021').collection('Slots')
timing = doctor_slots.get()

# for slots in timing:
#     print('{} => {} '.format(slots.id, slots.to_dict()))

#adding first data
data = {
    'avaliable' : True,
    'patient' : ['test'],
    'slotid' : 'slot-1002',
    'time' : '0930'
}

#doctor_slots.document('slot-1002').set(data)
d = datetime.datetime.now()

#Medical Personnel Profile
doctor = {
    'accountdetails' : {
        'createdon' : {
            'date' : d.strftime("%d-%m-%Y"),
            'time' : d.strftime("%X")},
        'usertype': 'Medical Personnel'
    },
    'credentials' : {
        'email' : '',
        'password' : ''
    },
    'passwordreset' : {
        'passwordtoken' : '',
        'requestedon' : {
            'date' : d.strftime("%d-%m-%Y"),
            'time' : d.strftime("%X")
        },
        'tokenused' : False
    },
    'practionerinfo' : {
        'facilityid' : ['mf001'],
        'licensenumber' : 'doc-002',
        'specialisation' : 'Neurology'
    },
    'profile' : {
        'address' : '35 Tampines Road',
        'contactnumber' : '98752364',
        'dob' : '03-05-1989',
        'gender' : 'F',
        'name' : {
            'first' : 'John',
            'last' : 'Doe'
        }
    },
    'session' : {
        'ipaddress' : '',
        'isloggedin' : False,
        'sessionid' : '',
        'token' : ''}
}


# for x in range (1,5):
#     db.collection('Account_User').document('MedicalPersonnel00' + str(x)).set(doctor)