<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class MainModel extends Model
{
    


    public function randomHiragana(){
        $query = $this->db->query('SELECT id,hiragana,romaji FROM romaji ORDER BY rand() LIMIT 1')->getResult();
        return $query;
    }
    public function insertAnswer($req){
        $i = $this->db->table('answer')->insert($req);
        if($i){
            $this->checkAnswer($req);
        }
    }
    public function checkAnswer($req){
        $session = session();
        //get answer
        $answer = $req['answer'];
        $romaji_id = $req['romaji_id'];
        $q = $this->db->query("SELECT id,romaji FROM romaji WHERE id = $romaji_id AND romaji = '$answer' LIMIT 1 ")->getResult();
        if($q){
            //update status
            $this->db->query("UPDATE answer SET status=1 ORDER BY id DESC LIMIT 1 ");
            $session->setFlashdata('result', 'Correct');
        }else{
            $session->setFlashdata('result', 'Wrong');

            $a = $this->db->query("SELECT hiragana,romaji FROM romaji WHERE id = '$romaji_id' LIMIT 1 ")->getResult();
            $res = $a[0];
            $session->setFlashdata('c_hiragana', $res->hiragana);
            $session->setFlashdata('c_romaji', $res->romaji);
        }
    }
    public function prevResult(){
        return $this->db->query("SELECT a.id as correct_id, a.answer, b.romaji, b.hiragana, a.status FROM answer a JOIN romaji b ON a.romaji_id = b.id ORDER BY a.id DESC LIMIT 10 ")->getResult();
    }
}