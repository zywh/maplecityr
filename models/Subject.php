<?php
/**
 * "{{subject}}" 数据表模型类.
 *
 * @author        ShengHui
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class Subject extends XBaseModel
{

    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{subject}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('name, city_id, summary, date, point, developer_intro, image_list, room_type_image, recommend', 'required'),
            array('name, room_type_image', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, city_id, date, summary, point, developer_intro, image_list, room_type_image, recommend', 'safe', 'on'=>'search'),
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
            'city'=>array(self::BELONGS_TO, 'City', 'city_id'),
        );
    }

    /**
     * @return array 自定义属性标签 (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'              => 'ID',
            'name'            => '项目名称',
            'city_id'         => '城市',
            'date'            => '项目时间',
            'summary'         => '项目概况',
            'point'           => '项目重点',
            'developer_intro' => '开发商介绍',
            'image_list'      => '项目组图',
            'room_type_image' => '房型图',
            'recommend'       => '热点推荐',
        );
    }


    /**
     * 返回指定的AR类的静态模型.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Page the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}
