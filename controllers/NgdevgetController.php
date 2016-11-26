<?php

spl_autoload_unregister(array('YiiBase','autoload'));
Yii::import('application.vendor.*');
require_once('autoload.php'); 
spl_autoload_register(array('YiiBase','autoload'));

class NgDevGetController extends XFrontBase
{
	
    //private $imgHost ="http://m.maplecity.com.cn/";
	private $imgHost ="http://ca.maplecity.com.cn/";
	
	private $TREB_IMG_HOST = "http://1546690846.rsc.cdn77.org/treb/";//CDN Treb Large Image URL
	private $TREB_TN_HOST = "http://1546690846.rsc.cdn77.org/trebtn/"; //CDN Treb Thumbnail
	private $TREB_MID_HOST = "http://1546690846.rsc.cdn77.org/trebmid/";//CDN Treb Medium Image URL
	//private $CREA_IMG_HOST = "http://ca.maplecity.com.cn/mlspic/crea/";//CDN CREA Large Image URL
	//private $CREA_TN_HOST = "http://ca.maplecity.com.cn/mlspic/crea/creamtn/";//CDN CREA Thumbnail
	//private $CREA_MID_HOST = "http://ca.maplecity.com.cn/mlspic/crea/creamid/"; //CDN CREA Medium Image 
	private $CREA_IMG_HOST = "http://creac.citym.ca/";//CDN CREA Large Image URL
	private $CREA_TN_HOST = "http://creac.citym.ca/creamtn/";//CDN CREA Thumbnail
	private $CREA_MID_HOST = "http://creac.citym.ca/creamid/"; //CDN CREA Medium Image 
	
    private $MAPLEAPP_SPA_SECRET = "Wg1qczn2IKXHEfzOCtqFbFCwKhu-kkqiAKlBRx_7VotguYFnKOWZMJEuDVQMXVnG";
    private $MAPLEAPP_SPA_AUD = ['9fNpEj70wvf86dv5DeXPijTnkLVX5QZi'];
    private $MAPLEAPP_SPA_VOW_SECRET = "Wg1qczn2";
    private $MAPLEAPP_SPA_VOW_AUD = ['9fNpEj70'];
    //private $MAPLEAPP_SPA_VOW_LIFETIME = 60*7*24*60*60; // 60 days expiration
    private $MAPLEAPP_SPA_VOW_LIFETIME = 87200; // 60 days expiration
    private $PROFILE_FAVLIST_MAX = 30;
    private $PROFILE_CENTER_MAX = 10;
	private $STR_MEMBER_ONLY = '登录用户可见';
	private $IMG_ZANWU = 'static/images/zanwu.jpg';
	private $IMG_MEMBER = 'static/images/memberonly.jpg';

    function __construct() {
                ini_set("display_errors", "1"); // shows all errors
                ini_set("log_errors", 1);
                ini_set("error_log", "/tmp/php-error.log");
        	//return $this;
    }
	//REST to return either single project detail or list of projects if no parm ID is provided
   public function actionGetProjects(){
		$imghost = $this->imgHost;
		$results = array();
		$postParms = array();
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		//error_log("Parms:".$_POST['parms']['id']);
		$criteria = new CDbCriteria();
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		
		if (!empty($postParms['id'])){
			//return single record for detail page
			$criteria->addCondition('id="'.$_POST['parms']['id'].'"');
			//$subject = Subject::model()->find($criteria);
			$row = Subject::model()->find($criteria);
			
			//foreach($subject as $row){

			$result['id'] = $row["id"]; 
			$result['name'] = $row["name"]; 
			$result['summary'] = $row["summary"]; 
			$result['image_list'] = unserialize($row["image_list"]); 
			$result['layout_list'] = unserialize($row["layout_list"]); 
			$result['amenities'] = $row["amenities"]; 
			$result['point'] = $row["point"]; 
			$result['room_type_image'] = $row["room_type_image"]; 
			$result['developer_intro'] = $row["developer_intro"];
			$result['cityname'] = $row["cityname"]; 			
			$result['replaceurl'] = $imghost."tn_uploads";
			
			//$results[] = $result;
			//Return single Array object
			echo json_encode($result);
			//}
		} else {
			//Return all recommended project
			
			$criteria->addCondition('recommend=1');
			$subject = Subject::model()->findAll($criteria);
			foreach($subject as $row){

				$result['id'] = $row["id"]; 
				$result['name'] = $row["name"]; 
				$result['cityname'] = $row["cityname"]; 
				$result['room_type_image'] = str_replace("uploads","tn_uploads",$imghost.$row["room_type_image"]);
				$results[] = $result;
			}
			//return object array with multiple elements. 
			echo json_encode($results);
		}
    }	


