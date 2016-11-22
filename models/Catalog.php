<?php
/**
 * "{{catalog}}" 数据表模型类.
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Model
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */
class Catalog extends XBaseModel
{
	
	/**
	 * @return string 相关的数据库表的名称
	 */
	public function tableName()
	{
		return '{{catalog}}';
	}

	/**
	 * @return array 对模型的属性验证规则.
	 */
	public function rules()
	{
		return array(
			array('catalog_name', 'required'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('catalog_name', 'length', 'max'=>50),
			array('image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, catalog_name, image', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'parent_id' => '上级分类',
			'catalog_name' => '名称',
			'image' => '缩略图'
		);
	}


	/**
	 * 返回指定的AR类的静态模型.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Catalog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 取分类
     */
	static public function get($parentid = 0, $array = array(), $level = 0, $add = 2, $repeat = '&nbsp;&nbsp;') {
        
        $str_repeat = '';
        if ($level) {
            for($j = 0; $j < $level; $j ++) {
                $str_repeat .= $repeat;
            }
        }
        $newarray = array ();
        $temparray = array ();
        foreach ( ( array ) $array as $v ) {
            if ($v ['parent_id'] == $parentid) {
                $newarray [] = array ('id' => $v['id'], 'catalog_name' => $v['catalog_name'], 'parent_id' => $v ['parent_id'], 'level' => $level, 'image' => $v['image'], 'str_repeat' => $str_repeat);
    
                $temparray = self::get ( $v['id'], $array, ($level + $add) );
                if ($temparray) {
                    $newarray = array_merge ( $newarray, $temparray );
                }
            }
        }
        return $newarray;
    }
    
    
    
    /**
     * 获取下级子类，普通模式
     *
     * @param $parentId
     * @param array $array
     * @return array
     */
    static public function lite ($parentId, array $array = array(), $params = array())
    {
        if(empty($parentId))
            return ;
        $eachArr = empty($array)? XXcache::system('_catalog', 86400): $array;
        foreach ((array)$eachArr as $row) {
            if ($row['parent_id'] == $parentId)
                $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 取子类连接
     * @param $parentId
     * @param array $array
     */
    static public function subArr2str ($parentId, array $array = array(), $self = true)
    {
         if(empty($parentId))
            return ;
        $eachArr = empty($array)? XXcache::system('_catalog', 86400): $array;
        foreach ((array)$eachArr as $row) {
            if ($row['parent_id'] == $parentId)
                $arr[] = $row['id'];
        }
        $string = implode(',', $arr);
        return $self ? $string . ',' . $parentId : $string;
    
    }

    /**
     * 取分类名称
     *
     * @param $parentId
     * @param array $array
     * @return string
     */
    static public function name ($catalog, array $array = array())
    {
         if(empty($catalog))
            return ;
        $eachArr = empty($array)? XXcache::system('_catalog', 86400): $array;
        foreach ((array)$eachArr as $row) {
            if ($row['id'] == $catalog)
                $name = $row['catalog_name'];
        }
        return $name;
    }

    /**
     * 根据项目名称取类别ID
     * @param $catalog
     * @param array $array
     * @return unknown
     */
    static public function alias2idArr ($alias, array $array = array())
    {
        if(empty($alias))
            return ;
        $eachArr = empty($array)? XXcache::system('_catalog', 86400): $array;
        foreach ((array)$eachArr as $row) {
            if ($row['catalog_name'] == $alias)
                return $row;
        }
    
    }

    /**
     * 取单条记录
     * @param $catalog
     * @param array $array
     */
    static public function item ($id, array $array = array())
    {
        if(empty($id))
            return ;
        $eachArr = empty($array)? XXcache::system('_catalog', 86400): $array;
        foreach ((array)$eachArr as $row) {
            if ($row['id'] == $id)
                return $row;
        }
    }
}
