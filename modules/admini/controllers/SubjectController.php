<?php
/**
 * 内容管理
 *
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.admini.Controller
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */

class SubjectController extends XAdminiBase
{
    protected $city_list;
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('subject_index');
        $model = new Subject();
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );
        $this->render( 'index', array ( 'datalist' => $result , 'pagebar' => $pages ) );
    }

    /**
     * 录入
     *
     */
    public function actionCreate() {
        parent::_acl('subject_create');
        $model = new Subject();
        $imageList = $this->_gets->getPost( 'imageList' );
        $imageListSerialize = XUtils::imageListSerialize($imageList);
        if ( isset( $_POST['Subject'] ) ) {
            $file = XUpload::upload( $_FILES['attach'] );
            $model->attributes = $_POST['Subject'];
            $model->date = strtotime($_POST['Subject']['date']);
            if ( is_array( $file ) ) {
                $model->room_type_image = $file['pathname'];
            }
            $model->image_list = $imageListSerialize['dataSerialize'];
            if ($model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入项目,ID:' . $model->id ) );
                $this->redirect( array ( 'index' ) );
            }
        }
        $this->city_list = parent::_groupList('city');
        $this->render( 'create', array ( 'model' => $model, 'imageList'=>$imageListSerialize['data'] ) );
    }

    /**
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('subject_update');
        $model = parent::_dataLoad( new Subject(), $id );
        $imageList = $this->_gets->getParam( 'imageList' );
        $imageListSerialize = XUtils::imageListSerialize($imageList);
        if ( isset( $_POST['Subject'] ) ) {
            $model->attributes = $_POST['Subject'];
            $model->date = strtotime($_POST['Subject']['date']);
            $file = XUpload::upload( $_FILES['attach'] );
            if ( is_array( $file ) ) {
                $model->room_type_image = $file['pathname'];
                @unlink( $_POST['oAttach'] );
            }
            $model->image_list = $imageListSerialize['dataSerialize'];
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑项目,ID:' . $id ) );
                $this->redirect( array ( 'index' ) );
            }
        }
        if ( $imageList ) {
            $imageList = $imageListSerialize['data'];
        }
        elseif($model->image_list) {
            $imageList = unserialize($model->image_list);
        }
        $this->city_list = parent::_groupList('city');
        $this->render( 'update', array ( 'model' => $model, 'imageList'=>$imageList ) );

    }

    public function actionRecommend() {
        if(parent::_ajax_acl('subject_update')){
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad( new Subject(), $id );
            if($model->recommend == 0){
                $model->recommend = 1;
            }else{
                $model->recommend = 0;
            }
            if($model->save()){
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新项目推荐状态,ID:' . $id ) );
                echo CJSON::encode(array('status'=>'success'));
            }else{
                echo CJSON::encode(array('status'=>'failed'));
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
                parent::_acl( 'subject_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除项目，ID:' . $ids ) );
                parent::_delete( new Subject(), $ids, array ( 'index' ) );
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}
