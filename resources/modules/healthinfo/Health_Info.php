<?php

/*
 *  @author: tracieqwynn
 */

require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

class Health_Info {

    private string $id;
    private string $title;   /* Editable */
    private string $descriptions;  /* Editable */
    private string $type; /* NON Editable */
    private Time $updatedon;
    private Time $createdon;

    public function __construct(Time $createdon, Time $updatedon, string $id, string $title, string $descriptions, string $type) {
        $this->createdon = $createdon;
        $this->updatedon = $updatedon;
        $this->id = $id;
        $this->title = $title;
        $this->descriptions = $descriptions;
        $this->type = $type;
    }

    public function get_id(): string {
        return $this->id();
    }

    public function get_title(): string {
        return $this->title;
    }

    public function get_descriptions(): string {
        return $this->descriptions;
    }

    public function get_type(): string {
        return $this->type();
    }

    public function get_createdon(): Time {
        return $this->createdon;
    }

    public function get_updatedon(): Time {
        return $this->updatedon;
    }

    public static function initialise_health_info(array $health_info): Health_Info {

        # Time Object
        $createdon_obj =  Time::initialise_time($health_info['createdon']);
        $updatedon_obj = Time::initialise_time($health_info['updatedon']);
        
        # Health Info Object
        $health_info_obj = new Health_Info($createdon_obj, $updatedon_obj, $health_info['id'], $health_info['title'],
                $health_info['descriptions'], $health_info['type']);
        
        return $health_info_obj;
    }

    /*
     * Database Access Function
     */

    # FETCH (RETRIEVE)
    public static function retrieve_all_healthinfo(string $startAfter = null): array {

        # Create Health Tips
        $health_info_arr = array();

        $db = new DbQuery();
        $ref = $db->get_db()->collection(Database::HEALTH_INFO)->orderBy('id');

        if ($startAfter == null):
            # Beginning Query 
            $arr = $ref->limit(10)->documents();
        else:
            # Consecutive Query
            $arr = $ref->startAfter($startAfter)->limit(10)->documents();
        endif;
        # Loop & Add To Container
        foreach ($arr As $doc):
            if ($doc->exists()):
                $doc_data = $doc->data();
//               $health_info_arr[] 
            endif;
        endforeach;
    }
    
    public static function generate_id(): string {
        
        # To OrderBy The Id
        $orderBy = array('id');
        
        # Find The Last ID & Increment
        $db = new DbQuery();
        $path = Database::HEALTH_INFO;
        $last_id = $db->get_first_id_ordered($path, $orderBy, false);
        
        # If There Is Any Present ID In Database
        if($last_id_arr != null):
            return ++$last_id;
        endif;
        return "hinfo-10001";
    }
    

    # INSERT (CREATE)
    public static function create_healthinfo(array $health_info_data): void {

        $db = new DbQuery();
        
        # SET The Timing
        $createdon = new Time();
        
        # Get Id
        $id = self::generate_id();
        
    }

    # MODIFY (UPDATE)
    public static function update_healthinfo(string $id, string $title, string $descriptions): bool {
        $db = new DbQuery();
        $ref = $db->get_db()->collection(Database::HEALTH_INFO)->document($id);
        $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
        use ($ref, $title, $descriptions) {
            $updatedon = new Time();

            if ($title !== null && $descriptions !== null):
                $transaction->update($ref, [
                    ['path' => 'title', 'value' => $title],
                    ['path' => 'descriptions', 'value' => $descriptions],
                    ['path' => 'updatedon.date', 'value' => $updatedon->get_date()],
                    ['path' => 'updatedon.time', 'value' => $updatedon->get_time()]
                ]);
                return true; # Values Got Updated
            endif;
            return false; # Not Updated
        });

        return $trnx_result;
    }

    # DELETE
    public static function delete_healthinfo(string $id): void {
        $db = new DbQuery();
        $db->get_db()->collection(Database::HEALTH_INFO)->document($id)->delete();
    }

}
?>

