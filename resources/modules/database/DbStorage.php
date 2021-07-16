<?php

/*
 * @author yanying (Tracie)
 */

use Google\Cloud\Storage\StorageClient;

class DbStorage {

    const BUCKET_NAME = "fyp-21-s2-24.appspot.com";

    private $bucket;

    function __construct() {
        $storage = new StorageClient([
            'keyFile' => json_decode(file_get_contents(DB_MOD . '/json-key-tracy-001.json'), true)
        ]);

        $this->bucket = $storage->bucket(self::BUCKET_NAME);
    }

    public function get_bucket() {
        return $this->bucket->name();
    }

    // -- STORE DATA
    public function store_data(string $folder, string $filename): void {
        $this->bucket->upload(fopen($filename, 'r'), [
            'name' => $folder . '/' . $filename,
            'predefinedAcl' => 'publicRead'
        ]);
    }

    // -- RETRIEVE AND DISPLAY DATA
    public function retrieve_data_url(string $filename): ?string {
        $object_name = $this->bucket->object(urlencode($filename))->name();
        $url = "https://firebasestorage.googleapis.com/v0/b/{$this->bucket->name()}/o/{$object_name}";

        $payload = @file_get_contents($url);
        if ($payload !== false) {
            $data = json_decode($payload);
            $token = $data->downloadTokens;
        }

        return $payload === false ? null : $url . "?alt=media&token=" . $token; # -- Return URL link With Token, Else Return NULL
    }

}
