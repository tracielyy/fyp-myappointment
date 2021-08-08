<?php

/*
 *  @author: tracieqwynn
 */
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';

use Google\Cloud\Firestore\Transaction;

class Faq {

    private string $question;
    private string $answer;
    private string $id;
    private Time $updatedon;
    private Time $createdon;

    public function __construct(Time $createdon, Time $updatedon, string $id, string $question, string $answer) {
        $this->createdon = $createdon;
        $this->updatedon = $updatedon;
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
    }
    
    public function get_id(): string {
        return $this->id;
    }

    public function get_question(): string {
        return $this->question;
    }

    public function get_answer(): string {
        return $this->answer;
    }

    public function get_createdon(): Time {
        return $this->createdon;
    }

    public function get_updatedon(): Time {
        return $this->updatedon;
    }

    public static function initialise_faq(array $faq_info): null|Faq {
        try {
            # Time Object
            $createdon_obj = Time::initialise_time($faq_info['createdon']);
            $updatedon_obj = Time::initialise_time($faq_info['updatedon']);

            # Faq Info Object
            $faq_info_obj = new Faq($createdon_obj, $updatedon_obj, $faq_info['id'], $faq_info['question'],
                    $faq_info['answer']);

            return $faq_info_obj;
        } catch (Exception $ex) {
            return null;
        }
    }

// Database
    public static function create_faq(array $faq): void {

        $db = new DbQuery();

        # SET The Timing
        $createdon = new Time();
        $createdon_arr = array('date' => $createdon->get_date(), 'time' => $createdon->get_time());
        $faq['createdon'] = $faq['updatedon'] = $createdon_arr;

        $doc_ref = $db->get_db()->collection(Database::FAQ)->newDocument();
        $faq['id'] = $doc_ref->id();
        $doc_ref->set($faq);
    }

    public static function update_faq(string $id, string $question, string $answer): bool {
        $db = new DbQuery();
        $ref = $db->get_db()->collection(Database::FAQ)->document($id);
        $trnx_result = $db->get_db()->runTransaction(function (Transaction $transaction)
        use ($ref, $question, $answer) {
            $updatedon = new Time();

            if ($question !== null && $answer !== null):
                $transaction->update($ref, [
                    ['path' => 'question', 'value' => $question],
                    ['path' => 'answer', 'value' => $answer],
                    ['path' => 'updatedon.date', 'value' => $updatedon->get_date()],
                    ['path' => 'updatedon.time', 'value' => $updatedon->get_time()]
                ]);
                return true; # Values Got Updated
            endif;
            return false; # Not Updated
        });
        return $trnx_result;
    }

    //  RETRIEVE HEALTH INFO BY ID  //
    public static function retrieve_faq_by_id(string $id): null|Faq {
        $db = new DbQuery();
        $faq_info = $db->fetch_document_by_id(Database::FAQ, $id);
        if ($faq_info !== null):
            return self::initialise_faq($faq_info);
        endif;
        return $faq_info;
    }

    //  FETCH (RETRIEVE)  // 
    public static function retrieve_all_faqs(string $startAfter = null): array {

        $faq_arr = array();

        $db = new DbQuery();
        $ref = $db->get_db()->collection(Database::FAQ)->orderBy('id', 'DESC');

        $limit = 10;

        if ($startAfter == null):
            # Beginning Query 
            $arr = $ref->limit($limit)->documents();
        else:
            # Consecutive Query
            $arr = $ref->startAfter($startAfter)->limit($limit)->documents();
        endif;
        # Loop & Add To Container
        foreach ($arr As $doc):
            if ($doc->exists()):
                $doc_data = $doc->data();
                $faq_arr[] = self::initialise_faq($doc_data);
            endif;
        endforeach;
        return $faq_arr;
    }

    public static function delete_faq(string $id): void {
        $db = new DbQuery();
        $db->get_db()->collection(Database::FAQ)->document($id)->delete();
    }

}
