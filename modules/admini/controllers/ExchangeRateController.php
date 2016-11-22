<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/9
 * Time: 13:25
 */
class ExchangeRateController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('exchangeRate_index');
        $model = new ExchangeRate();
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
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
        parent::_acl('exchangeRate_create');
        $model = new ExchangeRate();
        if ( isset( $_POST['ExchangeRate'] ) ) {
            $model->attributes = $_POST['ExchangeRate'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入汇率,ID:' . $model->id ) );
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
        parent::_acl('exchangeRate_update');
        $model = parent::_dataLoad( new ExchangeRate(), $id );
        if ( isset( $_POST['ExchangeRate'] ) ) {
            $model->attributes = $_POST['ExchangeRate'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '汇率设置,ID:' . $model->id ) );
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
                parent::_acl( 'exchangeRate_delete' );
                $cityModel = new ExchangeRate();
                $cityModel->deleteAll( 'id IN(' . $ids . ')' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除内汇率，ID:' . $ids ) );
                parent::_delete( new ExchangeRate(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }

}