<?php
/**
 * 内容管理
 * 
 * @author        ShengHui
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.admini.Controller
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */

class HouseController extends XAdminiBase
{
    protected $subject_list;
    protected $city_list;
    protected $district_list;
    protected $investType_list;
    protected $propertyType_list;
    protected $match_list;
    /**
     * 首页
     *
     */
    public function actionIndex() {

        parent::_acl('house_index');
        $model = new House();
        $criteria = new CDbCriteria();
        $condition = '1';
        
        $city_id = $this->_gets->getParam( 'city_id' );
        $district_id = $this->_gets->getParam( 'district_id' );
        //$investType_id = $this->_gets->getParam( 'investType_id' );
        $propertyType_id = $this->_gets->getParam( 'propertyType_id' );
        $h_name = trim( $this->_gets->getParam( 'h_name' ) );
        $is_recommend = $this->_gets->getParam( 'is_recommend' );

        $city_id && $condition .= ' AND city_id= ' . $city_id;
        $district_id && $condition .= ' AND district_id= ' . $district_id;
        //$investType_id && $condition .= ' AND investType_id= ' . $investType_id;
if($propertyType_id==1){
$propertyType_id && $condition .= ' AND type_own1_out="Detached" ';
}
elseif($propertyType_id==2){
$propertyType_id && $condition .= ' AND type_own1_out="Townhouse" or type_own1_out="Att∕Row∕Twnhouse" or type_own1_out="Triplex" or type_own1_out="Fourplex" or type_own1_out="Multiplex"';
}
elseif($propertyType_id==3){
$propertyType_id && $condition .= ' AND lp_dol>3000000';
}	
elseif($propertyType_id==4){
$propertyType_id && $condition .= ' AND type_own1_out="Semi-Detached" or type_own1_out="Link" or type_own1_out="Duplex"';
}
elseif($propertyType_id==5){
$propertyType_id && $condition .= ' AND type_own1_out="Cottage" or type_own1_out="Rural Resid"';
}
elseif($propertyType_id==6){
$propertyType_id && $condition .= ' AND type_own1_out="Farm"';
}
elseif($propertyType_id==7){
$propertyType_id && $condition .= ' AND type_own1_out="Vacant Land"';
}
elseif($propertyType_id==8){
$propertyType_id && $condition .= ' AND type_own1_out="Mobile/Trailer" or type_own1_out="Det W/Com Elements" or type_own1_out="Store W/Apt/offc"';
}

        $h_name && $condition .= ' AND addr LIKE \'%' . $h_name . '%\' or ml_num LIKE \'%' . $h_name . '%\'';
        $is_recommend && $condition .= ' AND recommend= ' . $is_recommend;

        $criteria->condition = $condition;
        $criteria->order = 't.id DESC';
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $pageParams = XUtils::buildCondition( $_GET, array ( 'city_id, district_id, type_own1_out, addr, recommend' ) );
        $pages->params = is_array( $pageParams ) ? $pageParams : array ();
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );

        $this->subject_list = parent::_groupList('subject');
        $this->city_list = parent::_groupList('city');
        $this->district_list = parent::_groupList('district');
        $this->investType_list = parent::_groupList('investType');
        $this->propertyType_list = parent::_groupList('propertyType');

