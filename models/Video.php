<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/11
 * Time: 11:07
 */
class Video extends XBaseModel
{
    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{video}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('title, url, describe, date, chosen, home', 'required'),
            array('title, url', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, url, describe, date, chosen, home', 'safe', 'on'=>'search'),
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
            'id'       => 'ID',
            'title'    => '视频名称',
            'url'      => '视频路径',
            'describe' => '视频描述',
            'date'     => '上传时间',
            'chosen'   => '是否精选',
            'home'     => '是否首页播放'
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