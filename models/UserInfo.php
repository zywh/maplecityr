<?php
/**
 * "{{user_info}}" 数据表模型类.
 *
 * @author        ShengHui
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class UserInfo extends XBaseModel
{
	/**
	 * @return string 相关的数据库表的名称
	 */
	public function tableName()
	{
		return '{{user_info}}';
	}

	/**
	 * @return array 对模型的属性验证规则.
	 */
	public function rules()
	{
		return array(
			array('userId, username', 'required'),
			array('username, nickname, gender, province, city, inform_type, purpose', 'length', 'max'=>50),
			array('aim_city', 'length', 'max'=>500),
			array('instruction', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userId, username, nickname, gender, province, city, inform_type, aim_city, purpose, instruction', 'safe', 'on'=>'search'),
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
			'user'=>array(self::BELONGS_TO, 'User', 'userId'),
        );
	}

	/**
	 * @return array 自定义属性标签 (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => 'ID',
			'userId'      => '用户ID',
			'username'    => '用户名',
			'phone'       => '手机号码',
			'nickname'    => '昵称',
			'gender'      => '称谓',
			'province'    => '省份',
			'city'        => '城市',
			'inform_type' => '通知类型',
			'aim_city'    => '意向城市',
			'purpose'     => '用途',
			'instruction' => '补充说明',
		);
	}


	/**
	 * 返回指定的AR类的静态模型.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
