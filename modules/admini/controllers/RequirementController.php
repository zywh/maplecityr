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

class RequirementController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('requirement_index');
        $model = new Requirement();
        $criteria = new CDbCriteria();
        $condition = '1';

        $isHandle = $this->_gets->getParam( 'is_handle' );
        $noHandle = $this->_gets->getParam( 'no_handle' );

        $isHandle && $condition .= ' AND status= ' . $isHandle;
        $noHandle && $condition .= ' AND status= ' . $noHandle;

        $criteria->condition = $condition;
        $criteria->order = 't.id DESC';
        $count = $model->count( $criteria );
        $pages = new CPagination( $count );
        $pages->pageSize = 10;
        $pageParams = XUtils::buildCondition( $_GET, array ( 'status' ) );
        $pages->params = is_array( $pageParams ) ? $pageParams : array ();
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        $result = $model->findAll( $criteria );
        $this->render( 'index',array('datalist'=>$result, 'pagebar' => $pages));
    }

    public function actionRequirementHandle(){
        if(parent::_ajax_acl('evaluate_update')) {
            $id = Yii::app()->request->getPost('id');
            $requirement = Requirement::model()->findByPk($id);
            if (!empty($requirement)) {
                $requirement->status = 1;
                if ($requirement->save()) {
                    AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新学区专栏房源需求处理状态，ID:' . $requirement->id ) );
                    echo CJSON::encode(array('success' => true, 'msg' => '需求处理成功'));
                } else {
                    echo CJSON::encode(array('success' => false, 'msg' => '操作失败，请联系管理员'));
                }
            } else {
                echo CJSON::encode(array('success' => false, 'msg' => '此需求不存在'));
            }
        }else{
            echo CJSON::encode(array('success' => false, 'msg' => '当前角色组无权限进行此操作，请联系管理员授权'));
        }
    }
}
