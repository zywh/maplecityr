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

class DistrictController extends XAdminiBase
{
    protected $city_list;
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl();
        $model = new District('district_index');
        $criteria = new CDbCriteria();
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );
        $this->city_list = parent::_groupList('city');
        $this->render( 'index',array('datalist'=>$result, 'pagebar' => $pages));
    }

    /**
     * 录入
     *
     */
    public function actionCreate() {
        parent::_acl('district_create');
        $model = new District();
        if ( isset( $_POST['District'] ) ) {
            $model->attributes = $_POST['District'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入地区,ID:' . $model->id ) );
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
        parent::_acl('district_update');
        $model = parent::_dataLoad( new District(), $id );
        if ( isset( $_POST['District'] ) ) {
            $model->attributes = $_POST['District'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '更新地区,ID:' . $model->id ) );
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
                parent::_acl( 'district_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除地区，ID:' . $ids ) );
                parent::_delete( new District(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}
