<?php
/**
 * 后台菜单及权限
 * 
 * @author        shuguang <5565907@qq.com>
 * @copyright     Copyright (c) 2007-2013 bagesoft. All rights reserved.
 * @link          http://www.bagecms.com
 * @package       BageCMS.Acl
 * @license       http://www.bagecms.com/license
 * @version       v3.1.0
 */

class XAdminiAcl
{
    //权限配制数据
	public static $aclList = array(
    	'首页' => array(
    	   'controller'=>'home', 'url'=>'default/home', 'acl'=>'home','action'=>array(
                array('name'=>'系统首页','url'=>'default/home','acl'=>'home_index','list_acl'=>array()),
                array('name'=>'首页Banner','url'=>'banner/index','acl'=>'banner_index','list_acl'=>array(
                    '录入'=>'banner_create', '编辑'=>'banner_update', '删除'=>'banner_delete'
                )),
                array('name'=>'QQ咨询管理','url'=>'consult/index','acl'=>'consult_index','list_acl'=>array(
                    '录入'=>'consult_create', '编辑'=>'consult_update','删除'=>'consult_delete'
                )),
                array('name'=>'学区专栏房源需求','url'=>'requirement/index','acl'=>'requirement_index','list_acl'=>array(
                    '编辑'=>'requirement_update'
                )),
                array('name'=>'投资评估','url'=>'evaluate/index','acl'=>'evaluate_index','list_acl'=>array(
                    '编辑'=>'evaluate_update'
                )),
                array('name'=>'汇率设置','url'=>'exchangeRate/index','acl'=>'exchangeRate_index','list_acl'=>array(
                    '编辑'=>'exchangeRate_update'
                )),
                array('name'=>'短信验证设置','url'=>'sms/index','acl'=>'sms_index','list_acl'=>array(
                    '编辑'=>'sms_update'
                )),
        	)
          ),
        /**
         * 房源信息管理菜单
         * @author ShengHui
        */
        '房源' => array(
           'controller'=>'house', 'url'=>'house/index', 'acl'=>'house','action'=>array(
                array('name'=>'房源管理','url'=>'house/index','acl'=>'house_index','list_acl'=>array(
                    '录入'=>'house_create', '编辑'=>'house_update', '推荐'=>'house_recommend', '删除'=>'house_delete'
                )),
                array('name'=>'项目管理','url'=>'subject/index','acl'=>'subject_index','list_acl'=>array(
                    '录入'=>'subject_create', '编辑'=>'subject_update',  '删除'=>'subject_delete'
                )),
                array('name'=>'省份管理','url'=>'province/index','acl'=>'province_index','list_acl'=>array(
                    '录入'=>'province_create', '编辑'=>'province_update', '删除'=>'province_delete'
                )),
                array('name'=>'城市管理','url'=>'city/index','acl'=>'city_index','list_acl'=>array(
                    '录入'=>'city_create', '编辑'=>'city_update', '删除'=>'city_delete'
                )),
                array('name'=>'地区管理','url'=>'district/index','acl'=>'district_index','list_acl'=>array(
                    '录入'=>'district_create', '编辑'=>'district_update',  '删除'=>'district_delete'
                )),
                array('name'=>'投资类型管理','url'=>'investType/index','acl'=>'investType_index','list_acl'=>array(
                    '录入'=>'investType_create', '编辑'=>'investType_update', '删除'=>'investType_delete'
                )),
                array('name'=>'物业类型管理','url'=>'propertyType/index','acl'=>'propertyType_index','list_acl'=>array(
                    '录入'=>'propertyType_create', '编辑'=>'propertyType_update', '删除'=>'propertyType_delete'
                )),
                array('name'=>'房屋布局管理','url'=>'layout/index','acl'=>'layout_index','list_acl'=>array(
                    '录入'=>'layout_create', '编辑'=>'layout_update', '删除'=>'layout_delete'
                )),
                array('name'=>'房屋配套管理','url'=>'match/index','acl'=>'match_index','list_acl'=>array(
                    '录入'=>'match_create', '编辑'=>'match_update', '删除'=>'match_delete'
                )),
                array('name'=>'投资目的管理','url'=>'investAim/index','acl'=>'investAim_index','list_acl'=>array(
                    '录入'=>'investAim_create', '编辑'=>'investAim_update', '删除'=>'investAim_delete'
                )),
								array('name'=>'导入Excel文件','url'=>'import/index','acl'=>'import_index','list_acl'=>array(
                    '录入22'=>'investAim_create', '编辑'=>'investAim_update', '删除'=>'investAim_delete'
                )),
            )
          ),
    	'内容' => array(
    	    'controller'=>'post', 'url'=>'post/index', 'acl'=>'post','action'=>array(
                array('name'=>'内容管理','url'=>'post/index','acl'=>'post_index','list_acl'=>array(
                    '录入'=>'post_create', '编辑'=>'post_update', '删除'=>'post_delete'
                )),
                array('name'=>'类别管理','url'=>'catalog/index','acl'=>'catalog_index','list_acl'=>array(
                    '录入'=>'catalog_create', '编辑'=>'catalog_update'
                )),
                array('name'=>'枫之都视频','url'=>'video/index','acl'=>'video_index','list_acl'=>array(
                    '录入'=>'video_create', '编辑'=>'video_update', '删除'=>'video_delete'
                )),
                array('name'=>'友情链接','url'=>'link/index','acl'=>'link_index','list_acl'=>array(
                    '录入'=>'link_create', '编辑'=>'link_update', '删除'=>'link_delete'
                )),
        	)
          ),
        '俱乐部' => array(
            'controller'=>'service', 'url'=>'member/index', 'acl'=>'service_index','action'=>array(
                array('name'=>'会员管理','url'=>'member/index','acl'=>'member_index','list_acl'=>array(
                    '删除'=>'member_delete'
                )),
                array('name'=>'海外服务','url'=>'service/index','acl'=>'service_index','list_acl'=>array(
                    '录入'=>'service_create', '编辑'=>'service_update', '删除'=>'service_delete'
                )),
                array('name'=>'资料上传','url'=>'file/index','acl'=>'file_index','list_acl'=>array(
                    '录入'=>'file_create', '编辑'=>'file_update', '删除'=>'file_delete'
                )),
            )
        ),
        '用户' => array(
            'controller'=>'admin', 'url'=>'admin/index', 'acl'=>'admin','action'=>array(
                array('name'=>'管理员列表','url'=>'admin/index','acl'=>'admin_index','list_acl'=>array(
                    '录入'=>'admin_create', '编辑'=>'admin_update', '删除'=>'admin_delete'
                )),
//                array('name'=>'管理员权限','url'=>'admin/group','acl'=>'admin_group','list_acl'=>array(
//                    '录入'=>'admin_group_create', '编辑'=>'admin_group_update', '删除'=>'admin_group_delete'
//                )),
                array('name'=>'管理员日志','url'=>'logger/index','acl'=>'admin_logger','list_acl'=>array(
                    '删除'=>'admin_logger_delete'
                )),
            )
          ),
    );
    
