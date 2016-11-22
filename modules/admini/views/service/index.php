<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>海外服务</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="<?php echo $this->createUrl('create')?>" class="actionBtn"><span>录入</span></a></li>
    </ul>
  </div>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
    <tr class="tb_header">
      <th width="10%">ID</th>
      <th width="20%">标题</th>
      <th width="20%">封面图片</th>
      <th width="20%">创建时间</th>
      <th width="20%">最新更新时间</th>
      <th width="10%">操作</th>
    </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
      <?php foreach ($datalist as $row):?>
        <tr class="tb_list">
            <td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"><?php echo $row->id;?></td>
            <td><span class="alias"><?php echo $row->title; ?></span></td>
            <td><img src="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo $row->image; ?>" alt="" width="120" height="80"/></td>
            <td><span class="alias"><?php echo date('Y-m-d H:i:s', $row->create_time); ?></span></td>
            <td><span class="alias"><?php echo date('Y-m-d H:i:s', $row->last_update_time); ?></span></td>
            <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>
            </td>
        </tr>
      <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
      <td colspan="5"><div class="cuspages right">
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
