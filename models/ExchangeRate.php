<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/9
 * Time: 13:21
 */
class ExchangeRate extends XBaseModel
{
    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{exchange_rate}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('rate', 'required'),
            array('rate', 'length', 'max'=>50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, rate', 'safe', 'on'=>'search'),
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
            'rate'   => '汇率',
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