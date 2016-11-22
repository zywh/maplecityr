<?php

/**
 * This is the model class for table "{{school}}".
 *
 * The followings are the available columns in table '{{school}}':
 * @property integer $id
 * @property string $school
 * @property string $paiming
 * @property double $pingfen
 * @property string $xingzhi
 * @property string $tel
 * @property string $address
 * @property integer $type
 * @property string $lat
 * @property string $lng
 * @property string $language
 * @property string $city
 * @property string $province
 * @property string $zip
 * @property string $email
 * @property string $grade
 * @property string $schoolnumber
 * @property string $url
 */
class School extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{school}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lat, lng, language, city, province, zip, email, grade, schoolnumber, url', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('pingfen, paiming', 'numerical'),
			array('school, xingzhi, tel', 'length', 'max'=>100),
			array('address', 'length', 'max'=>255),
			array('lat, lng', 'length', 'max'=>8),
			array('language, city, province, zip, email, grade, schoolnumber', 'length', 'max'=>20),
			array('url', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, school, paiming, pingfen, xingzhi, tel, address, type, lat, lng, language, city, province, zip, email, grade, schoolnumber, url', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'school' => 'School',
			'paiming' => 'Paiming',
			'pingfen' => 'Pingfen',
			'xingzhi' => 'Xingzhi',
			'tel' => 'Tel',
			'address' => 'Address',
			'type' => 'Type',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'language' => 'Language',
			'city' => 'City',
			'province' => 'Province',
			'zip' => 'Zip',
			'email' => 'Email',
			'grade' => 'Grade',
			'schoolnumber' => 'Schoolnumber',
			'url' => 'Url',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('school',$this->school,true);
		$criteria->compare('paiming',$this->paiming,true);
		$criteria->compare('pingfen',$this->pingfen);
		$criteria->compare('xingzhi',$this->xingzhi,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('grade',$this->grade,true);
		$criteria->compare('schoolnumber',$this->schoolnumber,true);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return School the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
