<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/11
 * Time: 11:17
 */
class VideoController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('video_index');
        $model = new Video();
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
        parent::_acl('video_create');
        $model = new Video();
        if ( isset( $_POST['Video'] ) ) {
            $model->attributes = $_POST['Video'];
            if ($model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入视频,ID:' . $model->id ) );
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
        parent::_acl('video_update');
        $model = parent::_dataLoad( new Video(), $id );
        if ( isset( $_POST['Video'] ) ) {
            $model->attributes = $_POST['Video'];
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑视频,ID:' . $id ) );
                $this->redirect(array('index'));
            }
        }

        $this->render( 'update', array ( 'model' => $model ) );

    }

    public function actionChosen() {
        if(parent::_ajax_acl('video_update')){
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad( new Video(), $id );
            if($model->chosen == 0){
                $model->chosen = 1;
            }else{
                $model->chosen = 0;
            }
            if($model->save()){
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新视频状态,ID:' . $id ) );
                echo CJSON::encode(array('status'=>'success'));
            }else{
                echo CJSON::encode(array('status'=>'failed'));
            }
        }else{
            echo CJSON::encode(array('status'=>'forbid'));
        }
    }

    public function actionHomePlay() {
        if(parent::_ajax_acl('video_update')){
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad( new Video(), $id );
            if($model->home == 0){
                $index_video = Video::model()->findAll('home=:home', array(':home'=>1));
                if(!empty($index_video)){
                    foreach($index_video as $obj){
                        $obj->home = 0;
                        $obj->save();
                    }
                }
                $model->home = 1;
            }else{
                $model->home = 0;
            }
            if($model->save()){
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新视频状态,ID:' . $id ) );
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
                parent::_acl( 'video_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除视频，ID:' . $ids ) );
                parent::_delete( new Video(), $ids, array ( 'index' ), array( 'image' ) );
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}