	//REST to return either list of GRID and HOUSEes for map search page
    public function actionGetMapHouse() {
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		
		
		$maxmarkers = 2000;  //City count if count(house) is over
		$maxhouse = 40; //Grid count if count(house) is over
		$maxcitymarkers = 20;
		$minGrid = 5; //Display house if gridcount is lower than mindGrid
		$result['Data']['AreaHouseCount'] = array();
		$result['Data']['MapHouseList'] = array();
		$count= 0;
		
        if (empty($postParms)) {
            $result['IsError'] = true;
            $result['Message'] = '数据接收失败';
        } else {

            $criteria = new CDbCriteria();
			$criteria = $this->houseOption($postParms);
/*
			//exclude VOW List if no JWT token
			if (!$this->isValidIdToken()) {  
				$criteria->addCondition('src != "VOW"');
			};
*/
			$latlon = explode(',', $postParms['bounds']);
			$minLat = floatval($latlon[0]);
			$maxLat = floatval($latlon[2]);
			$minLon = floatval($latlon[1]);
			$maxLon = floatval($latlon[3]);
			$centerLat = ($minLat + $maxLat)/2;
			$centerLng = ($minLon + $maxLon)/2;
			
			
		//for home page nearby and recommend search, also for similar house search	

			if (!empty($postParms['type'])) {
				 $criteria->limit = 15;
				//error_log("type".$postParms['type']);
				 //Recommendation
				 if ($postParms['type']  == 'recommend') {
					//error_log("type2 is selected");
					$criteria->addCondition("propertyType_id = 1"); 
					$criteria->addCondition("br >= 3");
					$criteria->addCondition('lp_dol >= 800000');
					$criteria->addCondition('lp_dol <= 1800000');
				 }
				 //$criteria->addCondition("get_distance_in_miles_between_geo_locations(".$postParms['centerLat'].",".$postParms['centerLng'].", latitude, longitude)  < 20 "); 
				// $criteria->order = "get_distance_in_miles_between_geo_locations(".$postParms['centerLat'].",".$postParms['centerLng'].", latitude, longitude)";
				 if (!empty($postParms['housebaths'])) {
			                $criteria->addCondition("t.bath_tot=".$postParms['housebaths']);

            			}
				 if (!empty($postParms['houseroom']) ) {
                                		$houseroom = intval($postParms['houseroom']);
                                $criteria->addCondition("t.br = :br");
                                $criteria->params += array(':br' => $houseroom);
                        	}



				 $criteria->addCondition("get_distance_in_miles_between_geo_locations(".$centerLat.",".$centerLng.", latitude, longitude)  < 20 "); 
				 $criteria->order = "get_distance_in_miles_between_geo_locations(".$centerLat.",".$centerLng.", latitude, longitude)";
			 }
			if (empty($postParms['type'])) {	
				$count = House::model()->count($criteria);
				$result['Data']['Total'] = $count;
			}
						
			//Generate Data for City Count Marker Start
			if ( $count >= $maxmarkers) {
				error_log("Generate City View Count");
				$result['Data']['Type'] = "city";
				$groupcriteria = $criteria;
				$groupcriteria->select = 't.municipality as municipality,count(id) as id,sum(lp_dol)/10000 as lp_dol';
				//$groupcriteria->select = 't.municipality as municipality,count(id) as id,"100" as lp_dol';
				$groupcriteria->with = array('mname');
				$groupcriteria->group = "t.municipality";
				$groupcriteria->order = "id DESC";
				$groupcriteria->limit = $maxcitymarkers;
				
				$groupresult = House::model()->findAll($groupcriteria);
				$result['Message'] = '成功';
				//error_log(get_object_vars($groupcriteria));
				foreach ($groupresult as $val) {
					
					$city = $val->municipality;
					error_log("Generate City List".$city);
					$lat = $val->mname->lat;
					$lng = $val->mname->lng;
					$citycn = $val->mname->municipality_cname;
					
					if ( $lat > 20 ) {
						$result['Data']['AreaHouseCount'][$city]['NameCn'] = !empty($citycn)? ($citycn):"其他";
						$result['Data']['AreaHouseCount'][$city]['HouseCount'] = $val->id;
						$result['Data']['AreaHouseCount'][$city]['TotalPrice'] = $val->lp_dol;
						$result['Data']['AreaHouseCount'][$city]['GeocodeLat'] = $lat;
						$result['Data']['AreaHouseCount'][$city]['GeocodeLng'] = $lng;
					}
		
				}
			
			}
			
			$gridcount = 100;
			//Generate Data for Grid Counter Marker Start
			if (( $count < $maxmarkers) && ($count >= $maxhouse) ){
				//error_log("Count:".$count."Get Grid");
				$result['Data']['Type'] = "grid";
				$gridx =  ( $postParms['gridx'])? ( $postParms['gridx']): 5;
				$gridy =  ( $postParms['gridy'])? ( $postParms['gridy']): 5;
				
				$gridcriteria = $criteria;
				$gridcriteria->select = 'longitude,latitude,lp_dol';
				$location = House::model()->findAll($gridcriteria);
				$result['Message'] = '成功';
				//$tilex = (($maxLat - $minLat ) / $gridx) * 100000;
				//$tiley = (($maxLon - $minLon ) / $gridy) * 100000;
				$tiley = (($maxLat - $minLat ) / $gridy) ;
				$tilex = (($maxLon - $minLon ) / $gridx) ;
				//Generate grid center Lat/Lng
				for ( $x=1; $x <= $gridx ; $x++){
					for ( $y=1; $y <= $gridy ; $y++){
						$gridCenterlat = $minLat + ($tiley/2) + ($y -1)*$tiley ;
						$gridCenterlng = $minLon + ($tilex/2) + ($x -1)*$tilex ;
						$result['Data']['AreaHouseCount']["G".$x.$y]['GeocodeLat'] = $gridCenterlat;
						$result['Data']['AreaHouseCount']["G".$x.$y]['GeocodeLng'] = $gridCenterlng;
						
						
					}
				}
				//Get count of house in each tile
				foreach ($location as $val) {
					//$gridlat = ceil((($val->latitude - $minLat ) * 100000 / $tilex));
					//$gridlng = ceil((($val->longitude - $minLon) * 100000 / $tiley));
					$gridlat = ceil((($val->latitude - $minLat ) / $tiley));
					$gridlng = ceil((($val->longitude - $minLon) / $tilex));
					$price = $val-> lp_dol/10000;
					$result['Data']['AreaHouseCount']["G".$gridlng.$gridlat]['NameCn'] = "G".$gridlng.$gridlat;
					$result['Data']['AreaHouseCount']["G".$gridlng.$gridlat]['HouseCount']++; 
					$result['Data']['AreaHouseCount']["G".$gridlng.$gridlat]['TotalPrice'] += $price; 
					//error_log("G".$gridlng.$gridlat."Count:".$result['Data']['AreaHouseCount']["G".$gridlng.$gridlat]['HouseCount']);
				}
				
				
				
				//function moreThanOne($var)
				//{
				//return($var['HouseCount'] > 0);
				//}
				$moreThanOne = function($var) {  return($var['HouseCount'] > 0); }; 
				$filteredResult = array_filter($result['Data']['AreaHouseCount'],$moreThanOne);
				$gridcount = count($filteredResult);
				//$gridcount = 10;
				error_log("#Grid:".$gridcount);
				
				
				$result['Data']['Type'] = "grid";
				
				
			}
			
			
			
			//Generate Data for  House Marker Start
			if (($count < $maxhouse ) || ( $gridcount <= $minGrid)){
			
				$result['Data']['Type'] = "house";
				//$result['Data']['imgHost'] = "http://m.maplecity.com.cn/";
				$result['Data']['imgHost'] = $this->imgHost;
				$criteria->select = 'id,ml_num,zip,s_r,county,municipality,lp_dol,num_kit,construction_year,br,addr,longitude,latitude,area,bath_tot,pix_updt,src,pic_num';
				$criteria->with = array('mname','propertyType','city');
				//$criteria->order = "t.latitude,t.longitude";
				$house = House::model()->findAll($criteria);
				$result = $this->house2Array($house,$count,'house');
            
			}
			

		
		}
		
		echo json_encode($result);
    }
	
	
	//REST to return either list of GRID and HOUSEes for map search page
    public function actionGetHouseList() {
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
	    
		
        if (empty($postParms)) {
            $result['IsError'] = true;
            $result['Message'] = '数据接收失败';
        } else {
           
      
			$criteria = $this->houseOption($postParms);
/*
			if (!$this->isValidIdToken()) {  
				$criteria->addCondition('src != "VOW"');
			};
*/
			$count = House::model()->count($criteria);
			$pager = new CPagination($count);
			$pager->pageSize = 8;
			if (!empty($postParms['pageindex'])) {
				$pager->currentPage = $postParms['pageindex'];
			}
			$pager->applyLimit($criteria);
			$criteria->select = 'id,ml_num,zip,s_r,county,municipality,lp_dol,num_kit,construction_year,br,addr,longitude,latitude,area,bath_tot,pix_updt,src,pic_num';
			$criteria->with = array('mname','propertyType','city');
			$house = House::model()->findAll($criteria);
			

			$result = $this->house2Array($house,$count,'house');
       		
		} 
		
		
		echo json_encode($result);
    }
	
	
	/*
	REST for autocomplete page. 
	return either city -> map will re-center based on selection
	or MLS# -> map will redirect it to house detail page and pass MLS# as parm
	or House Address -> map will redirect it to house detail page and pass MLS# as parm
	*/
	public function actionGetCityList(){
		
		
		$limit = 8;
		$db = Yii::app()->db;
		$postParms = array();
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$term = trim($postParms['term']);
		
		error_log("Autocomplete Parms Term:".$term);
		$chinese = preg_match("/\p{Han}+/u", $term);
		
		
		if ( is_numeric($term) || preg_match("/^[a-zA-Z]\d+/",$term) ) {
			//MLS search
			$sql = "
			SELECT ml_num,municipality,if(s_r = 'Sale',concat(round(lp_dol/10000),'万'),concat(lp_dol,'/月')) as price,latitude,longitude,src FROM h_house 
			WHERE  ml_num like '".$term."%' 
			ORDER by city_id
			limit " .$limit;
			$resultsql = $db->createCommand($sql)->query();
			foreach($resultsql as $row){
				//Type MLS ARRAY
				$result['id'] = $row["ml_num"]; 
				$result['value'] = $row["ml_num"]; 
				$result['city'] = $row["municipality"];
				$result['lat'] = $this->maskVOW($row['src'],$row["latitude"]); 
				$result['lng'] = $this->maskVOW($row['src'],$row["longitude"]); 
				$result['price'] = $this->maskVOW($row['src'],$row['price'],$this->STR_MEMBER_ONLY);	
				$results['MLS'][] = $result;
			}
			
		} else{
		//Generate Count by municipality
			
			
			if ($chinese) { //if province = 0 and chinese search
			
				$sql = "
				SELECT m.lat lat,m.lng lng,m.municipality citye,m.municipality_cname cityc,m.province provincee,c.name provincec 
				FROM h_mname m, h_city c 
				WHERE  m.province = c.englishname 
				AND  m.municipality_cname like '".$term."%' 
				AND  m.count > 1 order by count desc limit " .$limit;
							
			
			} else { //if province = 0  and english search
			
				$sql = "
				SELECT m.lat lat,m.lng lng,m.municipality citye,m.municipality_cname cityc,m.province provincee,c.name provincec 
				FROM h_mname m, h_city c 
				WHERE  m.province = c.englishname 
				AND  municipality like '".$term."%' 
				AND  m.count > 1 order by count desc limit ". $limit;
				
			}
						
			$resultsql = $db->createCommand($sql)->query();
			$citycount = count($resultsql);
			
			foreach($resultsql as $row){
				$idArray = array($row["citye"],$row["lat"],$row["lng"]);
				
				//Type CITY ARRAY
				$result['id'] = $row["citye"]; 
				$result['type'] = "CITY"; 
				$result['lat'] = $row["lat"]; 
				$result['lng'] = $row["lng"]; 
				
				if ( $chinese ) {
					
					$result['value'] = $row["cityc"].", ".$row["provincec"]; 
					$results['CITY'][] = $result;
					
				} else {
					$result['value'] = $row["citye"].", ". $row["provincee"]; 
					$results['CITY'][] = $result;
				}
		
		
			}
			
			//Address Search and Return ML_NUM
			if ($citycount < $limit){
				//start address selection
				$limit = $limit - $citycount;
				$sql = "
				SELECT ml_num,addr,if(s_r = 'Sale',concat(round(lp_dol/10000),'万'),concat(lp_dol,'/月'))  as price,municipality,county,latitude,longitude,src FROM h_house  
				WHERE  addr like '%".$term."%' order by city_id
				limit " .$limit;
				$resultsql = $db->createCommand($sql)->query();
				
				foreach($resultsql as $row){
					//Type ADDRESS ARRAY
					$result['id'] = $row["ml_num"]; 
					$result['value'] = $this->maskVOW($row["src"],$row["addr"],$this->STR_MEMBER_ONLY);
					$result['city'] = $row["municipality"];
					$result['lat'] = $this->maskVOW($row["src"],$row["latitude"]); 
					$result['lng'] = $this->maskVOW($row["src"],$row["longitude"]); 
					$result['price'] = $this->maskVOW($row["src"],$row['price'],$this->STR_MEMBER_ONLY);	
					$result['province'] = $row["county"];
					$results['ADDRESS'][] = $result;
				}
			}
			
			
		}
		echo json_encode($results);

		
    
	//Function END  
    }
	
