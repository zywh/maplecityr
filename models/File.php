<?php
/**
 * Created by PhpStorm.
 * User: ShengHui
 * Date: 2015/3/4
 * Time: 20:49
 */
class File extends XBaseModel
{

    /**
     * @return string 相关的数据库表的名称
     */
    public function tableName()
    {
        return '{{file}}';
    }

    /**
     * @return array 对模型的属性验证规则.
     */
    public function rules()
    {
        return array(
            array('name, path, recommend', 'required'),
            array('name, image', 'length', 'max'=>255),
            array('path', 'length', 'max'=>500),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, path, image, upload_time, recommend', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array 自定义属性标签 (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'          => 'ID',
            'name'        => '文件名称',
            'path'        => '文件路径',
            'image'       => '图片',
            'upload_time' => '上传时间',
            'recommend'   => '是否推荐',
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
        $this->upload_time = time();
        return true;
    }
}