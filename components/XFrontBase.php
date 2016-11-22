<?php
/**
 * 控制器基类，前台控制器必须继承此类
 * 
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Controller
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */

class XFrontBase extends Controller
{
    public $layout = '';
    protected $_account;
    protected $_catalog;
    
    /**
	 * 初始化
	 * @see CController::init()
	 */
    public function init ()
    {
        parent::init();
        if (isset($_POST['sessionId'])) {
            $session = Yii::app()->getSession();
            $session->close();
            $session->sessionID = $_POST['sessionId'];
            $session->open();
        }
        $this->_account = parent::_sessionGet('_account');

        //栏目
        $this->_catalog = Catalog::model()->findAll();

        //检测系统是否已经安装
        if(!is_file(WWWPATH.DS.'data'.DS.'install.lock'))
            $this->redirect(array('/install'));
    }

    protected function getCatalogName($id){
        $catalog = Catalog::model()->findByPk(intval($id));
        if(!empty($catalog)){
            return $catalog->catalog_name;
        }else{
            return null;
        }
    }

    protected function getCatalogImage($id){
        $catalog = Catalog::model()->findByPk(intval($id));
        if(!empty($catalog)){
            return $catalog->image;
        }else{
            return null;
        }
    }
}