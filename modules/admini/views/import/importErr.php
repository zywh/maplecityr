<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>从Excel文件中导入房源</h3>
  <div class="searchArea">
    <ul class="action left" >
      <li class="current"><a href="<?php echo $this->createUrl('index')?>" class="actionBtn"><span>返回</span></a></li>
    </ul>
    
  </div>
</div>

<table class="form_table">
  <tr>
    <td>文件不存在！</td>
  </tr>
</table>

<!--<table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
<tr>
<td width="100">输入文件名</td>
<td>.csv</td>
</tr>
</table>-->

<?php $this->renderPartial('/_include/footer');?>