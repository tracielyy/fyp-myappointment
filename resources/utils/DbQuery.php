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
use Google\Cloud\Firestore\DocumentSnapshot;

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
    public function get_document(string $collection, string $id): ?array {

        # Collection Reference
        $collection_ref = $this->db->collection($collection);

        # Document Reference
        $doc_ref = $collection_ref->document($id);
        $snapshot = $doc_ref->snapshot();
        if ($snapshot->exists()) {
            return $snapshot->data();
        } else {
            return NULL;
        }
    }

    // -- Get Document Reference Via Document ID -- //
    private function get_doc_ref(string $collection, string $id): DocumentReference {

        # Collection Reference
        $collection_ref = $this->db->collection($collection);

        # Document Reference
        $doc_ref = $collection_ref->document($id);

        return $doc_ref;
    }


    // -- Get DocumentSnapshot -- //
    private function document_query(string $collection, array $conditionArr)/* [nullable]: DocumentSnapshot */ {

        # Collection Reference
        $query = $this->db->collection($collection);

        # Iterate Through The Given `$conditionArr` (Array)
        foreach ($conditionArr as $mapCondition => $value) {

            if (is_array($conditionArr[$mapCondition])) {
                # Inner Loop For Maps
                foreach ($conditionArr[$mapCondition] as $condition => $condition_value) {

                    # Get The Path To The Map Fields
                    $condition_path = $mapCondition . "." . $condition;
                    $query = $query->where($condition_path, "=", $condition_value)->limit(1);
                }
            } else {
                $condition_path = $mapCondition;
                $query = $query->where($condition_path, "=", $value)->limit(1);
            }
        }
        $snapshot = $query->documents();

        # Iterate Through An Array Of Documents
        foreach ($snapshot as $document) {
            if ($document->exists()) {
                return $document; //  -- Returning the  Whole Document
            }
            return null;
        }
    }

    // -- Get Firestore Document Wihout Knowing Document ID -- //
    public function query_exact_match(string $collection, array $conditionArr): ?array {

        # Get Document Data From DocumentSnapshot
        $doc_ref = $this->document_query($collection, $conditionArr);
        if ($doc_ref !== NULL) {
            return $doc_ref->data();
        }
        return null;
    }

    // -- Insert Data: return success status  (Adding New Document To Collection) -- //
    public function insert_data(string $collection, array $data_info): bool {

        # Collection Reference
        $collection_ref = $this->db->collection($collection);

        # Adding New Set Of Document To Collection
        if ($collection_ref->add($data_info) !== NULL) {
            return True;
        }
        return False;
    }

    // -- Modify Field(s) [Modify A Field In A Document] -- //
    public function modify_field(string $collection, array $conditionArr, array $changedArr): void {

        # Get Document ID From DocumentSnapshot
        $doc_id = $this->document_query($collection, $conditionArr)->id();

        # Get Document Reference
        $doc_ref = $this->get_doc_ref($collection, $doc_id);

        # Modify The Document Via Document Reference
        foreach ($changedArr as $field => $value) {
            $doc_ref->update([
                ['path' => $field, 'value' => $value]
            ]);
        }
    }

    // -- Modify Map Fields (EMAIL) -- //
    public function modify_map_field(string $collection, array $conditionArr, array $mapArr): bool {

        # Collection Reference
        $collection_ref = $this->db->collection($collection);

        # Search Document Via `email` Condition
        $document = $this->document_query($collection, $conditionArr);

        # Check If Document Exist
        if ($document->exists()) {

            # Getting The Document Reference
            $doc_id = $document->id();
            $doc_ref = $collection_ref->document($doc_id);

            # Updating The Map Values With Attained Document ID
            self::update_map_values($doc_ref, $mapArr);

            return True;
        }
        return False;
    }

    // -- Update Multiple Map Field Values -- //
    private function update_map_values(DocumentReference $doc_ref, array $mapArr): void {

        # Array (Outside)
        foreach ($mapArr as $fieldArr => $value) {


            # Iterating Through Each Field In Array To Update
            foreach ($mapArr[$fieldArr] as $field => $field_value) {

                # Path For Each Field In Map Data Types
                $path = $fieldArr . "." . $field;

                # Update Each Value
                $doc_ref->update([
                    ['path' => $path, 'value' => $field_value]
                ]);
            }
        }
    }

    private function get_map_values(DocumentReference $doc_ref, array $mapArr) {
        foreach ($mapArr as $fieldArr => $value) {
            foreach ($mapArr[$fieldArr] as $field => $field_value) {
                
            }
        }
    }

    public function get_map_field(string $collection, array $conditionArr, string $mapField): array {

        # Return The Whole Document Data
        $mapData = $this->query_exact_match($collection, $conditionArr);

        # Filter & Return Specified Map Field Datas
        return $mapData[$mapField];
    }

    // -- Get Nested Collection's Documents - //
    public function get_nested_collection(string $collection, string $subcollection, array $conditionArr, array $subconditionArr): array {

        # Getting The Condition Keys
//        $condition = array_key_first($conditionArr); # Outer Condition
        //$subcondition = array_key_first($subconditionArr); # Inner Condition
        # Collection
        $doc_snapshot = $this->document_query($collection, $conditionArr);

        # Iterate Through An Array Of Documents

        if ($doc_snapshot->exists()) {
            $doc_id = $doc_snapshot->id();
        }


        # Using ID
        $sub_col_ref = $this->db->collection($collection)->document($doc_id);
        $sub_cols = $sub_col_ref->collection($subcollection);

        # Sub-Collection
        foreach ($subconditionArr as $subcondition => $value) {
            $sub_cols = $sub_cols->where($subcondition, "=", $value);
        }
        $sub_snapshot = $sub_cols->documents();

        # Create An Array To Store The Document Data
        $doc_arr = array();

        # Iterate Through An Array Of Documents
        foreach ($sub_snapshot as $doc) {
            if ($doc->exists()) {
                $doc_arr[] = $doc->data(); //  -- Storing Each Document Data In Array
            }
        }
        return $doc_arr;  // -- Return Array Of Document Datas
    }

}
?>
