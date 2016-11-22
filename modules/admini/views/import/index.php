<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>从Excel文件中导入房源</h3>
  <div class="searchArea">
    <ul class="action left" >
      <li class="current"><a href="<?php echo $this->createUrl('index')?>" class="actionBtn"><span>返回</span></a></li>
    </ul>
    
  </div>
</div>
<form name="xform" id="xform" action="<?php echo Yii::app()->createUrl('admini/import/import'); ?>" method="post">
<table class="form_table">
  <tr>
    <td class="tb_title" >文件名称：</td>
  </tr>
  <tr>
    <td ><input size="20" maxlength="255" class="validate[required]" name="filename" id="InvestAim_name" type="text" value="data_0810" />.csv  </tr>

  </tr>
  <tr class="submit">
    <td colspan="2" >
      <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" /></td>
  </tr>
</table>
</form>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();
});
</script>

<!--<table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
<tr>
<td width="100">输入文件名</td>
<td>.csv</td>
</tr>
</table>-->

<?php $this->renderPartial('/_include/footer');?>