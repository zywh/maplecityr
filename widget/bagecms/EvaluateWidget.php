<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/2
 * Time: 11:41
 */
class EvaluateWidget extends CWidget{
    public function run(){
        $city = City::model()->findAll();
        $type = InvestType::model()->findAll();
        $aim = InvestAim::model()->findAll();
        $data = array(
            'city' => $city,
            'type' => $type,
            'aim'  => $aim
        );
        $this->render('evaluate', $data);
    }
}