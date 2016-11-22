<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>汇率设置</h3>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
    <thead>
      <tr class="tb_header">
          <th width="30%">ID</th>
          <th>加币对人民币汇率</th>
          <th width="30%">操作</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td><?php echo $row->id;?></td>
        <td><span class="alias"><?php echo $row->rate; ?></span></td>
        <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
        </td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
</table>
<?php $this->renderPartial('/_include/footer');?>
