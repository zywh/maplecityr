<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/12
 * Time: 15:19
 */
class ProvinceController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {

        parent::_acl('province_index');
        $model = new Province();
        $criteria = new CDbCriteria();
        $criteria->order = 'id ASC';
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );
        $this->render( 'index',array('datalist'=>$result, 'pagebar' => $pages));
    }

    /**
     * 录入
     *
     */
    public function actionCreate() {
        parent::_acl('province_create');
        $model = new Province();
        if ( isset( $_POST['Province'] ) ) {
            $model->attributes = $_POST['Province'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入省份,ID:' . $model->id ) );
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
        parent::_acl('province_update');
        $model = parent::_dataLoad( new Province(), $id );
        if ( isset( $_POST['Province'] ) ) {
            $model->attributes = $_POST['Province'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入省份,ID:' . $model->id ) );
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
                parent::_acl( 'province_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除省份，ID:' . $ids ) );
                parent::_delete( new Province(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}