<?php
/**
 * "{{user}}" 数据表模型类.
 *
 * @author        ShengHui
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class User extends XBaseModel
{
	public $password_repeat;
	public $sms_code;
//	public $captcha;
	/**
	 * @return string 相关的数据库表的名称
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array 对模型的属性验证规则.
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required', 'on'=>'login'),
			array('username, email, phone, password, sms_code', 'required', 'on'=>'create'),
		    array('username', 'unique', 'on'=>'create', 'message'=>'该用户名已被注册'),
			array('phone', 'unique', 'on'=>'create', 'message'=>'该手机号已被注册'),
			array('email', 'email'),
			array('email', 'unique', 'on'=>'create', 'message'=>'该邮箱已被注册'),
			array('password_repeat', 'compare', 'compareAttribute'=>'password', 'on'=>'create', 'message'=>'密码确认不正确'),
//			array('captcha', 'captcha', 'allowEmpty'=>!extension_loaded('gd'), 'on'=>'login'),
			array('username, realname, phone, register_ip, last_login_ip', 'length', 'max'=>50),
			array('password, email, portrait', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, email, realname, phone, portrait, status_is, register_ip, login_count, last_login_time, last_login_ip, last_update_time, create_time', 'safe', 'on'=>'search'),
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
        );
	}

	/**
	 * @return array 自定义属性标签 (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'               => '用户id',
			'username'         => '用户名',
			'password'         => '密码',
			'password_repeat'  => '确认密码',
			'sms_code'         => '验证码',
			'realname'         => '真实姓名',
			'email'            => '邮箱',
			'phone'            => '手机号',
			'portrait'         => '头像',
			'status_is'        => '用户状态',
			'register_ip'      => '注册IP',
			'login_count'      => '登录次数',
			'last_login_time'  => '最后登录时间',
			'last_login_ip'    => '最后登录IP',
			'last_update_time' => '最后更新时间',
			'create_time'      => '注册时间',
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

	/**
	 * 数据保存前处理
	 * @return boolean.
	 */
	protected function beforeSave ()
	{
		$this->last_login_time = time();
		if ($this->isNewRecord) {
			$this->password = md5($this->password);
			$this->create_time = time();
		}
		return true;
	}
}
