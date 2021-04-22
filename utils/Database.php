<?php

use Google\Cloud\Firestore\FirestoreClient;

require '../vendor/autoload.php';

class Database {

    private $db;

    // Constructor
    function __construct() {
        $this->db = new FirestoreClient([
            'projectId' => 'fyp-21-s2-24',
            'keyFile' => json_decode(file_get_contents('../utils/json-key-tracy-001.json'), true)
        ]);
    }

    // Get Unique Firestore Document In Specific Collection
    function get_document($collection, $id) {
        $collectionRef = $this->db->collection($collection);
        $docRef = $collectionRef->document($id);
        $snapshot = $docRef->snapshot();
        if ($snapshot->exists()) {
            return $snapshot->data();
        } else {
            return NULL;
        }
    }

    // Get Firestore Document Wihout Knowing Document ID
    function query_exact_match($collection, $conditionArr) {
        $query = $this->db->collection($collection);
        foreach ($conditionArr as $condition => $condition_value) {
            $query = $query->where($condition, "=", $condition_value);
        }
        $snapshot = $query->documents();
        foreach ($snapshot as $document) {
            return $document->data();
        }
    }

    // Insert Data: return success status
    function insert_data(string $collection, array $userDataInfo): bool {
        $dataDocRef = $this->db->collection($collection)->add($userDataInfo);
        if ($dataDocRef !== NULL) {
            return True;
        }
        return False;
    }

    //    // Get Firestore Document Wihout Knowing Document ID
//    function retrieve_user_credential($collection, $email, $password) {
//        $collectionRef = $this->db->collection($collection);
//        $query = $collectionRef->where('email', '=', $email);
//        $query = $query->where('password', '=', $password);
//        $snapshot = $query->documents();
//        foreach ($snapshot as $document) {
//            return $document->data();
//        }
//    }
}

?>
