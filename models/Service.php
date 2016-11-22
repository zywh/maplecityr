<?php
/**
 * Created by PhpStorm.
 * User: ShengHui
 * Date: 2015/3/4
 * Time: 21:06
 */
class Service extends XBaseModel
{

    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{service}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('title, image, summary, content', 'required'),
            array('title, image', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, image, summary, content, create_time, last_update_time', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array 自定义属性标签 (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'               => 'ID',
            'title'            => '服务名称',
            'image'            => '图片',
            'summary'          => '简介',
            'content'          => '服务内容',
            'create_time'      => '创建时间',
            'last_update_time' => '最新更新时间',
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
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->create_time = time();
            $this->last_update_time = time();
        } else {
            $this->last_update_time = time();
        }
        return true;
    }
}