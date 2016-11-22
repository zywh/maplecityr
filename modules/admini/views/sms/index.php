<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>短信验证设置</h3>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
    <thead>
      <tr class="tb_header">
          <th width="5%">ID</th>
          <th width="30%">帐号</th>
          <th width="30%">密码</th>
          <th width="30%">模板ID</th>
          <th width="5%">操作</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td><?php echo $row->id;?></td>
        <td><span class="alias"><?php echo $row->uid; ?></span></td>
        <td><span class="alias"><?php echo $row->password; ?></span></td>
        <td><span class="alias"><?php echo $row->cid; ?></span></td>
        <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
        </td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
</table>
<?php $this->renderPartial('/_include/footer');?>
