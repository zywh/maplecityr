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

class ImportController extends XAdminiBase
{
	
/**
* 首页
*
*/
public function actionIndex() {
	unset($_SESSION['importFileName']);
	$this->render('index');
}
public function actionImport() {
	$filename = trim($_POST["filename"]).'.csv';
	if (empty($_SESSION['importFileName']))
	{
		session_start();
		$_SESSION['importFileName']=$filename;
	}
	else
	{
		$filename=$_SESSION['importFileName'];
	}
	
	if(!file_exists($filename)){
		$this->render('importErr');
	}
	else
	{
		$id=$_GET["id"];
		$csv_count=$_GET["count"];
		//$id=8;
		//$csv_count=7;
		if ($id == '') 
		{
			$id=2;
			$csv_count=$this->get_file_line_count($filename);
			//$csv_count=2;
			unset($_SESSION['import_err']);
		}
		$progress=$id/$csv_count*100;
		$progress=round($progress, 1);
		$progress2=$progress;
		if ($progress2 < 1) $progress2=1;
		$import_result = $this->import_db_line($filename, $id );
		//print_r($import_result);
		if ($import_result['state']==0 ){
			if ( empty($_SESSION['import_err']))
			{
				session_start();
				$_SESSION['import_err']=$import_result['msg'].'： '.var_export($import_result['err'], true).'<br />';
			}
			else
			{
				$_SESSION['import_err'].=$import_result['msg'].': '.var_export($import_result['err'], true).'<br />';
			}
		}
		$this->render('import', array(
            'id' => $id,
            'csv_count' => $csv_count,
						'progress' => $progress,
						'progress2' => $progress2,
						'import_result' => $import_result
        ));
	}
	
}

/**
* csv文件的某一行数据导入到数据库
*/
function import_db_line( $file_name, $line){
	$msg='';
	$state=0;
  $n = 0;
  $handle = fopen($file_name,'r');
  while ($data = fgetcsv($handle, 10000)) { 
	  ++$n;
		
		$is_print_col=0; //读取第一行
		if (1==$is_print_col)
		{
			$num = count($data); 
			echo '<tr> ' . "\r\n";
			echo '<td>' . $line .'</td>' . "\r\n";
			for ($i = 0; $i < $num; $i++) { 
					//echo '<td>'.iconv('gb2312', 'utf-8', $data[$i]).'</td>' . "\r\n"; //中文转码 
					$this->p($i . '行： ' . $data[$i]);
			} 
			echo '</tr> ' . "\r\n";
		}
		
		
		if($line==$n) 
		{
			$msg = '读取第'.$n.'行;';
			$num = count($data); 
			if ($num>0)
			{
				
				$addr=trim($data[0]); 
				$a_c=trim($data[1]);
				$yr_built=trim($data[2]);
				$sqft=trim($data[3]);
				$area=trim($data[4]);
				$area_code=trim($data[5]);
				$bsmt1_out=trim($data[6]);
				$bsmt2_out=trim($data[7]);
				$br=trim($data[8]);
				$br_plus=trim($data[9]);
				$central_vac=trim($data[10]);
				$community=trim($data[11]);
				$community_code=trim($data[12]);
				$cross_st=trim($data[13]);
				$elevator=trim($data[14]);
				$constr1_out=trim($data[15]);
				$constr2_out=trim($data[16]);
				$extras=trim($data[17]);
				$fpl_num=trim($data[18]);
				$comp_pts=trim($data[19]);
				$furnished=trim($data[20]);
				$gar_spaces=trim($data[21]);

				
				if ($location != '')
				{
					$local_zb=$this->actionGetCodeAddress2($location);
					$longitude=$local_zb['lng']; //	经度////////待定
					$latitude=$local_zb['lat']; //	纬度////////待定
//					p($longitude);
//					p($latitude);
				}		

				$is_print_value=0; //是否输入 读入的csv值
				if (1==$is_print_value)
				{
				p('名称: '.$addr);
				p('总价: '.$a_c);
				p('开发商: '.$yr_built);
				p('所属项目: '.$sqft);
				p('挂牌时间: '.$area);
				p('地址: '.$area_code);
				p('描述: '.$bsmt1_out);
				p('房源图片: '.$bsmt2_out);
				p('组图: '.$br);
				p('房源视频路径: '.$br_plus);
				p('作者: '.$central_vac);
				p('是否推荐: '.$community);
				p('城市: '.$community_code);
				p('地区: '.$cross_st);
				p('社区: '.$elevator);
				p('投资类型: '.$constr1_out);
				p('物业类型: '.$constr2_out);
				p('土地面积: '.$extras);
				p('房屋面积: '.$fpl_num);
				p('房屋层数: '.$comp_pts);
				p('卧室数量: '.$furnished);
				p('卫生间数量: '.$gar_spaces);
	

				}
				
				parent::_acl('house_create');
				$model = new House();
				$model -> addr = $addr; //名称 必填
				$model -> a_c = $a_c; //总价 必填
				$model ->yr_built  = $yr_built; //开发商 必填
				$model ->sqft  = $sqft; //所属项目 必填
				$model ->area  = $area; //挂牌时间
				$model ->area_code  = $area_code; //	地址
				$model ->bsmt1_out  = $bsmt1_out; //	地址
				$model ->bsmt2_out  = $bsmt2_out; //	地址
				$model ->br  = $br; //	地址
				$model ->br_plus  = $br_plus; //	地址
				$model ->central_vac  = $central_vac; //	地址
				$model ->community  = $community; //	地址
				$model ->community_code  = $community_code; //	地址
				$model ->cross_st  = $cross_st; //	地址
				$model ->elevator  = $elevator; //	地址
				$model ->constr1_out  = $constr1_out; //	地址
				$model ->constr2_out  = $constr2_out; //	地址
				$model ->extras  = $extras; //	地址
				$model ->fpl_num  = $fpl_num; //	地址
				$model ->comp_pts  = $comp_pts; //	地址
				$model ->furnished  = $furnished; //	地址
				$model ->gar_spaces  = $gar_spaces; //	地址
				
				$is_save=1; //是否保存
				if ($is_save == 1)
				{
					if ($model->save()) {
						//echo '导入数据成功！';
						$msg = $msg . '导入数据成功！';
						$state=1;
					}else{
						//echo '导入数据失败！';
						$msg = $msg .  '导入数据失败！';
						$state=0;
						//print_r($model->errors);
					}
				}
				
			}
			break;
		}
	} 
	fclose($handle); //关闭指针 
	unset($handle);
	return array('line'=>$line, 'state'=>$state, 'msg' => $msg, 'err'=>$model->errors);
	unset($model);
}

		

/**
* 读取csv文件的某一行数据
*/
function get_file_line( $file_name, $line ){
	//echo 'get_file_line'.'<br />';
  $n = 0;
  $handle = fopen($file_name,'r');
  while ($data = fgetcsv($handle, 10000)) { 
	  ++$n;
		//echo count($out).'<br />';
		
		//读取第一行
		if ($n==1)
		{
			$num = count($data); 
			echo '<tr> ' . "\r\n";
			echo '<td>' . $line .'</td>' . "\r\n";
			for ($i = 0; $i < $num; $i++) { 
					//echo '<td>'.iconv('gb2312', 'utf-8', $data[$i]).'</td>' . "\r\n"; //中文转码 
					$this->p($i . '行： ' . $data[$i]);
			} 
			echo '</tr> ' . "\r\n";
		}
		
		if($line==$n) 
		{
			$num = count($data); 
			if ($num>0)
			{
				$name=$data[0]; //名称
//				$prepay=$data[]; //首付
				$total_price=$data[38]; //总价
//				$developer=$data[]; //开发商
//				$subject_id=$data[]; //所属项目
				$accessDate=$data[45]; //挂牌时间
//				$location=$data[0]; //	地址
//				$introduction=$data[]; //	描述
//				$house_image=$data[]; //	房源图片
//				$image_list=$data[]; //	组图
//				$video_url=$data[]; //	房源视频路径
//				$author=$data[]; //	作者
//				$recommend=$data[]; //	是否推荐
				$city_id=$this->city_id($data[44]); //	城市
//				$district_id=$data[]; //	地区
				$community=$data[11]; //	社区
//				$investType_id=$data[]; //	投资类型
				$propertyType_id=$this->propertyType_id($data[134]); //	物业类型
//				$land_area=$data[]; //	土地面积
				$house_area=$data[3]; //	房屋面积
				$floor_num=$data[131]; //	房屋层数
				$bedroom_num=$this->rooms_sum($data[8], $data[9]); //	卧室数量
				$toilet_num=$data[136]; //	卫生间数量
				$kitchen_num=$this->rooms_sum($data[24], $data[25]);; //	厨房数量
				$park_num=$data[21]; //	停车位数量
//				$house_size=$data[]; //	房屋规格
//				$land_ownership=$data[]; //	土地所有权
				$door_direction=$this->door_direction($data[19]); //	大门朝向
				$construction_year=$data[2]; //	建造年份
				$zipcode=$data[47]; //	邮编
//				$certificate=$data[]; //	认证房源
//				$lift=$data[]; //	电梯
//				$carport=$data[]; //	车库
//				$embassy=$data[]; //	会客厅
				$mls_code=$data[42]; //	MLS编号
//				$facilities=$data[]; //	附近设施
//				$longitude=$data[]; //	经度
//				$latitude=$data[]; //	纬度
//				$match=$data[]; //	配套设施
//				$is_sell=$data[]; //	是否售卖

				//parent::_acl('house_create');
//				$model = new House();
//				$model -> name = $name;
//				$model -> total_price = $total_price;
//				if ($model->save()) {
//					echo '导入数据成功！';
//				}

				
			}
			//echo $num;
			echo '<tr> ' . "\r\n";
			echo '<td>' . $line .'</td>' . "\r\n";
			
			if ($data[0] != '')
			{
				$local .= $data[2];
				if ($data[4] != '') $local .= ',' . $data[4];
				//$local_zb=actionGetCodeAddress2($local);
			}		
			echo '<td>X:'. $local_zb['lng'] . '   Y:' . $local_zb['lat'] . '</td>' . "\r\n";
			
			for ($i = 0; $i < $num; $i++) { 
					echo '<td>'.iconv('gb2312', 'utf-8', $data[$i]).'</td>' . "\r\n"; //中文转码 
			} 
			
			echo '</tr> ' . "\r\n";
			break;
		}
	} 
	fclose($handle); //关闭指针 
}
		
/**
* 获取csv文件的总行数
*/
function get_file_line_count( $file_name ){
  $n = 0;
  $handle = fopen($file_name,'r');
  while ($data = fgetcsv($handle, 10000)) { 
	  $n++;
	} 
	fclose($handle); //关闭指针 
	unset($handle);
	return $n;
}


/**
* 读取csv文件的某一行数据
*/
function get_file_line2( $file_name, $line ){
  $n = 0;
  $handle = fopen($file_name,'r');
  if ($handle) {
    while (!feof($handle)) {
        ++$n;
        $out = fgets($handle, 4096);
        if($line==$n) break;
    }
    fclose($handle);
  }
  if( $line==$n) return $out;
  return false;
//	if ($line==$n)
//	{
//		print_csv($handle);
//	}
}
//echo get_file_line("windows_2011_s.csv", 10);

//转换cvs数据
function to_db_data_int($d)
{
	if( isset($d) || !empty($d) ) $d=0;
	return $d;
}


//转换cvs数据, 总价
function total_price($d)
{
	$ret=$d;	
	if( !isset($d) || empty($d) ) $ret=0;
	$ret=$ret/10000;
	$ret=round($ret, 2);
	return $ret;
}

//转换物业类型
//独栋别墅	Detached
//双拼别墅	Semi-Detached， Link, Duplex
//联排别墅	Townhouse，Att∕Row∕Twnhouse, Triplex, Fourplex, Multiplex
//豪华公寓	Condo,Apartment
//乡间小屋/度假屋	Cottage, Rural Resid
//农场屋/农庄	Farm
//空地	Vacant Land
//其他	Mobile/Trailer, Det W/Com Elements, Store W/Apt/offc
function propertyType_id($p)
{
	$p = trim($p);
	$id=0;
	switch ($p)
	{
		case 'Detached':
			$id=1;
			break;
		case 'Semi-Detached':
			$id=2;
			break;
		case 'Link':
			$id=2;
			break;
		case 'Duplex':
			$id=2;
			break;
		case 'Townhouse，Att∕Row∕Twnhouse':
			$id=3;
			break;
		case 'Triplex':
			$id=3;
			break;
		case 'Fourplex':
			$id=3;
			break;
		case 'Multiplex':
			$id=3;
			break;
		case 'Condo':
			$id=6;
			break;
		case 'Apartment':
			$id=6;
			break;	
		case 'Cottage':
			$id=7;
			break;
		case 'Rural Resid':
			$id=7;
			break;
		case 'Farm':
			$id=8;
			break;
		case 'Vacant Land':
			$id=9;
			break;
		case 'Mobile/Trailer':
			$id=10;
			break;
		case 'Det W/Com Elements':
			$id=10;
			break;
		case 'Store W/Apt/offc':
			$id=10;
			break;
		default:
			$id=10;
	}
	return $id;
}

//转换周边环境、配套
function facilities($c1, $c2, $c3, $c4, $c5, $c6)
{
	$ret='';
	$f=array(
		'waterfront' => '临水',
		'pond' => '临塘,',
		'steam' => '临溪,',
		'river' => '临河,',
		'lake(beach)' => '临湖（海）,',
		'marina' => '临游艇停靠区,',
		'treed' => '临树林,',
		'wooded' => '临森林,',
		'grnbelt' => '临绿色保护带,',
		'conserv' => '临森林保护区,',
		'ravine' => '临谷,',
		'school' => '学校,',
		'hospital' => '医院,',
		'public transit' => '公共交通,',
		'park' => '公园,',
		'golf' => '高尔夫球场,',
		'library' => '图书馆,'
	);
	$ret= $ret . $f[strtolower(trim($c1))];
	$ret= $ret . $f[strtolower(trim($c2))];
	$ret= $ret . $f[strtolower(trim($c3))];
	$ret= $ret . $f[strtolower(trim($c4))];
	$ret= $ret . $f[strtolower(trim($c5))];
	$ret= $ret . $f[strtolower(trim($c6))];
	if($ret != '') {
		$ret=substr($ret,0,strlen($ret)-1);
	}
	else{
		$ret='-';
	}
	return $ret;
}

//转换城市ID
function city_id($c)
{
	$id=1;
	//if($c=='') $id=1;
	return $id;
}
//转换地区ID
function district_id($d)
{
	$id=1;
	//if($c=='') $id=1;
	return $id;
}

//转换社区ID
function community($d)
{
	$id=1;
	//if($c=='') $id=1;
	return $id;
}

//转换土地面积
function land_area($front, $depth)
{
	$ret=0;
	if (is_numeric($front) && is_numeric($depth)){
		$ret=round($front*$depth, 2);
	}
	return $ret;
}

//转换房屋面积
function house_area($d)
{
	$id=0;
	if (is_numeric($d)) $id=$d;
	//if($c=='') $id=1;
	return $id;
}


//转换房间数量
function rooms_sum($room1, $room2)
{
	if ($room1 == '') $room1=0;
	if ($room2 == '') $room2=0;
	return $room1+$room2;
}
//转换大门朝向
function door_direction($d)
{
//（N-北，S-南，E-东，W-西）
	$chn='';
	switch ($d)
	{
		case 'N':
			$chn='北';
			break;
		case 'S':
			$chn='南';
			break;
		case 'E':
			$chn='东';
			break;
		case 'W':
			$chn='西';
			break;
	}
	return $chn;
}

//转换电梯
function lift($d)
{
	$ret='没有';
	if (trim(strtolower($d))=='elevator') $ret='有';
	return $ret;
}

//转换中央空调
function ac($d)
{
	$ret='没有';
	if (trim(strtolower($d))=='central air') $ret='有';
	return $ret;
}

//转换中央吸尘
function central_vac($d)
{
	$ret='没有';
	if (trim(strtolower($d))=='central vac') $ret='有';
	return $ret;
}

//转换是否配套家具
function furnished($d)
{
	$ret='没有';
	if (trim(strtolower($d))=='furnished') $ret='有';
	return $ret;
}


//转换是否地下室
function basement($d)
{
	$ret='有';
	if (trim(strtolower($d))=='none') $ret='没有';
	return $ret;
}

//转换是否游泳池
function pool($d)
{
	$ret='没有';
	if (trim(strtolower($d))=='pool') $ret='有';
	return $ret;
}

//转换是否壁炉
function fireplace_stove($d)
{
	$ret='没有';
	if (trim(strtolower($d))=='fireplace/stove') $ret='有';
	return $ret;
}

//转换暖气
function heat($d1, $d2)
{
	$ret='';
	if ($d1 != '' && $d2 != '') {
		$ret=$d1.', '.$d2;
	}
	else{
			if ($d1 != ''){
				$ret=$d1;
			}
			else{
				$ret=$d2;	
			}
	}
	return $ret;
}





function y_or_n($yn)
{
	
}




function p( $str)
{
	echo $str.'<br />';	
}



/**
* 获取地址所在经纬度
*/
function actionGetCodeAddress2($location) {
	$result = array();
//	$location = $_GET['location'];
//	echo $location;
	if (!empty($location)) {
			$url = 'http://maps.google.cn/maps/api/geocode/json?address=' . trim($location) . ',加拿大&sensor=false&language=zh-CN';
			//$url = 'http://maps.google.cn/maps/api/geocode/json?address=' . '129 Ulster St,Toronto' . ',加拿大&sensor=false&language=zh-CN';
			//$url = 'http://maps.google.cn/maps/api/geocode/json?address=129 Ulster St Toronto,加拿大&sensor=false&language=zh-CN';
			$url = str_replace(' ', '+', $url);
			// header
			$userAgent = array(
					'Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20100101 Firefox/22.0', // FF 22
					'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36', // Chrome 27
					'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)', // IE 9
					'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', // IE 8
					'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', // IE 7
					'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Maxthon/4.1.0.4000 Chrome/26.0.1410.43 Safari/537.1', // Maxthon 4
					'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E)', // 2345 2
					'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; QQBrowser/7.3.11251.400)', // QQ 7
					'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)', // Sougo 4
					'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0) LBBROWSER', //  liebao 4
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0");
			curl_setopt($ch, CURLOPT_REFERER, "http://hqn.jschina.com.cn/prop.asp?id=1975");
			curl_setopt($ch, CURLOPT_TIMEOUT, 16);
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent[rand(0, count($userAgent) - 1)]);
			curl_setopt($ch, CURLOPT_PROXY, null);
			// 伪造IP头
			$ip = rand(27, 64) . "." . rand(100, 200) . "." . rand(2, 200) . "." . rand(2, 200);
			$headerIp = array("X-FORWARDED-FOR:{$ip}", "CLIENT-IP:{$ip}");
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headerIp);
			// 读取数据
			$res = @curl_exec($ch);
			curl_close($ch);
			$addrInfo = json_decode($res, true);
			$result['IsError'] = false;
			$result['lng'] = $addrInfo['results'][0]['geometry']['location']['lng'];
			$result['lat'] = $addrInfo['results'][0]['geometry']['location']['lat'];
	} else {
			$result['IsError'] = true;
			$result['Message'] = '数据接收失败';
	}
	//var_dump ($result);
	return $result;
}

    /**
     * 更新
     *
     * @param  $id
     */
    public function actionUpdate( $id ) {
        parent::_acl('investAim_update');
        $model = parent::_dataLoad( new InvestAim(), $id );
        if ( isset( $_POST['InvestAim'] ) ) {
            $model->attributes = $_POST['InvestAim'];
            if ($model->save()) {
                AdminLogger::_create( array ( 'catalog' => 'create' , 'intro' => '录入投资目的,ID:' . $model->id ) );
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
                parent::_acl( 'investAim_delete' );
                AdminLogger::_create( array ( 'catalog' => 'delete' , 'intro' => '删除投资目的，ID:' . $ids ) );
                parent::_delete( new InvestAim(), $ids, array ( 'index' ));
                break;
            default:
                throw new CHttpException(404, '错误的操作类型:' . $command);
                break;
        }
    }
}


function p($str){
	echo $str . '<br />';
}
