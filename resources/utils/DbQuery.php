<?php

/*
 * @author yanying (Tracie)
 */
/* Load Config File */
require_once '../resources/config.php';

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentReference;
use Google\Cloud\Firestore\CollectionReference;
use Google\Cloud\Firestore\Query;
use Google\Cloud\Firestore\DocumentSnapshot;
use Google\Cloud\Firestore\FieldValue;

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

        # Document Reference
        $doc_ref = $this->db->collection($collection)->document($id);
        $snapshot = $doc_ref->snapshot();
        if ($snapshot->exists()) :
            return $snapshot->data();
        endif;

        return NULL;
    }

    private function with_condition(CollectionReference $query, array $condition): Query {
        # Iterate Through The Given `$condition` (Array)
        foreach ($condition as $key => $value) :

            if (is_array($value)):

                # Inner Loop For Maps
                $query = $this->nested_condition($query, $condition, $key);
            else:

                # If It Is Not A Map
                $condition_path = $key;
                $query = $query->where($condition_path, "=", $value);
            endif;
        endforeach;

        return $query->limit(1);
    }

    public function get_documents_by_path(string $doc_path, bool $filter, ?array $condition = null): array {

        # Create Empty Array
        $doc_arr = array();

        # Collection Reference
        $query = $this->db->collection($doc_path);

        # Add Condition (WHERE Clause)
        if ($filter):
            $query = $this->with_condition($query, $condition);
        endif;

        $doc_snapshot = $query->documents();

        foreach ($doc_snapshot as $doc):
            if ($doc->exists()):
                $doc_arr[] = $doc->data();
            endif;

        endforeach;
        return $doc_arr;
    }


    // -- For Document Inner Maps -- //
    private function nested_condition(Query $query, array $conditionArr, string $key): Query {
        foreach ($conditionArr[$key] as $condition => $condition_value) :

            # Get The Path To The Map Fields
            $condition_path = $key . "." . $condition;
            $query = $query->where($condition_path, "=", $condition_value);
        endforeach;
        return $query;
    }

    // -- Get DocumentSnapshot -- //
    private function document_query(CollectionReference $query, array $conditionArr)/* [nullable]: DocumentSnapshot */ {

        $snapshot = $this->with_condition($query, $conditionArr)->documents();

        # Iterate Through An Array Of Documents
        foreach ($snapshot as $document) :

            if ($document->exists()) :
                return $document; //  -- Returning the Whole Document
            endif;

            return null;

        endforeach;
    }

    public function get_document_id(string $doc_path, array $conditionArr): ?string {
        # Collection Reference
        $collection_ref = $this->db->collection($doc_path);

        # Get Document 
        $doc_ref = $this->document_query($collection_ref, $conditionArr);

        if ($doc_ref != null):
            return $doc_ref->id();
        endif;
        return null;
    }

    // -- Get Firestore Document Wihout Knowing Document ID -- //
    public function query_exact_match(string $path, array $conditionArr): ?array {

        # Collection Reference
        $collection_ref = $this->db->collection($path);

        # Get Document Data From DocumentSnapshot
        $doc_ref = $this->document_query($collection_ref, $conditionArr);

        # If The `DocumentRefence` Is Retrieved Then Return It's Data
        if ($doc_ref !== NULL) :
            return $doc_ref->data();
        endif;

        return null;
    }

    // -- Insert Data: return success status  (Adding New Document To Collection) -- //
    public function insert_data(string $doc_path, array $data_info, bool $auto_id, ?string $id): bool {

        # Collection Reference
        $collection_ref = $this->db->collection($doc_path);

        # Opt for Auto-Generated ID
        if ($auto_id):

            # Adding New Set Of Document To Collection Using `CollectionReference`
            if ($collection_ref->add($data_info) !== NULL) :
                return True;
            endif;

            return False;

        # User Specified ID
        else:

            # Adding New Set Of Document To Collection Using `CollectionReference`
            if ($collection_ref->document($id)->set($data_info) !== NULL) :
                return True;
            endif;

            return False;

        endif;
    }

    // -- Modify Map Fields (EMAIL) -- //
    public function modify_field(string $collection, array $conditionArr, array $mapArr): bool {

        # Collection Reference
        $collection_ref = $this->db->collection($collection);

        # Search Document Via `email` Condition
        $document = $this->document_query($collection_ref, $conditionArr);

        # Check If Document Exist
        if ($document->exists()) :

            # Getting The Document Reference
            $doc_ref = $collection_ref->document($document->id());

            # Updating The Map Values With Attained Document ID
            $this->update_values($doc_ref, $mapArr);

            return True;
        endif;
        return False;
    }

    public function update_array_add(string $doc_path, string $doc_id, array $changedArr): bool {

        # Get The Document Reference
        $doc_ref = $this->db->collection($doc_path)->document($doc_id);

        # Modify The Document Via `DocumentReference`
        foreach ($changedArr as $field => $v) :
            foreach ($v as $val):
                $doc_ref->update([
                    ['path' => $field, 'value' => FieldValue::arrayUnion([$val])]
                ]);
            endforeach;
        endforeach;
        return True;
    }

    private function update_values(DocumentReference $doc_ref, array $changedArr): void {

        # Modify The Document Via `DocumentReference`
        foreach ($changedArr as $field => $value) :
            if (!is_array($value)):
                $doc_ref->update([
                    ['path' => $field, 'value' => $value]
                ]);

            else:
                $this->update_map_values($doc_ref, $changedArr, $field);

            endif;
        endforeach;
    }

    // -- Update Multiple Map Field Values -- //
    private function update_map_values(DocumentReference $doc_ref, array $mapArr, string $fieldArr): void {

        # Iterating Through Each Field In Array To Update (Inner Array)
        foreach ($mapArr[$fieldArr] as $field => $field_value) :

            # Path For Each Field In Map Data Types
            $path = $fieldArr . "." . $field;

            # Update Each Value
            $doc_ref->update([
                ['path' => $path, 'value' => $field_value]
            ]);

        endforeach;
    }

    public function get_map_field(string $collection, array $conditionArr, string $mapField): array {

        # Return The Whole Document Data
        $mapData = $this->query_exact_match($collection, $conditionArr);

        # Filter & Return Specified Map Field Datas
        return $mapData[$mapField];
    }

    private function get_sub_collection_ref(string $collection, string $subcollection, string $doc_id) {

        # Using Document ID To Get Nested Collection (Sub-Collection)
        $doc_ref = $this->db->collection($collection)->document($doc_id);
        $sub_col_ref = $doc_ref->collection($subcollection); // -- Sub Collection Reference
        return $sub_col_ref;
    }


    private function get_sub_document(string $collection, string $subcollection, array $conditionArr, array $subconditionArr) {

        # Collection Reference
        $collection_ref = $this->db->collection($collection);

        # Get DocumentSnapShot (An Array Of Documents)
        $doc_snapshot = $this->document_query($collection_ref, $conditionArr);

        # Get Document ID
        if ($doc_snapshot->exists()) :
            $doc_id = $doc_snapshot->id();
        endif;

        # Using Document ID To Get Nested Collection (Sub-Collection)
        $sub_query = $this->get_sub_collection_ref($collection, $subcollection, $doc_id);

        # Sub-Collection (Get Document With Relevant Condition In Sub Collection)
        foreach ($subconditionArr as $subcondition => $value) :
            $sub_query = $sub_query->where($subcondition, "=", $value);
        endforeach;
        $sub_snapshot = $sub_query->documents();

        # Return The DocumentSnapShot From The Sub Collection
        return $sub_snapshot;
    }

    // -- Get Nested Collection's Documents (With Conditions) - //
    public function get_nested_collection(string $collection, string $subcollection, array $conditionArr, array $subconditionArr): array {

        # -- Get DocumentSnapShot Of Sub Collection
        $sub_snapshot = $this->get_sub_document($collection, $subcollection, $conditionArr, $subconditionArr);

        # Create An Array To Store The Document Data
        $doc_arr = array();

        # Retrieve & Store The Document's Data To Array
        foreach ($sub_snapshot as $doc) :
            if ($doc->exists()) :
                $doc_arr[] = $doc->data(); //  -- Storing Each Document Data In Array
            endif;
        endforeach;

        return $doc_arr;  // -- Return Array Of Document Datas
    }

    // -- Update Nested Collection's Document -- //
    public function modify_nested_collection(string $collection, string $subcollection, array $conditionArr, array $subconditionArr, array $changedArr): bool {

        # Get Collection Rerefence
        $collection_ref = $this->db->collection($collection);

        # Get DocumentSnapShot
        $sub_snapshot = $this->get_sub_document($collection, $subcollection, $conditionArr, $subconditionArr);

        # Get DocumentID
        $doc_id = $this->document_query($collection_ref, $conditionArr)->id();

        # Iterate Through An Array Of Documents
        foreach ($sub_snapshot as $sub_doc) :

            if ($sub_doc->exists()) :

                # Getting The Document Reference
                $sub_doc_id = $sub_doc->id();
                $sub_col_ref = $this->get_sub_collection_ref($collection, $subcollection, $doc_id);
                $doc_ref = $sub_col_ref->document($sub_doc_id);

                # Update The Values Of The Retrieved DocumentReference
                $this->update_values($doc_ref, $changedArr);
                return true;

            endif;

        endforeach;

        return false;
    }

    // -- Order By (Array Of Fields To Order) --//
    private function get_ordered_by(Query $query, array $orderedBy, bool $asc): Query {
        echo nl2br(PHP_EOL . "OrderBy:" . var_dump($orderedBy));

        foreach ($orderedBy as $orderBy => $o) :
            if (is_array($orderedBy[$orderBy])):
                echo "An Array";
                $query = $this->nested_order_by($query, $orderedBy, $orderBy, $asc);
            else:
                echo nl2br(PHP_EOL . "This is o:" . $o . PHP_EOL);
                $query = $this->asc_desc($query, $o, $asc);
            endif;
        endforeach;

        return $query;
    }

    private function asc_desc(Query $query, string $orderBy, bool $asc): Query {
        if ($asc):
            $query = $query->orderBy($orderBy);
        else:
            $query = $query->orderBy($orderBy, 'DESC');
        endif;
        return $query;
    }

    private function nested_order_by(Query $query, array $orderedBy, string $orderBy, bool $asc): Query {
        foreach ($orderedBy[$orderBy] as $order => $ovalue) :
            $by = $orderBy . "." . $ovalue;
            $query = $this->asc_desc($query, $by, $asc);
        endforeach;
        return $query;
    }

    // -- Get All The Document With Certain Condition (An Array)-- //
    public function get_filtered_documents_ordered(string $collection, array $conditions, array $orderedBy, bool $asc): array {

        # -- Collection Reference -- #
        $collection_ref = $this->db->collection($collection);

        # -- Create An Array To Store The Document Data -- #
        $doc_arr = array();

        # -- DocumentSnapShots Of All The Documents -- #
        foreach ($conditions as $condition => $cvalue) :
            $query = $this->nested_condition($collection_ref, $conditions, $condition);
        endforeach;

        # -- DocumentSnapshots (Creation Of Composite Index In Google Cloud Console REQUIRED) -- #
        $query = $this->get_ordered_by($query, $orderedBy, $asc);
        $snapshot = $query->documents();

        # -- Iterate Through An Array Of Documents -- #
        foreach ($snapshot as $document) :

            if ($document->exists()) :
                $doc_arr[] = $document->data();
            endif;

        endforeach;

        return $doc_arr;
    }

    // -- Get All The Documents In A Collection -- //
    public function get_all_documents(string $collection): array {

        # Collection Reference 
        $collection_ref = $this->db->collection($collection);

        # Create An Array To Store The Document Data
        $doc_arr = array();

        # DocumentSnapshots Of All The Documents
        $documents = $collection_ref->documents();

        # Iterate Through & Add To Array
        foreach ($documents as $doc) :
            if ($doc->exists()) :
                $doc_arr[] = $doc->data();
            endif;
        endforeach;

        return $doc_arr;
    }

    // -- Gell All The Documents In A Collection In Order -- //
    public function get_all_documents_ordered(string $collection, string $orderBy): array {

        # Collection Reference 
        $collection_ref = $this->db->collection($collection);

        # Create An Array To Store The Document Data
        $doc_arr = array();

        # DocumentSnapshots Of All The Documents
        $query = $collection_ref->orderBy($orderBy);
        $documents = $query->documents();

        # Iterate Through & Add To Array
        foreach ($documents as $doc) :
            if ($doc->exists()) :
                $doc_arr[] = $doc->data();
            endif;
        endforeach;

        return $doc_arr;
    }

    // -- Get ONLY ONE Document -- //
    public function get_document_ordered(string $path, string $orderBy, bool $asc) {

        # Collection Reference 
        $collection_ref = $this->db->collection($path);

        # DocumentSnapshots Of All The Documents
        if ($asc):
            $query = $collection_ref->orderBy($orderBy)->limit(1);
        else:
            $query = $collection_ref->orderBy($orderBy, 'DESC')->limit(1);
        endif;

        $documents = $query->documents();

        # Iterate Through & Add To Array
        foreach ($documents as $doc):
            if ($doc->exists()) :
                return $doc->data();
            endif;
        endforeach;
    }

    // -- Get Nested Collection Document Ordered -- //
    public function get_documentid_ordered(string $path, array $orderBy, bool $asc) {

        # Get Nested Sub Document
        $sub_col_ref = $this->db->collection($path);
        $query = $this->get_ordered_by($sub_col_ref, $orderBy, $asc)->limit(1);
        $sub_snapshot = $query->documents();

        # Return If There Is Any Document In DocumentSnapShot
        foreach ($sub_snapshot as $sub_doc):
            if ($sub_doc->exists()):
                return $sub_doc->id();
            endif;
        endforeach;
    }

}

?>
