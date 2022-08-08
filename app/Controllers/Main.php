<?php

namespace App\Controllers;
use App\Models\MainModel;
class Main extends BaseController
{
    public function index()
    {
        $m = new MainModel();
        $res = $m->randomHiragana();
        $p = $m->prevResult();
        foreach($res as $d){
            $data = array(
                'r_id' => $d->id,
                'hiragana' => $d->hiragana,
                'romaji' => $d->romaji,
                'prevresult' => $p
            );
        }
        return view('main', $data); 
       
    }
    public function check_answer()
    {
        $session = session();
        $m = new MainModel();
        $req = array(
            'answer' => $this->request->getVar('romaji'),
            'romaji_id' => $this->request->getVar('romaji_id')
        );
        //insert answer
        $m->insertAnswer($req);
        return redirect()->to(base_url());
    }
}
