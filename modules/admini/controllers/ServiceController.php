<?php
/**
 * Created by PhpStorm.
 * User: ShengHui
 * Date: 2015/3/4
 * Time: 21:13
 */
class ServiceController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {

        parent::_acl();
        $model = new Service();
        $criteria = new CDbCriteria();
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );
        $this->render('index', array('datalist'=>$result, 'pagebar' => $pages));
    }

    /**
     * 录入
     *
     */
    public function actionCreate() {
        parent::_acl();
        $model = new Service();
        if ( isset( $_POST['Service'] ) ) {
            $image = XUpload::upload( $_FILES['image'] );
            $model->attributes = $_POST['Service'];
            if(is_array($image)){
                $model->image = $image['pathname'];
            }
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入海外服务,ID:' . $model->id ) );
                $this->redirect( array ( 'index' ) );
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
        parent::_acl();
        $model = parent::_dataLoad( new Service(), $id );
        if ( isset( $_POST['Service'] ) ) {
            $model->attributes = $_POST['Service'];
            $file = XUpload::upload( $_FILES['image'] );
            if ( is_array( $file ) ) {
                $model->image = $file['pathname'];
                @unlink( $_POST['oAttach'] );
            }
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '修改海外服务,ID:' . $model->id ) );
                $this->redirect( array ( 'index' ) );
            }
        }
        $this->render( 'update', array ( 'model' => $model ) );

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
                parent::_acl( 'service_delete' );
                $cityModel = new Service();
                $cityModel->deleteAll( 'id IN(' . $ids . ')' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除海外服务，ID:' . $ids ) );
                parent::_delete( new Service(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}