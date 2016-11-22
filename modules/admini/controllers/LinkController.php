<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/11
 * Time: 11:16
 */
class LinkController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('link_index');
        $model = new Link();
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
        parent::_acl('link_create');
        $model = new Link();
        if ( isset( $_POST['Link'] ) ) {
            $model->attributes = $_POST['Link'];
            if ($model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入友情链接,ID:' . $model->id ) );
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
        parent::_acl('link_update');
        $model = parent::_dataLoad( new Link(), $id );
        if ( isset( $_POST['Link'] ) ) {
            $model->attributes = $_POST['Link'];
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑友情链接,ID:' . $id ) );
                $this->redirect(array('index'));
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
                parent::_acl( 'link_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除友情链接，ID:' . $ids ) );
                parent::_delete( new Link(), $ids, array ( 'index' ), array( 'image' ) );
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}