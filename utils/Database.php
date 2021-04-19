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

    // Get unique Firestore document in specific collection
    function getDocument($collection, $id) {
        $collectionRef = $this->db->collection($collection);
        $docRef = $collectionRef->document($id);
        $snapshot = $docRef->snapshot();
        if ($snapshot->exists()) {
            return $snapshot->data();
        } else {
            return NULL;
        }
    }

}

?>
