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
        $collection_ref = $this->db->collection($collection);
        $doc_ref = $collection_ref->document($id);
        $snapshot = $doc_ref->snapshot();
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
            $query = $query->where($condition, "=", $condition_value)->limit(1);
        }
        $snapshot = $query->documents();
        foreach ($snapshot as $document) {
            if ($document->exists()) {
                return $document->data();
            }
        }
    }

    // Insert Data: return success status
    function insert_data(string $collection, array $userDataInfo): bool {
        $data_doc_ref = $this->db->collection($collection)->add($userDataInfo);
        if ($data_doc_ref !== NULL) {
            return True;
        }
        return False;
    }

    // Modify Data Via Email
    function modify_single_field(string $collection, string $email, string $field, $field_value) {
        $collection_ref = $this->db->collection($collection);
        $get_query = $collection_ref->where("email", "=", $email)->limit(1);
        $snapshot = $get_query->documents();
        foreach ($snapshot as $document) {
            if ($document->exists()) {
                $doc_id = $document->id();
                $doc_ref = $collection_ref->document($doc_id);
                $doc_ref->update([
                    ['path' => $field, 'value' => $field_value]
                ]);
            }
        }
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