	/*
	REST for About Page content POST Model
	*/
	public function actionGetAbout(){
		$postParms = array();
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$catalog_id = $postParms['id'];
		if ($catalog_id == '') $catalog_id = 27;
		if ($catalog_id == 27) $cat_name_en="MAPLECITY PFOFILE";
		if ($catalog_id == 28) $cat_name_en="SUPERIORITY";
		if ($catalog_id == 30) $cat_name_en="CONTACT US";
		if ($catalog_id == 31) $cat_name_en="JOIN US";
				
		$row = Post::model()->find(array(
			'select'    => 'id, title, content',
			'condition' => ' catalog_id = :catalog_id',
			'params'    => array(':catalog_id' => $catalog_id),
			'order'     => 'id ASC',
			'limit'     => 1
		));
		//$imghost = "http://m.maplecity.com.cn/";
		$result['id'] = $row['id'];
		$result['title'] = $row['title'];
		$result['content'] = $row['content'];
		$result['catname'] = $cat_name_en;
		//$result['imgHost'] = "http://m.maplecity.com.cn/";
		$result['imgHost'] = $this->imgHost;
		echo json_encode($result);
	}
	

	/* News Info POST Model*/
    public function actionGetPost(){
		$results = array();
		$postParms = array();
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		//error_log("Parms:".$_POST['parms']['id']);
		$id = (!empty($postParms['id']))? $postParms['id']: 32;
		//$id = (!empty($id))? id: 10;
		$criteria = new CDbCriteria();
        
        $post = Post::model()->findByPk($id);
  
        $post->view_count += 1;
        $post->save();
        $catalog_id = $post->catalog_id;
        $next_post = Post::model()->findAll(array(
            'select'    => 'id, title',
            'condition' => 'id > :id AND catalog_id = :catalog_id',
            'params'    => array(':id' => $id, ':catalog_id' => $catalog_id),
            'order'     => 'id ASC',
            'limit'     => 1
        ));
        $prev_post = Post::model()->findAll(array(
            'select'    => 'id, title',
            'condition' => 'id < :id AND catalog_id = :catalog_id',
            'params'    => array(':id' => $id, ':catalog_id' => $catalog_id),
            'order'     => 'id DESC',
            'limit'     => 1
        ));
		$result['current']['title'] = $post['title'];
		$result['current']['content'] = $post['content'];
		$result['current']['image'] = $post['image'];
		
		//$result['pre'] = array_map(create_function('$m','return $m->getAttributes(array(\'id\',\'title\'));'),$prev_post);
		$result['pre']['id'] = $prev_post[0]['id'];
		//$result['next'] = array_map(create_function('$m','return $m->getAttributes(array(\'id\',\'title\'));'),$next_post);
		$result['next']['id'] = $next_post[0]['id'];
        echo json_encode($result);
    }
	
	/* News Info List POST Model*/	
    public function actionGetPostList(){
        //Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/post.css');
        //$catalog_id = Yii::app()->request->getQuery('catalog_id', 11);
		$postParms = array();
		$db = Yii::app()->db;
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$catalog_id = $postParms['id'];
		//$catalog_id = 12;
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        if(!empty($catalog_id)){
            $criteria->addCondition('catalog_id='.$catalog_id);
        }
      

 
        //房产热点新闻
        $posts = Post::model()->findAll(array(
            'select'    => 't.id as id, title',
            'condition' => 'catalog_id = :catalog_id',
            'params'     => array(':catalog_id' => $catalog_id),
			'with' => array('catalog'),
            'order'     => 't.id DESC',
            'limit'     => 5
        ));
		
	

		$result['posts'] =  array_map(create_function('$m','return $m->getAttributes(array(\'id\',\'title\'));'),$posts);
		
		echo json_encode($result);
        

    }
	
	/*MLS Data Stat for stats page*/
    public function actionGetMlsData(){

        $result = array();
        $criteria = new CDbCriteria();
        $criteria->select = 'unix_timestamp(date)*1000 as date,
				sales,dollar/1000000 as dallor,avg_price,
				new_list,snlr*100 as snlr,active_list,
				moi,avg_dom,avg_splp*100 as avg_splp,type';
	

        $data = MlsHist::model()->findAll($criteria);
        foreach ($data as $val) {


                $result['mlsdata'][$val->type]['avgprice'][] = array($val->date,$val->avg_price); //good
                $result['mlsdata'][$val->type]['avgdom'][] = array($val->date,$val->avg_dom); //good
                $result['mlsdata'][$val->type]['avgsplp'][] = array($val->date,$val->avg_splp); //good
				$result['mlsdata'][$val->type]['sales'][] = array($val->date,$val->sales); //good
                $result['mlsdata'][$val->type]['newlist'][] = array($val->date,$val->new_list); //good
                $result['mlsdata'][$val->type]['moi'][] = array($val->date,$val->moi); //good
				$result['mlsdata'][$val->type]['active'][] = array($val->date,$val->active_list); //good
				$result['mlsdata'][$val->type]['snlr'][] = array($val->date,$val->snlr); //bad
        }


        echo json_encode($result);

     

    }

	/*Current House Stats data for stats page*/
	public function actionGetHouseStats(){
		$db = Yii::app()->db;
		$result = array();
		//
		
		$sql = " select * from h_stats_chart order by i1 desc;";
		$resultsql = $db->createCommand($sql)->query();
		
		foreach($resultsql as $row){
			if ( $row["chartname"] == 'city')	{
				//City
				//$result["city"][] = array($row["n1"],$row["n3"],$row["n2"],$row["n4"],$row["i1"],$row["i2"]); 
				$s["name"] = $row["n1"];
				$s["y"] =(int)$row["i1"];
				$result["city"][] = $s;
				
			}
		   if ( $row["chartname"] == 'province')       {
					
				$s["name"] = $row["n2"];
				$s["y"] =(int)$row["i1"];	
				$result["province"][] = $s;
			}

		  
			if ( $row["chartname"] == 'price')	{
				//房价分布图
				$s["name"] = $row["n1"];
				$s["y"] =(int)$row["i1"];
				$result["price"][] = $s; //n1 is bin and i1 is count
			}
			
			if ( $row["chartname"] == 'house')	{
				//房屋面积分布图
				$s["name"] = $row["n1"];
				$s["y"] =(int)$row["i1"];
				$result["housearea"][] = $s; //n1 is bin and i1 is count
			}
			
			if ( $row["chartname"] == 'land')	{
				//土地面积分布图
				//$result["landarea"][] = array($row["i1"],$row["n1"]); //n1 is bin and i1 is count
				$s["name"] = $row["n1"];
				$s["y"] =(int)$row["i1"];
				$result["landarea"][] = $s;
			}
			if ( $row["chartname"] == 'type')	{
				//类型分布图
				//$result["property_type"][] = array($row["i1"],$row["n1"]); //n1 is bin and i1 is count
				$s["name"] = $row["n1"];
				$s["y"] =(int)$row["i1"];
				$result["property_type"][] = $s; //n1 is bin and i1 is count
			}
						
		}
		

       	//End of count
		
       echo json_encode($result);

      
    }

	public function statsLevel($characteristic) {
		return strlen($characteristic) - strlen(ltrim($characteristic));
	}

