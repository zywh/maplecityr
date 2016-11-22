<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/11
 * Time: 11:16
 */
class BannerController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('banner_index');
        $model = new Banner();
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 5;
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
        parent::_acl('banner_create');
        $model = new Banner();
        if ( isset( $_POST['Banner'] ) ) {
            $file = XUpload::upload( $_FILES['attach'] );
            $model->attributes = $_POST['Banner'];
            if ( is_array( $file ) ) {
                $model->image = $file['pathname'];
            }
            if ($model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入banner,ID:' . $model->id ) );
                $this->redirect(array('index'));
            }
        }

        $this->render( 'create', array ( 'model' => $model ) );
    }

    /**
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('banner_update');
        $model = parent::_dataLoad( new Banner(), $id );
        if ( isset( $_POST['Banner'] ) ) {
            $model->attributes = $_POST['Banner'];
            $file = XUpload::upload( $_FILES['attach'] );
            if ( is_array( $file ) ) {
                $model->image = $file['pathname'];
                @unlink( $_POST['oAttach'] );
            }
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑banner,ID:' . $id ) );
                $this->redirect(array('index'));
            }
        }

        $this->render( 'update', array ( 'model' => $model ) );

    }

    public function actionDisplay() {
        if(parent::_ajax_acl('banner_update')){
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad( new Banner(), $id );
            if($model->status == 0){
                $model->status = 1;
            }else{
                $model->status = 0;
            }
            if($model->save()){
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新首页Banner状态,ID:' . $id ) );
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
                parent::_acl( 'banner_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除banner，ID:' . $ids ) );
                parent::_delete( new Banner(), $ids, array ( 'index' ), array( 'image' ) );
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}