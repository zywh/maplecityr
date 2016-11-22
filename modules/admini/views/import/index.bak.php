<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>从Excel文件中导入房源22</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="<?php echo $this->createUrl('create')?>" class="actionBtn"><span>录入</span></a></li>
    </ul>
  </div>
</div>

<table border="1" cellpadding="0" cellspacing="0">
<?php
	$filename = 'data_0810.csv';
	if (empty ($filename)) { 
			echo '请选择要导入的CSV文件！'; 
			exit; 
	} 
	
	$id=$_GET["id"];
	
	$csv_count=$_GET["count"];
	
	$id=8;
	$csv_count=7;
	if ($id == '') 
	{
		$id=2;
		$csv_count=get_file_line_count($filename);
	}
	get_file_line($filename, $id);

	//print_csv($handle);

	


function print_csv($handle) { 	
	$index=1;
	while ($data = fgetcsv($handle, 10000)) { 
			$num = count($data); 
			echo $num;
			echo '<tr> ' . "\r\n";
			echo '<td>' . $index .'</td>' . "\r\n";
//			
//			
//			echo '<td>' . $data[0].'</td>' . "\r\n";
//			echo '<td>' . $data[1].'</td>' . "\r\n";
//			echo '<td>' . $data[2].'</td>' . "\r\n";
//			echo '<td>' . $data[3].'</td>' . "\r\n";
//			echo '<td>' . $data[8].'</td>' . "\r\n";
//			echo '<td>' . $data[200].'</td>' . "\r\n";
//			echo '<td>' . $data[213].'</td>' . "\r\n";
//			
//			if ($data[2] != '')
//			{
//				$local .= $data[2];
//				if ($data[8] != '') $local .= ',' . $data[8];
//				
//			}		
//			$local_zb=actionGetCodeAddress2($local);
//			echo '<td>X:'. $local_zb['lng'] . '   Y:' . $local_zb['lat'] . '</td>' . "\r\n";

			
			for ($i = 0; $i < $num; $i++) { 
					echo '<td>'.iconv('gb2312', 'utf-8', $data[$i]).'</td>' . "\r\n"; //中文转码 
			} 
			echo '</tr> ' . "\r\n";
			$index++;
	} 
	//$local_zb=actionGetCodeAddress2($local);
	//echo $local_zb;
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
	return $n;
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
		
		if ($n==1)
		{
			$num = count($data); 
			echo '<tr> ' . "\r\n";
			echo '<td>' . $line .'</td>' . "\r\n";
			for ($i = 0; $i < $num; $i++) { 
					//echo '<td>'.iconv('gb2312', 'utf-8', $data[$i]).'</td>' . "\r\n"; //中文转码 
					p($i . '行： ' . $data[$i]);
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
				$city_id=city_id($data[44]); //	城市
//				$district_id=$data[]; //	地区
				$community=$data[11]; //	社区
//				$investType_id=$data[]; //	投资类型
				$propertyType_id=propertyType_id($data[134]); //	物业类型
//				$land_area=$data[]; //	土地面积
				$house_area=$data[3]; //	房屋面积
				$floor_num=$data[131]; //	房屋层数
				$bedroom_num=rooms_sum($data[8], $data[9]); //	卧室数量
				$toilet_num=$data[136]; //	卫生间数量
				$kitchen_num=rooms_sum($data[24], $data[25]);; //	厨房数量
				$park_num=$data[21]; //	停车位数量
//				$house_size=$data[]; //	房屋规格
//				$land_ownership=$data[]; //	土地所有权
				$door_direction=door_direction($data[19]); //	大门朝向
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
//echo get_file_line("windows_2011_s.csv", 10);



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
		
?>

</table>

<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
      <tr class="tb_header">
          <th width="30%">ID</th>
          <th>类型名称</th>
          <th width="30%">操作</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td ><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"><?php echo $row->id;?></td>
        <td ><span class="alias"><?php echo $row->name; ?></span></td>
        <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>
        </td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
      <td colspan="3"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel">
          <input type="checkbox" name="chkall" id="chkall" onClick="checkAll(this.form, 'id');" />
          <label for="chkall">全选</label>
          <select name="command">
            <option>选择操作</option>
            <option value="delete">批量删除</option>
          </select>
          <input id="submit_maskall" class="button confirmSubmit" type="submit" value="提交" name="maskall" />
        </div>
      </td>
    </tr>
  </form>
</table>
<?php $this->renderPartial('/_include/footer');?>
<script language="JavaScript"> 
window.onload = function(){
//全部加载完成！！
//alert('页面加载完毕！');
var ids=<?php echo $id;?> + 1;
var count=<?php echo $csv_count;?> ;
if (ids <= count)
 //window.location.href="/index.php?r=admini/import/index&id="+ids+"&count="+count;
 window.location.href="<?php echo Yii::app()->createUrl('admini/import/index&id='); ?>"+ids+"&count="+count;

//alert(ids);
}


function myrefresh() 
{ 
	
} 
//setTimeout('myrefresh()',5000); //指定1秒刷新一次 
</script>