	/*Current House Stats data for stats page*/
	public function actionGetCityStats(){
		$db = Yii::app()->db;
		$results = [];
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$city = $postParms['city'];
		//$city='Mississauga';
		//
		$sql = "select replace(topic_chinese,' ','_') as t,Characteristic_chinese as c,Total from h_stats_city where CSD_Name='".$city."';";
		$resultsql = $db->createCommand($sql)->query();
		
		// add columns level and parent to a series
		// $topics[$topic] - a array of all topics with all of its series
		$parent = ["topic" => "1st"];
		$parents = [];
		$parents_list = [];
		$parents_stack = ["toplevel"];
		$topics = [];
		
		foreach($resultsql as $row){
			$topic = $row['t'];
			// new topic
			if ($topic != $parent["topic"]) {
				$parents_stack = ["toplevel"];
				if ($parent["topic"] != "1st") $parents_list[$parent["topic"]] = $parents;
				$parents = [];
			}

			$level = $this->statsLevel($row['c']);
			$s["level"] = $level;
			//error_log("topic=".$topic."parent.level".$parent["level"]."level=".$level);
			
			switch(TRUE) {	
				case ($level > $parent["level"]):
					array_push($parents_stack, $parent["name"]);
					$parents[] = $parent["name"];
					break;
				case ($level < $parent["level"]):
					array_pop($parents_stack);
					break;
				case ($level == $parent["level"]):
				default:
					break;
			}
			$s["parent"] = end($parents_stack);
			
			$s["topic"] = $topic;
			$s["name"] = trim($row['c']);
			$s["y"] = (int)$row["Total"];
			$parent = $s;
			$topics[$topic][] = $s;
		}
		// $parents_list - all level names
		//error_log(print_r($topics,1));
		//error_log(print_r($parents_list,1));

		foreach($topics as $topic_name => $a_topic){
			$data = [];
			foreach ($a_topic as $a_series) {
				// if the parent has children, add the drilldown
				$level_name = $a_series["parent"];
				if (in_array($a_series["name"], $parents_list[$topic_name]))
					$data[$level_name][] = ["name" => $a_series["name"], "y" => $a_series["y"], "drilldown" => $a_series["name"]];
				else 
					//$data[$level_name][] = ["name" => $a_series["name"], "y" => $a_series["y"]];
					$data[$level_name][] = [$a_series["name"], $a_series["y"]];
				
			}
			
			foreach ($data as $level_name => $a_data) {
				if ($level_name == "toplevel")
					$results[$topic_name]["series"][] = ["name" => $topic_name, "data" => $a_data];
				else
					//$results[$topic_name]["drilldown"]["series"][] = ["id" => $level_name, "name" => $level_name, "data" => $a_data]; 
					$results[$topic_name]["drilldown"]["series"][] = ["id" => $level_name, "name" => "上级", "data" => $a_data]; 
			}
			$results[$topic_name]["rawseries"] = $a_topic;
			$results[$topic_name]["levels"] = $parents_list[$topic_name];
		}
       	//End of topic

       echo json_encode($results);
    }

	/*Current House Stats data for stats page*/
	public function actionGetHpiStats(){
		$db = Yii::app()->db;
		$result = array();
		//
		
		$sql = " select unix_timestamp(date)*1000 as date,hpi,sales,location from h_stats_hpinew";
		$resultsql = $db->createCommand($sql)->query();
		
		foreach($resultsql as $row){
			$location = $row["location"];
			$s["date"] = $row["date"];
			$s["hpi"] = $row["hpi"];
			$s["sales"] = $row["sales"];
			$result[$location][] = $s;
						
		}
		

       	//End of count
		
       echo json_encode($result);

      
    }

	/*Current House Stats data for stats page*/
	public function actionGetHelp(){
		$db = Yii::app()->db;
		$result = array();
		
		$sql = "select * from h_mobile_help";
		$resultsql = $db->createCommand($sql)->query();
		
		foreach($resultsql as $row){
			$s["id"] = $row["id"];
			$s["subject"] = $row["subject"];
			$s["text"] = $row["text"];
			$s["category"] = $row["category"];
			$result[] = $s;
						
		}
		

       	//End of count
		
       echo json_encode($result);
	  

      
    }
	/*School List for School Map Page*/	
    public function actionGetSchoolMap() {
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		
		
		$maxmarkers = 200;  
        $result = array();
		$result['SchoolList'] = array();
			
        if (empty($_POST)) {
            $result['IsError'] = true;
            $result['Message'] = '数据接收失败';
        } 
		else 
		{
            $result['IsError'] = false;

            //type
            $criteria = new CDbCriteria();
			
			
			if($postParms['type'] == TRUE ) { //secondary school
				$criteria->addCondition('type =1');
			} 
			/* 
			if($postParms['type'] ==  FALSE ) {  //elementary school
				$criteria->addCondition('type =0');
			}  */
			
			$chinese = preg_match("/\p{Han}+/u", $postParms['xingzhi']);
			//XingZhi
			if(!empty($postParms['xingzhi']) && !($chinese)) {
				$criteria->addCondition("xingzhi like '".$postParms['xingzhi']."%'");
			}
			
			//Pingfen
			if(!empty($postParms['pingfen']) && intval($postParms['pingfen']) > 0) {
				$criteria->addCondition("pingfen >='".$postParms['pingfen']."'");
			} 
			
			//Rank
			if(!empty($postParms['rank'])&& intval($postParms['rank']) > 0) {
				//$criteria->order = "paiming ASC";
				$criteria->addCondition("paiming <='".$postParms['rank']."'");
						
			} 		
			
			//lat and long selection
            if (!empty($postParms['bounds'])) {
                $latlon = explode(',', $postParms['bounds']);
                $minLat = floatval($latlon[0]);
                $maxLat = floatval($latlon[2]);
                $minLon = floatval($latlon[1]);
                $maxLon = floatval($latlon[3]);
                $criteria->addCondition("lat <= :maxLat");
                $criteria->params += array(':maxLat' => $maxLat);
                $criteria->addCondition("lat >= :minLat");
                $criteria->params += array(':minLat' => $minLat);
                $criteria->addCondition("lng <= :maxLon");
                $criteria->params += array(':maxLon' => $maxLon);
                $criteria->addCondition("lng >= :minLon");
                $criteria->params += array(':minLon' => $minLon);
		


            }

			//Filter Invalid Lat
			$criteria->addCondition("lat > 20");
			
			//End of Condition
			
			$count = School::model()->count($criteria);
			
						
			//Display grid list if # of maxmarker is large
			if ( $count >= $maxmarkers) {
				$result['type'] = "grid";
				$criteria->addCondition("pingfen >0");
				error_log("Count:".$count."Grid Mode");
				$criteria->limit = 2000;
				$school = School::model()->findAll($criteria);
				$result['Message'] = '成功';
				$gridx =  ( $postParms['gridx'])? ( $postParms['gridx']): 5;
				$gridy =  ( $postParms['gridy'])? ( $postParms['gridy']): 5;

				$tiley = (($maxLat - $minLat ) / $gridy) ;
				$tilex = (($maxLon - $minLon ) / $gridx) ;
				//Generate grid center Lat/Lng
				for ( $x=1; $x <= $gridx ; $x++){
					for ( $y=1; $y <= $gridy ; $y++){
						$gridCenterlat = $minLat + ($tiley/2) + ($y -1)*$tiley ;
						$gridCenterlng = $minLon + ($tilex/2) + ($x -1)*$tilex ;
						$result['gridList']["G".$x.$y]['GeocodeLat'] = $gridCenterlat;
						$result['gridList']["G".$x.$y]['GeocodeLng'] = $gridCenterlng;
									
					}
				}
				//Get count of school in each tile
				foreach ($school as $val) {
				
					$gridlat = ceil((($val->lat - $minLat ) / $tiley));
					$gridlng = ceil((($val->lng - $minLon) / $tilex));
					$rating = $val-> pingfen;
					$result['gridList']["G".$gridlng.$gridlat]['GridName'] = "G".$gridlng.$gridlat;
					$result['gridList']["G".$gridlng.$gridlat]['SchoolCount']++; 
					$result['gridList']["G".$gridlng.$gridlat]['TotalRating'] += $rating; 
					
				}
       		}
			
			//Display school list if maxmarker is less
			if ( $count < $maxmarkers) {
				$result['type'] = "school";
				$criteria->order = "paiming";
				$school = School::model()->findAll($criteria);
				$result['Message'] = '成功';
				foreach ($school as $val) {
					$schoolList = array();
					$schoolList['School'] = $val->school;
					$schoolList['Paiming'] = !empty( $val->paiming)?  $val->paiming :'无';
					$schoolList['Pingfen'] = !empty( $val->pingfen)?  $val->pingfen :'无';
					$schoolList['Grade'] = $val->grade;
					$schoolList['City'] = $val->city;
					$schoolList['Zip'] = $val->zip;
					$schoolList['Province'] = $val->province;
					$schoolList['Tel'] = $val->tel;
					$schoolList['Address'] = $val->address;
					$schoolList['Lat'] = $val->lat;
					$schoolList['Lng'] = $val->lng;
					$schoolList['URL'] = $val->url;
					$schoolList['Schoolnumber'] = $val->schoolnumber;
					$result['SchoolList'][] = $schoolList;


				}
 
       		}
		}
		
		echo json_encode($result);
    }
	
