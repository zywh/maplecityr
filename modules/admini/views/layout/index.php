<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>房屋布局管理</h3>
  <div class="searchArea">
    <ul class="action left">
        <?php if($house_id != 0){ ?>
        <li><a href="<?php echo $this->createUrl('create',array('id'=>$house_id))?>" class="actionBtn"><span>继续录入</span></a></li>
        <?php }else{ ?>
        <li><a href="<?php echo Yii::app()->createUrl('admini/house/index')?>" class="actionBtn"><span>录入</span></a></li>&nbsp;(<span style="color: #ff0000;display: inline-block;padding: 7px 0;">首次录入会进入房源列表,房源有对应的布局添加操作</span>)
        <?php } ?>
    </ul>
    <div class="search right">
        <?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('index'),'htmlOptions'=>array('name'=>'xform', 'class'=>'right '))); ?>
        房源
        <select name="house_id" id="house_id">
            <option value="">=所有房源=</option>
            <?php foreach((array)$this->house_list as $house):?>
                <option value="<?php echo $house['id']?>" <?php XUtils::selected($house['id'], $model->house_id);?>><?php echo $house['name']?></option>
            <?php endforeach;?>
        </select>&nbsp;&nbsp;
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
	$("#house_id").val('<?php echo Yii::app()->request->getParam('house_id')?>');
});
</script>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
      <tr class="tb_header">
          <th width="10%">ID</th>
          <th width="20%">房源名称</th>
          <th width="8%">楼层</th>
          <th width="8%">房间</th>
          <th width="8%">长(m)</th>
          <th width="8%">宽(m)</th>
          <th width="8%">面积(m&sup2;)</th>
          <th width="22%">描述</th>
          <th width="8%">操作</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td ><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"><?php echo $row->id;?></td>
        <td ><span><?php echo $row->house->name; ?></span></td>
        <td><span><?php echo $row->floor; ?></span></td>
        <td><span><?php echo $row->room; ?></span></td>
        <td><span><?php echo $row->length; ?></span></td>
        <td><span><?php echo $row->width; ?></span></td>
        <td><span><?php echo $row->area; ?></span></td>
        <td><span><?php echo $row->describe; ?></span></td>
        <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id, 'original'=>Yii::app()->request->getUrl()))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>&nbsp;&nbsp;
        </td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
      <td colspan="9"><div class="cuspages right">
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
<script type="text/javascript">
    $(function(){
    });
</script>
