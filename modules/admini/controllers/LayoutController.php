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

class LayoutController extends XAdminiBase
{
    protected $house_list;
    /**
     * 首页
     *
     */
    public function actionIndex($id = 0) {

        parent::_acl('layout_index');
        $model = new Layout();
        $criteria = new CDbCriteria();
        $condition = '1';

        $house_id = $this->_gets->getParam( 'house_id' );

        $house_id && $condition .= ' AND house_id= ' . $house_id;

        $criteria->condition = $condition;
        $criteria->order = 't.id DESC';
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $pageParams = XUtils::buildCondition( $_GET, array ( 'house_id' ) );
        $pages->params = is_array( $pageParams ) ? $pageParams : array ();
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );

        $this->house_list = parent::_groupList('house');

        $this->render('index',array('datalist'=>$result, 'pagebar'=>$pages, 'house_id'=>$id));
    }

    /**
     * 录入
     *
     */
    public function actionCreate($id) {
        parent::_acl('layout_create');
        $model = new Layout();
        if ( isset( $_POST['Layout'] ) ) {
            $model->attributes = $_POST['Layout'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入房屋布局,ID:' . $model->id ) );
                $this->redirect( array ( 'index', 'id'=>$model->house_id ) );
            }
        }
        $this->render( 'create', array ( 'model' => $model, 'house_id' => $id ) );
    }

    /**
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('layout_update');
        $original = Yii::app()->request->getQuery('original');
        $model = parent::_dataLoad( new Layout(), $id );
        if ( isset( $_POST['Layout'] ) ) {
            $model->attributes = $_POST['Layout'];
            if ( $model->save() ) {
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '编辑房屋布局,ID:' . $id ) );
                if(!empty($original)){
                    $this->redirect($original);
                }else{
                    $this->redirect(array('index'));
                }
            }
        }

        $urlReferrer = Yii::app()->request->urlReferrer;

        $this->render( 'update', array ( 'model' => $model, 'original'=>$original) );
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
                parent::_acl( 'layout_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除房屋布局，ID:' . $ids ) );
                parent::_delete( new Layout(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}
