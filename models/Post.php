<?php
/**
 * "{{post}}" 数据表模型类.
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class Post extends XBaseModel
{
	
	/**
	 * @return string 相关的数据库表的名称
	 */
	public function tableName()
	{
		return '{{post}}';
	}

	/**
	 * @return array 对模型的属性验证规则.
	 */
	public function rules()
	{
		return array(
			array('title, content, summary, catalog_id, view_count, author,taaag', 'required'),
			array('catalog_id', 'numerical', 'integerOnly'=>true),
			array('last_update_time, create_time, view_count', 'length', 'max'=>10),
			array('author,taaag', 'length', 'max'=>50),
			array('title, image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, content, summary, catalog_id, author, image, view_count, last_update_time, create_time,taaag', 'safe', 'on'=>'search'),
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
	        'catalog'=>array(self::BELONGS_TO, 'Catalog', 'catalog_id'),
	    );
	}

	/**
	 * @return array 自定义属性标签 (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'               => 'ID',
			'author'           => '作者',
			'title'            => '标题',
			'catalog_id'       => '分类',
			'content'          => '内容',
			'summary'          => '摘要',
			'view_count'       => '查看次数',
			'image'            => '缩略图',
			'last_update_time' => '最后更新时间',
			'create_time'      => '录入时间',
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

	/**
	 * 入库前自动处理
	 */
	public function beforeSave ()
    {
       	parent::beforeSave();
        return true;
    }
}
