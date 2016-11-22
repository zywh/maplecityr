<?php
/**
 * "{{collect}}" 数据表模型类.
 *
 * @author        ShengHui
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class Requirement extends XBaseModel
{
    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{requirement}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('phone', 'required', 'message'=>'请输入手机号后提交'),
            array('email', 'required', 'message'=>'请输入邮箱后提交'),
            array('status', 'required'),
            array('city_id, district_id, investType_id, propertyType_id', 'numerical', 'integerOnly'=>true),
            array('email', 'email', 'message'=>'不是有效的邮箱地址'),
            array('total_price, house_area, land_area, bedroom_num, construction_year, phone, email', 'length', 'max'=>50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, city_id, district_id, investType_id, propertyType_id, total_price, house_area, land_area, bedroom_num, construction_year, phone, email, status', 'safe', 'on'=>'search'),
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
            'district'=>array(self::BELONGS_TO, 'District', 'district_id', 'select'=>'id,name'),
            'investType'=>array(self::BELONGS_TO, 'InvestType', 'investType_id', 'select'=>'id,name'),
            'propertyType'=>array(self::BELONGS_TO, 'PropertyType', 'propertyType_id', 'select'=>'id,name'),
        );
    }

    /**
     * @return array 自定义属性标签 (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'                => 'ID',
            'city_id'           => '城市',
            'district_id'       => '地区',
            'investType_id'     => '投资类型',
            'propertyType_id'   => '物业类型',
            'total_price'       => '总价',
            'house_area'        => '房屋面积',
            'land_area'         => '土地面积',
            'bedroom_num'       => '卧室数量',
            'construction_year' => '建造年份',
            'phone'             => '手机',
            'email'             => '邮箱',
            'status'            => '是否处理',
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
