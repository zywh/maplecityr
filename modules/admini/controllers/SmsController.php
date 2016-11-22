<?php
/**
 * Created by PhpStorm.
 * User: p-shenghui
 * Date: 2015/2/9
 * Time: 15:05
 */
class SmsController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {
        parent::_acl('sms_index');
        $model = new Sms();
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
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('sms_update');
        $model = parent::_dataLoad( new Sms(), $id );
        if ( isset( $_POST['Sms'] ) ) {
            $model->attributes = $_POST['Sms'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '短信验证设置,ID:' . $model->id ) );
                $this->redirect( array ( 'index' ) );
            }
        }
        $this->render( 'update', array ( 'model' => $model ) );

    }

}