	/*School Map Auto Complete*/
	
	public function actionGetSchoolAutoComplete(){
		
			
		$limit = 8;
		$city_id='0';
		$db = Yii::app()->db;
		$postParms = array();
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$term = trim($postParms['term']);
		//$term = 'john';
		
		$chinese = preg_match("/\p{Han}+/u", $term);
		
		
		
		if ($chinese) { //if province = 0 and chinese search
		
			$sql = "
			SELECT m.lat lat,m.lng lng,m.municipality citye,m.municipality_cname cityc,m.province provincee,c.name provincec 
			FROM h_mname m, h_city c 
			WHERE  m.province = c.englishname 
			AND  m.municipality_cname like '".$term."%' 
			AND  m.count > 1 order by count desc limit " .$limit;
						
		
		} else { //if province = 0  and english search
		
			$sql = "
			SELECT m.lat lat,m.lng lng,m.municipality citye,m.municipality_cname cityc,m.province provincee,c.name provincec 
			FROM h_mname m, h_city c 
			WHERE  m.province = c.englishname 
			AND  municipality like '".$term."%' 
			AND  m.count > 1 order by count desc limit ". $limit;
			
		}
				
		
	
	
			
		
		$resultsql = $db->createCommand($sql)->query();
		$citycount = count($resultsql);
		
		foreach($resultsql as $row){
			
			//Type CITY ARRAY
			$result['id'] = $row["citye"]; 
			$result['type'] = "CITY"; 
			$result['lat'] = $row["lat"]; 
			$result['lng'] = $row["lng"]; 
			
			if ( $chinese ) {
				
				$result['value'] = $row["cityc"].", ".$row["provincec"]; 
				$results['CITY'][] = $result;
				
			} else {
				$result['value'] = $row["citye"].", ". $row["provincee"]; 
				$results['CITY'][] = $result;
			}
		
	
		}
			
		//Address Search and Return ML_NUM
		if ($citycount < $limit){
			//start address selection
			$result= [];
			$limit = $limit - $citycount;
			$sql = "
			SELECT school,lat,lng,city,province,if(paiming >0,paiming,'无') as  p FROM h_school 
			WHERE  school like '%".$term."%' order by p ASC
			limit " .$limit;
			$resultsql = $db->createCommand($sql)->query();
			
			foreach($resultsql as $row){
				
				//Type ADDRESS ARRAY
				$result['paiming'] = $row["p"]; 
				$result['value'] = $row["school"];
				$result['lat'] = $row["lat"];
				$result['lng'] = $row["lng"];
				$results['SCHOOL'][] = $result;
								
			}
		}
		
		

		 echo json_encode($results);
    
	//Function END  
    }

	//REST to return the house detail by its MLS#
    public function actionGetHouseDetail() {
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$id = $postParms['id'];
		$username = $postParms['username'];
		$VOWtoken = $postParms['VOWtoken'];
		error_log("id=".$id);
		//$id='W3589143';
        
		$criteria = new CDbCriteria();
		//$criteria->addCondition('t.id="'.$id.'"');
		$criteria->addCondition('t.ml_num="'.$id.'"');
		$criteria->with = array('mname','propertyType','city');
		
        //$house = House::model()->find('id=:id',array(':id'=>$id));
		$house = House::model()->find($criteria);
 		//$house = House::model()->find($criteria)->asArray()->all();
		//error_log(print_r($house));

        $exchangeRate = 0;
        $exchangeRateList = ExchangeRate::model()->findAll();
        if(!empty($exchangeRateList)){
            $exchangeRate = $exchangeRateList[0]->rate;
        }

		$pics = $this->getPicture($house->county,$house->ml_num,$house->src,1,$house->pic_num);
		$photos = $pics['photos'];
		$cdn_photos = $pics['cdn_photos'];
   
		
		$isFav = 0;
		if ($username != 'NO') {
			if ($this->isValidIdToken()) {
				//error_log("Token is valid:".$username);
				$isFav = $this->checkfav($username,$id);
			} 
		} 

		if ($house->src == 'VOW' && ! $this->isValidVOWToken($VOWtoken)) {
			$VOW_member_only=true;
			if ($username != 'NO') {
				if ($this->isValidIdToken()) {
					$VOW_member_only=false;
				}
			}	

			if ($VOW_member_only) {
				$house->addr = $this->STR_MEMBER_ONLY;
				$unmasked_fields = array('ml_num','addr','pix_updt');
				foreach( $house as $key => $value ){
					if (!is_array($key) && !in_array($key, $unmasked_fields)) {
						$house[$key] = '';
					}
				}
				$photos=array($this->IMG_MEMBER);
				$cdn_photos=array($this->imgHost.$this->IMG_MEMBER);
			}
		}

		$data = array(
			'house'           => $house->getAttributes(),
			'house_mname'     => $house->mname->getAttributes(),
			'house_propertyType' => $house->propertyType->getAttributes(),
			'house_province' => $house->city->getAttributes(),
			'exchangeRate'    => $exchangeRate,
			'photos'          => $photos,
			'cdn_photos'      => $cdn_photos,
			'isFav'			=> $isFav
		);

		echo json_encode($data);
		
    }	

	public function isValidIdToken(){
		static $validToken = false;
		if ($validToken) { return $validToken; }

		//error_reporting(-1); // reports all errors
		$headers = getallheaders();

		//error_log(print_r($headers,true));
		$tokens = explode(" ", $headers['Authorization']? $headers['Authorization']: $headers['authorization']);
		//error_log(print_r($tokens,true));
		if ($tokens[0] == "Bearer" && !empty($tokens[1])) {
			//error_log($tokens[0]);
			//error_log($tokens[1]);
			//second is client ID and 4th argument is an array 
			$decoded_id_token = \Auth0\SDK\Auth0JWT::decode($tokens[1], $this->MAPLEAPP_SPA_AUD, $this->MAPLEAPP_SPA_SECRET, []); 
			$validToken = true;
		}
		else {
			error_log("error: no bearer in the authorization http header");
			$validToken = false;
		}
		
		return $validToken;
	}		
	
	public function actionGetVOWToken(){
		if (!$this->isValidIdToken()) {  echo  json_encode("invalid_id_token_to_getVOWtoken") ; return; }

		$VOWToken = \Auth0\SDK\Auth0JWT::encode($this->MAPLEAPP_SPA_VOW_AUD, $this->MAPLEAPP_SPA_VOW_SECRET, null, null, $this->MAPLEAPP_SPA_VOW_LIFETIME); 

		echo json_encode($VOWToken);
	}		

	public function isValidVOWToken($VOWtoken){
		//error_reporting(-1); // reports all errors
		//error_log($VOWToken);
		static $validVOWToken = false;
		if ($validVOWToken) { return $validVOWToken; }

		if (!empty($VOWtoken)) {
		
			$decoded_VOWtoken = \Auth0\SDK\Auth0JWT::decode($VOWtoken, $this->MAPLEAPP_SPA_VOW_AUD, $this->MAPLEAPP_SPA_VOW_SECRET, []); 
			$validVOWToken = true;
		}

		return $validVOWToken;
	}		

	/*Get user data */
	public function actionGetUserData(){
		
		if (!$this->isValidIdToken()) {  echo  json_encode("invalid id_token"); return; }
		$data = [];
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$username = $postParms['username'];
		$type = $postParms['type'];
		//$postParms['type'] = 'houseSearch';
		//$username ="zhengying@yahoo.com";
		//$username = "zhengy@rogers.com";
		if ( !empty($type)){

			if (( $type == 'houseSearch')||($type == 'schoolSearch')||( $type == 'myCenter')||($type == 'recentCenter')){ 
				$data = $this->getoption($username,$type);}
			if (( $type == 'houseFav')||( $type == 'routeFav')||( $type == 'recentView')){ 
				$data = $this->favlist($username,$type);}
			
			
		}
	
		

		echo json_encode($data);
		
    }
	public function actionGetFavCount(){
		//if (!$this->isValidIdToken()) { echo "invalid id_token"; return; }
		 if (!$this->isValidIdToken()) {   return; }

		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$username = $postParms['username'];
		//$username = 'zhengyin@yahoo.com';
		$db = Yii::app()->db;
		$sql ='select houseFav,routeFav,recentView,JSON_LENGTH(myCenter) as myCenter, JSON_LENGTH(recentCenter) as recentCenter from h_user_data where username="'.$username.'"';
		$resultsql = $db->createCommand($sql)->queryRow();
		$data['houseFav'] = $this->countfav($resultsql['houseFav']);
		$data['routeFav'] = $this->countfav($resultsql['routeFav']);
		$data['recentView'] = $this->countfav($resultsql['recentView']);
		$data['recentCenter'] = $resultsql['recentCenter'];
		$data['myCenter'] = $resultsql['myCenter'];
		echo json_encode($data);

		

	}
	function countfav($s){
		if (!empty($s)){
			return substr_count($s, ',') + 1;
		}else { return 0;}
	}
    public function actioncheckFavData(){
		//$data = 0;
		if (!$this->isValidIdToken()) { echo "invalid id_token"; return; }
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		//$postParms['mls'] = '30533489';
		//$username ="zhengying@yahoo.com";
		if ( !empty($postParms['mls'])){

		$username = $postParms['username'];

				$data = $this->checkfav($username,$postParms['mls']);

		}

		echo json_encode($data);

    }   



