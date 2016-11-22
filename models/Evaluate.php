<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/2
 * Time: 18:00
 */
class Evaluate extends XBaseModel
{
    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{evaluate}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('name, phone, status', 'required'),
            array('city, type, aim, prepay, name, phone', 'length', 'max'=>50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('city, type, aim, prepay, name, phone, status', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array 关联规则.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array 自定义属性标签 (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'     => 'ID',
            'city'   => '意向城市',
            'type'   => '投资类型',
            'aim'    => '投资目的',
            'prepay' => '首付预算',
            'name'   => '称谓',
            'phone'  => '回拨电话',
            'status' => '是否处理',
        );
    }


    /**
     * 返回指定的AR类的静态模型.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}