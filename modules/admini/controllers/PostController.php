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

class PostController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('post_index');
        $model = new Post();
        $criteria = new CDbCriteria();
        $condition = '1';
        $title = trim( $this->_gets->getParam( 'title' ) );
        $catalogId = intval( $this->_gets->getParam( 'catalogId' ) );
        $title && $condition .= ' AND title LIKE \'%' . $title . '%\'';
        $catalogId && $condition .= ' AND catalog_id= ' . $catalogId;
        $criteria->condition = $condition;
        $criteria->order = 't.id DESC';
        $criteria->with = array ( 'catalog' );
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $pageParams = XUtils::buildCondition( $_GET, array ( 'title' , 'catalogId') );
        $pages->params = is_array( $pageParams ) ? $pageParams : array ();
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
        parent::_acl('post_create');
        $original = Yii::app()->request->getQuery('original');
        $model = new Post();
        if ( isset( $_POST['Post'] ) ) {
            $file = XUpload::upload( $_FILES['attach'] );
            $model->attributes = $_POST['Post'];
            if ( is_array( $file ) ) {
                $model->image = $file['pathname'];
            }
            if ($model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入内容,ID:' . $model->id ) );
                if(!empty($original)){
                    $this->redirect($original);
                }else{
                    $this->redirect(array('index'));
                }
            }
        }

        $this->render( 'create', array ( 'model' => $model, 'original'=>$original ) );
    }

    /**
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('post_update');
        $original = Yii::app()->request->getQuery('original');
        $model = parent::_dataLoad( new Post(), $id );
        if ( isset( $_POST['Post'] ) ) {
            $model->attributes = $_POST['Post'];
            $file = XUpload::upload( $_FILES['attach'] );
            if ( is_array( $file ) ) {
                $model->image = $file['pathname'];
                @unlink( $_POST['oAttach'] );
            }
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑内容,ID:' . $id ) );
                if(!empty($original)){
                    $this->redirect($original);
                }else{
                    $this->redirect(array('index'));
                }
            }
        }

        $this->render( 'update', array ( 'model' => $model, 'original'=>$original ) );

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
            parent::_acl( 'post_delete' );
            AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除内容，ID:' . $ids ) ); 
            parent::_delete( new Post(), $ids, array ( 'index' ), array( 'image' ) );
            break;
        default:
            throw new CHttpException(404, '错误的操作类型:' . $command);
            break;
        }
    }
}