	/*Delete user data */
	public function actionUpdateUserData(){
		if (!$this->isValidIdToken()) { echo "invalid id_token"; return; }
		$db = Yii::app()->db;
	
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		//$postParms['mls'] = 'W3534467';
		//$postParms['mls'] = 'W111';
		//$postParms['action'] = 'r';
		$username = $postParms['username'];
		$action =	$postParms['action'];
		$type =	$postParms['type'];
		$mls = $postParms['mls'];
		if ( $action != 'r'){  // action = r is for favlist reorder. comma list is pass for update
									
			
			//debug
			//$type = 'routeFav';
			//$action =   "c";
			//$username = 'zhengying@yahoo.com';
			//$mls = 'W133';

			
			$sql ='select houseFav,routeFav,recentView from h_user_data where username="'.$username.'"';
			$resultsql = $db->createCommand($sql)->queryRow();
			if (!empty($resultsql)){
				$r = $this->favupdate($username,$type,$resultsql[$type],$mls,$action);
			} else {
				$resultsql[$type] = '';
				$r = $this->favupdate($username,$type,$resultsql[$type],$mls,$action);
			}
			
			
		} else { //no mls ,action = r. fav reorder
				
				//$r = $this->favupdate($username,$type,'',$mls,$action);
				$r = $this->updateUserTable($username,$type,$mls);
		}
		echo json_encode($r);
    }

	public function actionUpdateCenter(){
		if (!$this->isValidIdToken()) { echo "invalid id_token"; return; }
		$db = Yii::app()->db;
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		$username = $postParms['username'];
		$center = $postParms['data']; 
		$action = $postParms['action'];
		$type = $postParms['type'];
		//get current myCenter/recentCenter
		$sql = 'select '.$type.' from h_user_data where username="'.$username.'"';
		$resultsql = $db->createCommand($sql)->queryRow();
		$centerR = $resultsql[$type];
		$centerA = json_decode($center,true);

		//sql select
		//$centerR = '[ {"lat": "43.653226", "lng": "-79.383184", "name": "Toronto, Ontario"},{"lat": "43.653226", "lng": "-79.383184", "name": "Miss, Ontario"} ]';
		if ( $action == 'd') {$r = $this->removeCenter($username, $type, $centerA, $centerR);} //delete center
		if ( $action == 'c') {$r = $this->addCenter($username, $type, $centerA, $centerR);} //add center
		if ( $action == 'r') {$r = $this->updateUserTable($username, $type, $center); } //center list reorder.push string
		
		echo json_encode($r);
    }

	function removeCenter($username, $type, $centerA, $centerR){
	     if ( !empty($centerR) ){
			$funcName = function($value) { return $value["name"]; };
			$y = json_decode($centerR,true);
			$name = array_map($funcName,$y);
			if ( is_numeric($pos= array_search($centerA['name'], $name)) ){
					$r=1; //find match remove center
					unset($y[$pos]);
					$center = json_encode(array_values($y)); //array key is removed and reorder.otherwise json_encode return object string
					error_log($center);	
					$this->updateUserTable($username,$type,$center);

			}else{ $r=0; } //no found and no action
		}
        else{ $r = 0; } //empty and no action
		return $r;	
	}

	function addCenter($username, $type, $centerA, $centerR){
	   if ( !empty($centerR) ) {

			$funcName = function($value) { return $value["name"]; }; 
			$y = json_decode($centerR,true);		
			$name = array_map($funcName,$y);
			$return_code = 0; //find match	

			if ( !is_numeric(array_search($centerA['name'], $name)) ) {
				$return_code = 2;
				array_push($y,$centerA);

				if (count($y) > $this->PROFILE_CENTER_MAX) {
					// remove 1st off recentCenter to rotate
					if ($type == "recentCenter") {
						$y = array_slice($y, 1, $this->PROFILE_CENTER_MAX);
					}	
					else $return_code = 3;	// 3 - return code for reach the maximum
				}

				//didn't find match. Push center
				if ( $return_code == 2 ) {
					$center = json_encode($y);
					$this->updateUserTable($username, $type, $center);
				}
			} 			
        } else {
                $center = json_encode(array($centerA));
                $return_code = 1; //no new center.update
                $this->updateUserTable($username, $type, $center);
        }

	    return $return_code;
    }

	
	public function actionSaveOptions(){
		//save select option for houseSearch and schoolSearch
		if (!$this->isValidIdToken()) { echo "invalid id_token"; return; }
		$db = Yii::app()->db;
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
		$postParms = (!empty($_POST['parms']))?  $_POST['parms'] : array();
		//$postParms['mls'] = 'W3534467';
		//$postParms['mls'] = 'W111';
		//$postParms['action'] = 'r';
		$username = $postParms['username'];
		$type =	$postParms['type'];
		$data = $postParms['data'];
		$r = $this->updateUserTable($username,$type,$data);
		
		echo json_encode($r);
    }
	
	
	function getoption($username,$type){
		//get data for myCenter and houseSearch and schoolSearch which is JSON type
		$db = Yii::app()->db;
		$sql ='select '.$type.' from h_user_data where username="'.$username.'"';
		$resultsql = $db->createCommand($sql)->queryRow();
		$r['Data'] = $resultsql[$type];
		return $r;
	}
	
	function favupdate($username, $type, $current, $mls, $action){
		//update houseFav/routeFav/recentView
		
		$c = (!empty($current))? explode(',',$current): [];
		$pos = array_search($mls, $c);
		$return_code = 0;

		switch(TRUE) {	
			//insert a MLS
			case ($action == 'c') && !is_numeric($pos):
				array_push($c,$mls);
				// 99 - return code for exceeding the list maximum
				if (count($c) > $this->PROFILE_FAVLIST_MAX) {
					// remove 1st mls off recentView to rotate
					if ($type == "recentView") {
						$c = array_slice($c, 1, $this->PROFILE_FAVLIST_MAX);
						//$c = $c1;
					}	
					else $return_code = 99;
				}

				if ($return_code == 0) {
					$data = implode(",",$c); //convert to comma separated string
					$return_code = $this->updateUserTable($username, $type, $data);
				}
				break;

			//remove a MLS
			case ($action == 'd') && is_numeric($pos):
				unset($c[$pos]);
				$data = implode(",",$c); //convert to comma separated string
				$return_code = $this->updateUserTable($username, $type, $data);
				break;
			//default for action r. no change to $current 
			default:
				break;
		}
		
		//error_log($type." updateUserTable result ".$return_code);
		return $return_code;
	}

	function updateUserTable($username,$type,$data){
		$db = Yii::app()->db;
		 //update if exist and insert if row doesn't exist
		$sql = 'INSERT IGNORE INTO h_user_data('.$type.',username) values(\''.$data.'\',"'.$username.'") on duplicate KEY UPDATE '.$type.'=\''.$data.'\'';
		$return_code = $db->createCommand($sql)->execute();
		return $return_code;
	 }
	
