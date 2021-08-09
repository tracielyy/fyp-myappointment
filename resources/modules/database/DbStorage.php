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

    // -- COMPRESS THE IMAGES
    private function compress_image(string $src_url, string $dest_url, int $quality /* 0 to 100 */) {
        $info = getimagesize($src_url);
        $info_mime = $info['mime'];
        switch ($info_mime):
            case 'image/jpeg':
                $img = imagecreatefromjpeg($src_url);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($src_url);
                break;
            case 'image/png':
                $img = imagecreatefrompng($src_url);
                break;
        endswitch;

        imagejpeg($img, $src_url, $quality);
    }

    // -- STORE DATA
    public function store_data(string $filetype, int $size, string $filename_from, string $filename_to): void {
        if (($filetype == "image/gif") || ($filetype == "image/jpeg") || ($filetype == "image/png") || ($filetype == "image/pjpeg")) {
            // Compress If More Than or Equal To 1mb
            if ($size >= 1000.00) {
                $this->compress_image($filename_from, $filename_to, 50);
            }
        }
        $this->bucket->upload(fopen($filename_from, 'r'), [
            'name' => $filename_to,
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

    // -- DELETE DATA 
    public function delete_data(string $filename) {
        $object = $this->bucket->object($filename);
        $object->delete();
    }

}
