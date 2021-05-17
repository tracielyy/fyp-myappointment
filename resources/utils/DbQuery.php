<!-- 
    Developed By FYP-21-S2-24
-->

<?php
/*
 * @author yanying (Tracy)
 */
/* Load Config File */
require_once '../resources/config.php';

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentReference;
use Google\Cloud\Firestore\CollectionReference;

require '../vendor/autoload.php';

class DbQuery {

    private $db;

    // -- Constructor -- //
    function __construct() {
        $this->db = new FirestoreClient([
            'projectId' => 'fyp-21-s2-24',
            'keyFile' => json_decode(file_get_contents(UTILS_PATH . '/json-key-tracy-001.json'), true)
        ]);
    }

    // -- Get Unique Firestore Document In Specific Collection -- //
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

    private function get_doc_ref($collection, $id): DocumentReference {
        $collection_ref = $this->db->collection($collection);
        $doc_ref = $collection_ref->document($id);
        return $doc_ref;
    }

    // -- Get Firestore Document Wihout Knowing Document ID -- //
    public function query_exact_match($collection, $conditionArr) {
        $query = $this->db->collection($collection);
        foreach ($conditionArr as $condition => $condition_value) {
            $query = $query->where($condition, "=", $condition_value)->limit(1);
        }
        $snapshot = $query->documents();
        foreach ($snapshot as $document) {
            if ($document->exists()) {
                return $document->data(); //  -- Returning the Data
            }
        }
        return NULL;
    }

    // -- Insert Data: return success status -- //
    public function insert_data(string $collection, array $data_info): bool {
        $data_doc_ref = $this->db->collection($collection)->add($data_info);
        if ($data_doc_ref !== NULL) {
            return True;
        }
        return False;
    }

    // Modify Map Fields (EMAIL)
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
    private function update_map_values(DocumentReference $doc_ref, array $mapArr) {
        foreach ($mapArr as $fieldArr => $value) {
            foreach ($mapArr[$fieldArr] as $field => $field_value) {
                $path = $fieldArr . "." . $field;
                $doc_ref->update([
                    ['path' => $path, 'value' => $field_value]
                ]);
            }
        }
    }

    // -- Get Fields Of Map Type -- //
    public function get_map_field(DocumentReference $doc_ref, array $mapArr) {
        
    }

    // -- Get Nested Collection's Documents - //
    public function get_nested_collection(string $collection, string $subcollection, array $conditionArr, array $subconditionArr) {
        // Getting The Condition Keys
        $condition = array_key_first($conditionArr); # Outer Condition
        $subcondition = array_key_first($subconditionArr); # Inner Condition
        # Collection
        $query = $this->db->collection($collection);
        $query = $query->where($condition, "=", $conditionArr[$condition])->limit(1);
        $snapshot = $query->documents();
        foreach ($snapshot as $document) {
            if ($document->exists()) {
                $doc_id = $document->id(); //  -- Returning the Data
            }
        }

        # Using ID
        $sub_col_ref = $this->db->collection($collection)->document($doc_id);
        $sub_cols = $sub_col_ref->collection($subcollection);

        # Sub-Collection

        $sub_cols = $sub_cols->where($subcondition, "=", $subconditionArr[$subcondition])->limit(1);

        $sub_snapshot = $sub_cols->documents();
        foreach ($sub_snapshot as $doc) {
            if ($doc->exists()) {
                return $doc->data(); //  -- Returning the Data
            }
        }


        return NULL;
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
