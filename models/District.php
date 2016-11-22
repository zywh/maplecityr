<?php
/**
 * "{{district}}" 数据表模型类.
 *
 * @author        ShengHui
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class District extends XBaseModel
{
    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{district}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('name, pinyin, englishName, city_id', 'required'),
            array('name, pinyin, englishName', 'length', 'max'=>50),
            array('city_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, pinyin, englishName, city_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array 关联规则.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'city'=>array(self::BELONGS_TO, 'City', 'city_id', 'select'=>'id,name'),
        );
    }

    /**
     * @return array 自定义属性标签 (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '地区名称',
            'pinyin' => '名称拼音',
            'englishName' => '英文名称',
            'city_id' => '所在城市'
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