        $this->render( 'index',array('datalist'=>$result, 'pagebar' => $pages));
    }

    /**
     * 录入
     *
     */
    public function actionCreate() {
        parent::_acl('house_create');
        $original = Yii::app()->request->getQuery('original');
        $model = new House();
        $imageList = $this->_gets->getPost( 'imageList' );
        $imageListSerialize = XUtils::imageListSerialize($imageList);
        if ( isset( $_POST['House'] ) ) {
            $model->attributes = $_POST['House'];
            $model->accessDate = strtotime($_POST['House']['accessDate']);
            $model->match = implode(',', $_POST['House']['match']);
            $file = XUpload::upload( $_FILES['house_image'] );
            if ( is_array( $file ) ) {
                $model->house_image = $file['pathname'];
            }
            $model->image_list = $imageListSerialize['dataSerialize'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入房源信息,ID:' . $model->id ) );
                if(!empty($original)){
                    $this->redirect($original);
                }else{
                    $this->redirect(array('index'));
                }
            }
        }

        $this->subject_list = parent::_groupList('subject');
        $this->city_list = parent::_groupList('city');
        $this->district_list = parent::_groupList('district');
        $this->investType_list = parent::_groupList('investType');
        $this->propertyType_list = parent::_groupList('propertyType');
        $this->match_list = parent::_groupList('match');

        $this->render( 'create', array ( 'model' => $model, 'imageList'=>$imageListSerialize['data'], 'original'=>$original) );
    }

    /**
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('house_update');
        $original = Yii::app()->request->getQuery('original');
        $model = parent::_dataLoad( new House(), $id );
        $imageList = $this->_gets->getParam( 'imageList' );
        $imageListSerialize = XUtils::imageListSerialize($imageList);
        if ( isset( $_POST['House'] ) ) {
            $model->attributes = $_POST['House'];
            $model->accessDate = strtotime($_POST['House']['accessDate']);
            $model->match = implode(',', $_POST['House']['match']);
            $file = XUpload::upload( $_FILES['house_image'] );
            if ( is_array( $file ) ) {
                $model->house_image = $file['pathname'];
                @unlink( $_POST['oAttach'] );
            }
            $model->image_list = $imageListSerialize['dataSerialize'];
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑房源信息,ID:' . $id ) );
                if(!empty($original)){
                    $this->redirect($original);
                }else{
                    $this->redirect(array('index'));
                }
            }
        }
        if ( $imageList ) {
            $imageList = $imageListSerialize['data'];
        }
        elseif($model->image_list) {
            $imageList = unserialize($model->image_list);
        }

        $this->subject_list = parent::_groupList('subject');
        $this->city_list = parent::_groupList('city');
        $this->district_list = parent::_groupList('district');
        $this->investType_list = parent::_groupList('investType');
        $this->propertyType_list = parent::_groupList('propertyType');
        $this->match_list = parent::_groupList('match');

        $this->render( 'update', array ( 'model' => $model, 'imageList'=>$imageList, 'original'=>$original) );

    }

    /**
     * 房源推荐
     *
     */
    public function actionRecommend() {
        if(parent::_ajax_acl('house_recommend')){
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad( new House(), $id );
            if($model->recommend == 0){
                $model->recommend = 1;
            }else{
                $model->recommend = 0;
            }
            if($model->save()){
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新房源推荐状态,ID:' . $id ) );
                echo CJSON::encode(array('status'=>'success'));
            }else{
                echo CJSON::encode(array('status'=>'failed'));
            }
        }else{
            echo CJSON::encode(array('status'=>'forbid'));
        }
    }

    /**
     * 房源出售/停售
     *
     */
    public function actionSell() {
        if(parent::_ajax_acl('house_update')) {
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad(new House(), $id);
            if ($model->is_sell == 0) {
                $model->is_sell = 1;
            } else {
                $model->is_sell = 0;
            }
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新房源出售状态,ID:' . $id ) );
                echo CJSON::encode(array('status' => 'success'));
            } else {
                echo CJSON::encode(array('status' => 'failed'));
            }
        }else{
            echo CJSON::encode(array('status'=>'forbid'));
        }
    }

    /**
     * 批量操作
     *
     */
    public function actionBatch() {
        if ( XUtils::method() == 'GET' ) {
            $command = trim( $_GET['command'] );
            $ids = intval( $_GET['id'] );
        } elseif ( XUtils::method() == 'POST' ) {
            $command = trim( $_POST['command'] );
            $ids = $_POST['id'];
            is_array( $ids ) && $ids = implode( ',', $ids );
        } else {
            XUtils::message( 'errorBack', '只支持POST,GET数据' );
        }
        empty( $ids ) && XUtils::message( 'error', '未选择记录' );

        switch ( $command ) {
            case 'delete':
                parent::_acl( 'house_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除房源，ID:' . $ids ) );
                parent::_delete( new House(), $ids, array ( 'index' ));
                break;
            case 'commend':
                parent::_acl( 'house_recommend' );
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '批量推荐房源，ID:' . $ids ) );
                parent::_recommend( new House(), 'recommend', $ids, array ( 'index' ) );
                break;
            case 'unCommend':
                parent::_acl( 'house_recommend' );
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '批量取消房源推荐，ID:' . $ids ) );
                parent::_recommend( new House(), 'unRecommend', $ids, array ( 'index' ) );
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}
