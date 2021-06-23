import firebase_admin
from firebase_admin import credentials
from firebase_admin import firestore

cred = credentials.Certificate("fyp-21-s2-24-firebase-adminsdk-7qer9-ad53d1c1e2.json")
firebase_admin.initialize_app(cred)
db = firestore.client()

emp_ref = db.collection('Medical_Facility').document('mf002').collection('Doctor Consultation').document('21-06-2021').collection('Slots')
docs = emp_ref.get()

for doc in docs:
    print('{} => {} '.format(doc.id, doc.to_dict()))

# #adding first data
# doc_ref = db.collection('Medical_Facility').document('mf002')
#
# doc_ref.set({
#
# })