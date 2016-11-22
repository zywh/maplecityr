<?php

/**
 * This is the model class for table "{{mls_hist}}".
 *
 * The followings are the available columns in table '{{mls_hist}}':
 * @property string $date
 * @property integer $sales
 * @property integer $dollar
 * @property integer $avg_price
 * @property integer $new_list
 * @property string $snlr
 * @property integer $active_list
 * @property string $moi
 * @property integer $avg_dom
 * @property string $avg_splp
 * @property string $type
 */
class MlsHist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mls_hist}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, sales, dollar, avg_price, new_list, snlr, active_list, moi, avg_dom, avg_splp, type', 'required'),
			array('sales, dollar, avg_price, new_list, active_list, avg_dom', 'numerical', 'integerOnly'=>true),
			array('snlr, moi, avg_splp', 'length', 'max'=>11),
			array('type', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('date, sales, dollar, avg_price, new_list, snlr, active_list, moi, avg_dom, avg_splp, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'date' => 'Date',
			'sales' => 'Sales',
			'dollar' => 'Dollar',
			'avg_price' => 'Avg Price',
			'new_list' => 'New List',
			'snlr' => 'Snlr',
			'active_list' => 'Active List',
			'moi' => 'Moi',
			'avg_dom' => 'Avg Dom',
			'avg_splp' => 'Avg Splp',
			'type' => 'Type',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('date',$this->date,true);
		$criteria->compare('sales',$this->sales);
		$criteria->compare('dollar',$this->dollar);
		$criteria->compare('avg_price',$this->avg_price);
		$criteria->compare('new_list',$this->new_list);
		$criteria->compare('snlr',$this->snlr,true);
		$criteria->compare('active_list',$this->active_list);
		$criteria->compare('moi',$this->moi,true);
		$criteria->compare('avg_dom',$this->avg_dom);
		$criteria->compare('avg_splp',$this->avg_splp,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MlsHist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
