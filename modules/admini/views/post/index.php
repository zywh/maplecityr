<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>内容管理</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="<?php echo $this->createUrl('create', array('original'=>Yii::app()->request->getUrl()))?>" class="actionBtn"><span>录入</span></a></li>
    </ul>
    <div class="search right">
      <?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('index'),'htmlOptions'=>array('name'=>'xform', 'class'=>'right '))); ?>
      分类
      <select name="catalogId" id="catalogId">
        <option value="">==选择类别==</option>
        <?php foreach((array)Catalog::get(0, $this->_catalog) as $catalog):?>
          <option value="<?php echo $catalog['id']?>"><?php echo $catalog['str_repeat']?><?php echo $catalog['catalog_name']?></option>
        <?php endforeach;?>
      </select>(<span style="color:#ff0000;">有子选项的选择最小子选项</span>)
      &nbsp;&nbsp;&nbsp;&nbsp;标题
      <input id="title" type="text" name="title" value="" class="txt" size="50"/>&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="searchsubmit" type="submit"  value="查询" class="button "/>
      <script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
      <?php $form=$this->endWidget(); ?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#title").val('<?php echo Yii::app()->request->getParam('title')?>');
	$("#catalogId").val('<?php echo Yii::app()->request->getParam('catalogId')?>');
});
</script>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
      <tr class="tb_header">
        <th width="10%">ID</th>
        <th>标题</th>
        <th width="16%">分类</th>
        <th width="9%">浏览</th>
        <th width="15%">录入时间</th>
        <th width="8%">操作</th>
      </tr>
    </thead>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
      <td><input type="checkbox" name="id[]" value="<?php echo $row->id?>">
        <?php echo $row->id?></td>
      <td><?php echo $row->title?></td>
      <td><?php echo $row->catalog->catalog_name?></td>
      <td><span><?php echo $row->view_count?></span></td>
      <td><?php echo date('Y-m-d H:i',$row->create_time)?></td>
      <td>
        <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id, 'original'=>Yii::app()->request->getUrl()))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
        <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>
      </td>
    </tr>
    <?php endforeach;?>
    <tr class="operate">
      <td colspan="6"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel">
          <input type="checkbox" name="chkall" id="chkall" onClick="checkAll(this.form, 'id')" />
          <label for="chkall">全选</label>
          <select name="command">
            <option>选择操作</option>
            <option value="delete">删除</option>
          </select>
          <input id="submit_maskall" class="button confirmSubmit" type="submit" value="提交" name="maskall" />
        </div></td>
    </tr>
  </form>
</table>
<?php $this->renderPartial('/_include/footer');?>