	function favlist($username,$type){
		//get house detail for fav list
		  ini_set("display_errors", "1"); // shows all errors
                ini_set("log_errors", 1);
                ini_set("error_log", "/tmp/php-error.log");

		$db = Yii::app()->db;
		$criteria = new CDbCriteria();
		//get list of fav			
		$sql ='select '.$type.' from h_user_data where username="'.$username.'"';
		$resultsql = $db->createCommand($sql)->queryRow();
		$favlist = explode(',',$resultsql[$type]);
		//get list of house
		$criteria->select = 'id,ml_num,zip,s_r,county,municipality,lp_dol,num_kit,construction_year,br,addr,longitude,latitude,area,bath_tot,pix_updt,src,pic_num';
		$criteria->addInCondition('ml_num', $favlist);
		$criteria->with = array('mname','propertyType','city');
		//$criteria->order = "field(ml_num, 'N3571091', 'N3577563', 'N3586413', 'N3586138')";
		$criteria->order = "field(ml_num,'".implode("','", $favlist)."')";
		$house = House::model()->findAll($criteria);
		
		$result = $this->house2Array($house,0,'house');
		if (count($house) > 0) {
			// listed mls list
			$funcName = function($value) { return $value["ml_num"]; }; 	
			$houseMLS = array_map($funcName,$house);
			
			
			// delisted mls list
			$houseEmptyMLS = array_diff($favlist, $houseMLS);
			if (count($houseEmptyMLS) > 0) {
				$result1 = $this->emptyHouse2Array($houseEmptyMLS);
				$result2 = $result1['Data']['EmptyHouseList'];
				if (count($house) > 0) foreach($result['Data']['HouseList'] as $row) $result2[] = $row;
				$result['Data']['HouseList'] = $result2;
			}
		}
		return $result;
	}
	

     function checkfav($username,$mls){
		//it's called from housedetail
		$db = Yii::app()->db;
		$sql ='select houseFav from h_user_data where username="'.$username.'" and houseFav like "%'.$mls.'%"';
		$resultsql = $db->createCommand($sql)->queryRow();
		if (!empty($resultsql)){ $result['houseFav']=1; }else {  $result['houseFav']=0;}
		$sql ='select routeFav from h_user_data where username="'.$username.'" and routeFav like "%'.$mls.'%"';
		$resultsql = $db->createCommand($sql)->queryRow();
		if (!empty($resultsql)){ $result['routeFav']=1; }else {  $result['routeFav']=0;};		
		
		//Insert into recentView
		$sql ='select recentView from h_user_data where username="'.$username.'"';
		$resultsql = $db->createCommand($sql)->queryRow();
		$this->favupdate($username,'recentView',$resultsql['recentView'],$mls,'c');
		
		return $result;
        }

	
	function emptyHouse2Array($emptyHouse){  //this is used for empty house fav 
		foreach ($emptyHouse as $mls) {
			$emptyHouseList = array();
			$emptyHouseList['ListDate'] = '';
			$emptyHouseList['Beds'] = '';
			$emptyHouseList['Baths'] = '';
			$emptyHouseList['Kitchen'] = '';
			$emptyHouseList['GeocodeLat'] = '';
			$emptyHouseList['GeocodeLng'] = '';
			$emptyHouseList['Address'] = '';
			$emptyHouseList['SaleLease'] = ''; 
			$emptyHouseList['Price'] = '';
			$emptyHouseList['HouseType'] = '';
			$emptyHouseList['MunicipalityName'] = '';
			$emptyHouseList['CountryName'] = '';
			$emptyHouseList['Zip'] = '';
			$emptyHouseList['MLS'] = $mls;
			$emptyHouseList['Country'] = '';
			$emptyHouseList['ProvinceEname'] = '';
			$emptyHouseList['ProvinceCname'] = '';
			$emptyHouseList['CoverImg'] = $this->IMG_ZANWU;
			$emptyHouseList['CoverImgtn'] = $this->IMG_ZANWU;
			$result['Data']['EmptyHouseList'][] = $emptyHouseList;
		}
		return $result;
	}

	function house2Array($house,$count,$type){  //this is used for map and fav list 
		//$result['Data']['imgHost'] = "http://m.maplecity.com.cn/";
		$result['Data']['imgHost'] = $this->imgHost;
		$result['Data']['Total'] = $count;
		$result['Data']['Type'] = $type;
		
		foreach ($house as $val) {
			$mapHouseList = array();
			$mapHouseList['Src'] = $val->src;
			$mapHouseList['ListDate'] = $val->pix_updt;
			$mapHouseList['Beds'] = $val->br;
			$mapHouseList['Baths'] = $val->bath_tot;
			$mapHouseList['Kitchen'] = $val->num_kit;
			$mapHouseList['GeocodeLat'] = $val->latitude;
			$mapHouseList['GeocodeLng'] = $val->longitude;
			$mapHouseList['Address'] = !empty($val->addr)?$val->addr : "地址不详";
			$mapHouseList['SaleLease'] = $val->s_r; 
			$mapHouseList['Price'] = $val->lp_dol;
			
			/*
			$mapHouseList['Beds'] = $this->maskVOW($val->src,$val->br);
			$mapHouseList['Baths'] = $this->maskVOW($val->src,$val->bath_tot);
			$mapHouseList['Kitchen'] = $this->maskVOW($val->src,$val->num_kit);
			$mapHouseList['GeocodeLat'] = $this->maskVOW($val->src,$val->latitude);
			$mapHouseList['GeocodeLng'] = $this->maskVOW($val->src,$val->longitude);
			$mapHouseList['Address'] = $this->maskVOW($val->src,!empty($val->addr)?$val->addr : "不详", $this->STR_MEMBER_ONLY);
			$mapHouseList['SaleLease'] = $this->maskVOW($val->src,$val->s_r); 
			//$mapHouseList['sqft'] = $val->sqft;
			$mapHouseList['Price'] = $this->maskVOW($val->src,$val->lp_dol);
			//$mapHouseList['Id'] = $val->id;
			$mapHouseList['Zip'] = $this->maskVOW($val->src,$val->zip);
			*/
			$mapHouseList['HouseType'] = !empty($val->propertyType->name) ? $val->propertyType->name : '其他';
			$mapHouseList['MunicipalityName'] = !empty($val->mname->municipality_cname)? ($val->mname->municipality_cname):"其他";
			$mapHouseList['CountryName'] = $val->municipality;
			$mapHouseList['MLS'] = $val->ml_num;
			$mapHouseList['Country'] = $val->city_id;
			$mapHouseList['ProvinceEname'] = $val->county;
			$mapHouseList['ProvinceCname'] = $val->city->name;
			//$county = $val->county;
			
			$pics = $this->getPicture($val->county,$val->ml_num,$val->src,0,$val->pic_num);
			$mapHouseList['CoverImg'] = $this->maskVOW($val->src,$pics['CoverImg'],$this->IMG_MEMBER);
			
			$mapHouseList['CoverImgtn'] = $this->maskVOW($val->src,$pics['CoverImgtn'],$this->IMG_MEMBER);
			$mapHouseList['CdnCoverImg'] = $pics['CdnCoverImg'];
			$mapHouseList['CdnCoverImgtn'] = $pics['CdnCoverImg'];
			$mapHouseList['MemberOnlyImg'] = $this->imgHost.$this->IMG_MEMBER;
		
			$result['Data']['HouseList'][] = $mapHouseList;


		}
		return $result;
	}
	
