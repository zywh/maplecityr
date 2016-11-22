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

class EvaluateController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('evaluate_index');
        $model = new Evaluate();
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

    public function actionEvaluateHandle(){
        if(parent::_ajax_acl('evaluate_update')) {
            $id = Yii::app()->request->getPost('id');
            $evaluate = Evaluate::model()->findByPk($id);
            if (!empty($evaluate)) {
                $evaluate->status = 1;
                if ($evaluate->save()) {
                    AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新评估处理状态，ID:' . $evaluate->id ) );
                    echo CJSON::encode(array('success' => true, 'msg' => '需求处理成功'));
                } else {
                    echo CJSON::encode(array('success' => false, 'msg' => '操作失败，请联系管理员'));
                }
            } else {
                echo CJSON::encode(array('success' => false, 'msg' => '此评估不存在'));
            }
        }else{
            echo CJSON::encode(array('success' => false, 'msg' => '当前角色组无权限进行此操作，请联系管理员授权'));
        }
    }
}
