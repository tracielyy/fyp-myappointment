<?php
/* Load Config File */
require_once '../resources/config.php';

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentReference;

require '../vendor/autoload.php';

class Database {

    private $db;

    // Constructor
    function __construct() {
        $this->db = new FirestoreClient([
            'projectId' => 'fyp-21-s2-24',
            'keyFile' => json_decode(file_get_contents( UTILS_PATH . '/json-key-tracy-001.json'), true)
        ]);
    }

    // Get Unique Firestore Document In Specific Collection
    public function get_document($collection, $id) {
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
    public function query_exact_match($collection, $conditionArr) {
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
        return NULL;
    }

    // Insert Data: return success status
    public function insert_data(string $collection, array $data_info): bool {
        $data_doc_ref = $this->db->collection($collection)->add($data_info);
        if ($data_doc_ref !== NULL) {
            return True;
        }
        return False;
    }
    
    // Get Document Via Email
//    public function get_document(){
//        
//    }

    // Modify Map Fields
    public function modify_map_field(string $collection, string $email, array $mapArr) {
        $collection_ref = $this->db->collection($collection);
        $get_query = $collection_ref->where("email", "=", $email)->limit(1);
        $snapshot = $get_query->documents();
        foreach ($snapshot as $document) {
            if ($document->exists()) {
                $doc_id = $document->id();
                $doc_ref = $collection_ref->document($doc_id);
                self::update_map_values($doc_ref, $mapArr);
                return True;
            }
        }
        return False;
    }

    // Update Multiple Map Field Values
    public function update_map_values(DocumentReference $doc_ref, array $mapArr) {
        foreach ($mapArr as $fieldArr => $value) {
            foreach ($mapArr[$fieldArr] as $field => $field_value) {
                $path = $fieldArr . "." . $field;
                $doc_ref->update([
                    ['path' => $path, 'value' => $field_value]
                ]);
            }
        }
    }

    // Get Fields Of Map Type
    public function get_map_field(DocumentReference $doc_ref, array $mapArr) {
        
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