    /**
     * 后台菜单过滤
     *
     */
    static public function filterMenu($append = ',home,home_index')
    {
        $session = new XSession();
        $admini = $session->get('_admini');
		$groupId = $admini['groupId'];
        if($groupId != 1){
            $aclModel = AdminGroup::model()->findByPk($groupId);
            $acl = $aclModel->acl.$append;
            $aclArr = explode(',', $acl);
            foreach (self::$aclList as $k=>$r){
                if(!in_array($r['acl'], $aclArr)){
                   unset(self::$aclList[$k]);
                }else{
                    self::$aclList[$k]['url'] = self::_parentRouter($k, $aclArr);
                    foreach($r['action'] as $kk=>$rr){
                        if(!in_array($rr['acl'], explode(',', $acl)))
                            unset(self::$aclList[$k]['action'][$kk]);
                    }
                }
            }
        }
       return self::$aclList;
    }

    /**
     * 取大类链接，防止有未授权情况
     * @param string $n
     * @param array $acl
     * @return string
     */
    private static function _parentRouter($n, $acl){
        $one = 0;
        foreach((array)self::$aclList[$n]['action'] as $key=>$row){
            if(in_array($row['acl'], $acl)){
                if($one == 0)
                    return $row['url'];
            }
        }
        return 'home';
    }
}