	function getPicture($county,$ml_num,$src,$fullList,$pic_num){
			
			$county = preg_replace('/\s+/', '', $county);
			$county = str_replace("&","",$county);
			$dir="mlspic/crea/creamid/".$county."/Photo".$ml_num."/";
			$dirtn="mlspic/crea/creatn/".$county."/Photo".$ml_num."/";
			$num_files = 0;
			
			//Return CDN and non-CDN thumbnail and medium picture
			if ( $fullList == 0){
				//if (( $pic_num > 0)&&($src !="CREA" )) { //Treb picture meta data is updated after 2016/10/29
				if ( $pic_num > 0) { //Treb picture meta data is updated after 2016/10/29
				
					$p1 = $this->TREB_MID_HOST."Photo".$ml_num."/Photo".$ml_num."-1.jpeg";
					$p2 = $this->CREA_MID_HOST.$county."/Photo".$ml_num."/Photo".$ml_num."-1.jpg";
					$picList['CdnCoverImg'] = ($src != "CREA")? $p1: $p2;
					
					$p3 = $this->TREB_TN_HOST."Photo".$ml_num."/Photo".$ml_num."-1.jpeg";
					$p4 = $this->CREA_TN_HOST.$county."/Photo".$ml_num."/Photo".$ml_num."-1.jpg";
					$picList['CdnCoverImgtn'] = ($src != "CREA")? $p3: $p4;
				
				} else {  //fall back to scan dir if num = 0
					if(is_dir($dir)){
						$picfiles =  scandir($dir);
						$num_files = count(scandir($dir))-2;
					}
				

					if ( $num_files > 0)    {
						$picList['CoverImg'] = $dir.$picfiles[2];
						$picList['CoverImgtn'] = $dirtn.$picfiles[2];
						//CDN FULL URL
						$p1 = $this->TREB_MID_HOST."Photo".$ml_num."/"."Photo".$ml_num."-1.jpeg";
						$p2 = $this->CREA_MID_HOST.$county."/Photo".$ml_num."/".$picfiles[2];
						$picList['CdnCoverImg'] = ($src != "CREA")? $p1: $p2;
						
						$p3 = $this->TREB_TN_HOST."Photo".$ml_num."/"."Photo".$ml_num."-1.jpeg";
						$p4 = $this->CREA_TN_HOST.$county."/Photo".$ml_num."/".$picfiles[2];
						$picList['CdnCoverImgtn'] = ($src != "CREA")? $p3: $p4;
					
						
					}else {
						
						//CDN FULL URL
						 $picList['CdnCoverImg'] = $this->imgHost.$this->IMG_ZANWU;
						 $picList['CdnCoverImgtn'] = $this->imgHost.$this->IMG_ZANWU;
					}
					/*
					$picList['CoverImg'] = $this->maskVOW($src,$picList['CoverImg'],$this->IMG_MEMBER);
					$picList['CoverImgtn'] = $this->maskVOW($src,$picList['CoverImgtn'],$this->IMG_MEMBER);
					$picList['CdnCoverImg'] = $this->maskVOW($src,$picList['CdnCoverImg'],$this->imgHost.$this->IMG_MEMBER);
					$picList['CdnCoverImgtn'] = $this->maskVOW($src,$picList['CdnCoverImgtn'],$this->imgHost.$this->IMG_MEMBER);
					*/
				}
			}
			
			//Return CDN and non-CDN full picture list
			if ( $fullList == 1){
				//if (( $pic_num > 0)&&($src !="CREA" )) { //Treb picture meta data is updated after 2016/10/29
				if ( $pic_num > 0) { //Treb picture meta data is updated after 2016/10/29
					for ($x = 1; $x <= $pic_num; $x++) {
						
						$p1 = $this->TREB_IMG_HOST."Photo".$ml_num."/"."Photo".$ml_num."-".$x.".jpeg";
						$p2 = $this->CREA_IMG_HOST.$county."/Photo".$ml_num."/"."Photo".$ml_num."-".$x.".jpg";
						$p3 = "Photo".$ml_num."/"."Photo".$ml_num."-".$x.".jpeg"; 
						$p4 = $county."/Photo".$ml_num."/"."Photo".$ml_num."-".$x.".jpg"; 
						$cdn_photos[] = ($src != "CREA")? $p1: $p2;
						$photos[] = ($src != "CREA")? $p3: $p4; //backward compatible with 0.0.6. No prefix host
					}
				} else {
					$rdir=$county."/Photo".$ml_num."/";
					$dir="mlspic/crea/".$rdir;
					$photos = array();
					$cdn_photos = array();
					if (is_dir($dir)){
						$picfiles =  scandir($dir);
						$num_files = count($picfiles)-2;
						if ( $num_files > 0)    {
							for ($x = 2; $x <= $num_files + 1; $x++) {
								$fileIndex = $x - 1;
								$p1 = $this->TREB_IMG_HOST."Photo".$ml_num."/"."Photo".$ml_num."-".$fileIndex.".jpeg";
								$p2 = $this->CREA_IMG_HOST.$county."/Photo".$ml_num."/".$picfiles[$x];
								$cdn_photos[] = ($src != "CREA")? $p1: $p2;
							
								$photos[] = $rdir.$picfiles[$x];
							}    
						}
					
					}
			
				} 

				if ( count($photos) == 0 ) {
					$photos = array($this->IMG_ZANWU);
					$cdn_photos = array($this->imgHost.$this->IMG_ZANWU);
				}
				
				$picList['photos'] = $photos;
				$picList['cdn_photos'] = $cdn_photos;
						
			}
			
		
			return $picList;
			

	}
	
	function houseOption($postParms){
		  //根据条件查询地图
            $criteria = new CDbCriteria();
			
			if ($postParms['sr'] == "Lease" )  {
				$criteria->addCondition('s_r = "Lease"');
			} else{
					
				$criteria->addCondition('s_r = "Sale"');
			} 
			
				
			if ($postParms['oh'] == "true" )  {
				$criteria->addCondition('oh_date1 > "2016"');
			} 
	

            //卫生间数量 1-5
            if (!empty($postParms['housebaths']) && intval($postParms['housebaths']) > 0) {
                $criteria->addCondition("t.bath_tot >= :bath_tot");
                $criteria->params += array(':bath_tot' => intval($postParms['housebaths']));
				
            }

            //土地面积 Multiple Selection Array
            if (!empty($postParms['houseground'])) {
  				
				
				$minArea = intval($postParms['houseground']['lower']) ;
				$maxArea = intval($postParms['houseground']['upper']) ;
				if ($minArea >0) {
					$criteria->addCondition('land_area >='.$minArea);
				}
				if ( $maxArea < 43560){
					$criteria->addCondition('land_area <='.$maxArea);
				}
				
            }
			
			//挂牌时间

			if($postParms['housedate'] > 0 ){
				$criteria->addCondition('DATE_SUB(CURDATE(), INTERVAL '.$postParms['housedate'].' DAY) <= date(pix_updt)');
			}
		
			//House Area - Multiple Selection Array
			if (!empty($postParms['housearea'])) {
					
				$minArea = intval($postParms['housearea']['lower']) ;
				$maxArea = intval($postParms['housearea']['upper']) ;
				if ($minArea >0) {
					$criteria->addCondition('house_area >='.$minArea);
				}
				if ( $maxArea < 4000){
					$criteria->addCondition('house_area <='.$maxArea);
				}
			}
			
			//价格区间 -  Multiple Selection . Array is returned
			if (!empty($postParms['houseprice'])) {				
		
				$minPrice = intval($postParms['houseprice']['lower'])*10000 ;
				$maxPrice = intval($postParms['houseprice']['upper'])*10000 ;
				if ($minPrice >0) {
					$criteria->addCondition('lp_dol >='.$minPrice);
				}
				if ( $maxPrice < 6000000){
					$criteria->addCondition('lp_dol <='.$maxPrice);
				}
			}

	 
			//Bedroom
			if (!empty($postParms['houseroom']) && intval($postParms['houseroom']) > 0) {
				$houseroom = intval($postParms['houseroom']);
				$criteria->addCondition("t.br >= :br");
				$criteria->params += array(':br' => $houseroom);
			}

			//房屋类型
			//if (!empty($postParms['housetype']) && intval($postParms['housetype']) > 0) {
			if (!empty($postParms['housetype'])) {
				$typeInString = implode(",", $postParms['housetype']);
				
				//$criteria->addCondition("propertyType_id =".$postParms['housetype']);
				$criteria->addCondition("propertyType_id in (".$typeInString.")");
				
			}

  
            //建造年份
           if (!empty($postParms['houseyear'])) {
                //$year = explode(',', $postParms['houseyear']);
				$year=$postParms['houseyear'];
                //$minYear = intval($year[0]);
               // $maxYear = intval($year[1]);
				$criteria->addCondition("t.yr_built = :year");
				$criteria->params += array(':year' => $year);
    
            }
		
			//房屋类型
			if (!empty($postParms['city']) ) {
				$criteria->addCondition("t.municipality ='".$_POST['city']."'");
			
			}
	

			if (!empty($postParms['bounds'])) {
                $latlon = explode(',', $postParms['bounds']);
                $minLat = floatval($latlon[0]);
                $maxLat = floatval($latlon[2]);
                $minLon = floatval($latlon[1]);
                $maxLon = floatval($latlon[3]);
                $criteria->addCondition("t.latitude <= :maxLat");
                $criteria->params += array(':maxLat' => $maxLat);
                $criteria->addCondition("t.latitude >= :minLat");
                $criteria->params += array(':minLat' => $minLat);
                $criteria->addCondition("t.longitude <= :maxLon");
                $criteria->params += array(':maxLon' => $maxLon);
                $criteria->addCondition("t.longitude >= :minLon");
                $criteria->params += array(':minLon' => $minLon);
            } 
	 if (!empty($postParms['sortType'])) {
		$s = $postParms['sortType'];	
		switch ($s) {
		case 'Price':
        	$sortBy = 'lp_dol';
		break;
		case 'ListDate':
        	$sortBy = 'pix_updt';
		break;
		case 'Beds':
                $sortBy = 'br';
                break;
    		default:
		$sortBy = 'lp_dol';
		}
		$sortOrder = ($postParms['sortOrder'] == 1)? 'DESC':'ASC';
		$criteria->order = $sortBy." ".$sortOrder;
	
	}

			
			return $criteria;
	}

	function maskVOW($src, $unmasked, $masked = ''){
		if ($src != 'VOW') {
			return $unmasked;
		} else if ($this->isValidIdToken()) {  
			return $unmasked;
		} else {
			return $masked;
		}
	}
	
	}

