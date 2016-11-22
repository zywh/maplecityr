<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/10
 * Time: 14:38
 */
class SMSComponent{
    private $uid;
    private $psw;
    private $cid;
    private $ch;
    function __construct($uid, $psw, $cid){
        $this->uid = trim($uid);
        $this->psw = trim($psw);
        $this->cid = trim($cid);
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, "http://api.weimi.cc/2/sms/send.html");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->ch, CURLOPT_POST, TRUE);
    }

    public function sendMsg($code, $phone, $time = 3){
        $str = 'uid='.$this->uid.'&pas='.$this->psw.'&mob='.trim($phone).'&cid='.$this->cid.'&p1='.trim($code).'&p2='.$time.'&type=json';
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $str);
        $res = curl_exec($this->ch);
        curl_close($this->ch);
        return $res;
    }
}
?>