<?php
/**
 * Created by PhpStorm.
 * User: ShengHui
 * Date: 2015/3/4
 * Time: 21:13
 */
class FileController extends XAdminiBase
{
    /**
     * 首页
     *
     */
    public function actionIndex() {

        parent::_acl();
        $model = new File();
        $criteria = new CDbCriteria();
        $criteria->order = 'id ASC';
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
        $model = new File();
        if ( isset( $_FILES['attach'] ) ) {
            $file = XUpload::upload( $_FILES['attach'] );
            $image = XUpload::upload( $_FILES['image'] );
            if ( is_array( $file ) ) {
                $name = explode('.', $file['name']);
                $model->name = $name[0];
                $model->path = $file['pathname'];
            }
            if(is_array($image)){
                $model->image = $image['pathname'];
            }
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '上传文件,ID:' . $model->id ) );
                $this->redirect( array ( 'index' ) );
            }
        }
        $this->render( 'create', array ( 'model' => $model ) );
    }

    /**
     * 文件下载
     */
    public function actionDownload(){
        $id = Yii::app()->request->getQuery('id');
        $file = File::model()->findByPk($id);
        $name = '';
        $path = '';
        if(!empty($file)){
            $name = $file->name;
            $path = $file->path;
        }
        //将网页变为下载框，原本是：header("content-type:text/html;charset=utf-8");
        header("content-type:application/x-msdownload");
        //设置下载框上的文件信息
        header("content-disposition:attachment;filename={$name}");
        //readfile("文件路径");从服务器读取文件，该函数才真正实现下载功能，其它的是固定死的
        readfile('./'.$path);
    }

    /**
     * 推荐
     */
    public function actionRecommend() {
        if(parent::_ajax_acl('file_update')){
            $id = Yii::app()->request->getPost('id');
            $model = parent::_dataLoad( new File(), $id );
            if($model->recommend == 0){
                $model->recommend = 1;
            }else{
                $model->recommend = 0;
            }
            if($model->save()){
                AdminLogger::_create( array ( 'catalog' => 'update' , 'intro' => '更新文件推荐状态,ID:' . $id ) );
                echo CJSON::encode(array('status'=>'success'));
            }else{
                echo CJSON::encode(array('status'=>'failed'));
            }
        }else{
            echo CJSON::encode(array('status'=>'forbid'));
        }
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
                parent::_acl( 'file_delete' );
                $cityModel = new File();
                $cityModel->deleteAll( 'id IN(' . $ids . ')' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除文件，ID:' . $ids ) );
                parent::_delete( new File(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}