<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>从Excel文件中导入房源</h3>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
<tr>
<td align="center"><p align="center"><?php echo $import_result['msg'];?></p></td>
</tr>
</table>

<table border="1" cellpadding="0" cellspacing="0" width="80%" align="center">
<tr>
<td bgcolor="#3366FF" width="<?php echo $progress2;?>%"></td>
<td></td>
</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
<tr>
<td align="center"><p align="center"><?php echo $progress;?>%</p></td>
</tr>
<tr>
<td align="center" height="20"></td>
</tr>
<tr>
<td align="center"><p align="center"><input name="11" type="button" value="如果导入进度终止，请点击此按钮继续导入" onclick="myrefresh();" /></p></td>
</tr>
</table>

<p style="font-size:18px; font-weight:bolder;">错误记录：</p>
<?php echo $_SESSION['import_err'] ;?>


<?php $this->renderPartial('/_include/footer');?>
<script language="JavaScript"> 
var ids=<?php echo $id;?> + 1;
var count=<?php echo $csv_count;?> ;

window.onload = function(){
//全部加载完成！！
//alert('页面加载完毕！');
//if (ids <= count)
// //window.location.href="/index.php?r=admini/import/index&id="+ids+"&count="+count;
// window.location.href="<?php echo Yii::app()->createUrl('admini/import/import&id='); ?>"+ids+"&count="+count;

//alert(ids);

	if (ids % 50 == 0)
	{
		//alert('20!');
		setTimeout('myrefresh()',3000); //指定3秒刷新一次 
	}
	else
	{
		myrefresh() ;
	}


}


function myrefresh() 
{ 
	if (ids <= count)
 //window.location.href="/index.php?r=admini/import/index&id="+ids+"&count="+count;
 window.location.href="<?php echo Yii::app()->createUrl('admini/import/import&id='); ?>"+ids+"&count="+count;

	
} 
//setTimeout('myrefresh()',3000); //指定1秒刷新一次 
</script>
