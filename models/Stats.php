<?php

/**
 * This is the model class for table "{{stats}}".
 *
 * The followings are the available columns in table '{{stats}}':
 * @property string $date
 * @property integer $t_resi
 * @property integer $t_condo
 * @property integer $u_resi
 * @property integer $u_condo
 * @property integer $avg_price
 * @property integer $t_crea
 * @property integer $avg_crea
 * @property integer $t_house
 * @property integer $avg_house
 * @property integer $u_house
 */
class Stats extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{stats}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, t_resi, t_condo, u_resi, u_condo, avg_price, t_crea, avg_crea, t_house, avg_house, u_house', 'required'),
			array('t_resi, t_condo, u_resi, u_condo, avg_price, t_crea, avg_crea, t_house, avg_house, u_house', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('date, t_resi, t_condo, u_resi, u_condo, avg_price, t_crea, avg_crea, t_house, avg_house, u_house', 'safe', 'on'=>'search'),
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
			't_resi' => 'Total Active Resi',
			't_condo' => 'Total Active Condo',
			'u_resi' => 'Updated Resi',
			'u_condo' => 'Updated Condo',
			'avg_price' => 'Avg Price',
			't_crea' => 'CREA Total',
			'avg_crea' => 'CREA Average Price',
			't_house' => 'Total House',
			'avg_house' => 'Average House Price',
			'u_house' => 'House Update',
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
		$criteria->compare('t_resi',$this->t_resi);
		$criteria->compare('t_condo',$this->t_condo);
		$criteria->compare('u_resi',$this->u_resi);
		$criteria->compare('u_condo',$this->u_condo);
		$criteria->compare('avg_price',$this->avg_price);
		$criteria->compare('t_crea',$this->t_crea);
		$criteria->compare('avg_crea',$this->avg_crea);
		$criteria->compare('t_house',$this->t_house);
		$criteria->compare('avg_house',$this->avg_house);
		$criteria->compare('u_house',$this->u_house);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
