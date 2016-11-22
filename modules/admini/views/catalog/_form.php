<?php if (CHtml::errorSummary($model)):?>

<table id="tips">
  <tr>
    <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
  </tr>
</table>
<?php endif?>
<script type="text/javascript">
$(function(){
  $("#Catalog_parent_id").val(<?php echo $parentId ?>);
});
</script>
<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform', 'enctype'=>'multipart/form-data'))); ?>
<table class="form_table">
  <tr>
    <td class="tb_title">名称：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'catalog_name',array('size'=>50,'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">所属分类：</td>
  </tr>
  <tr >
    <td >
      <select name="Catalog[parent_id]" id="Catalog_parent_id">
        <option value="0">==顶级分类==</option>
        <?php foreach((array)Catalog::get(0, $this->_catalog) as $catalog):?>
        <option value="<?php echo $catalog['id']?>" <?php XUtils::selected($catalog['id'], $model->parent_id);?>><?php echo $catalog['str_repeat']?><?php echo $catalog['catalog_name']?></option>
        <?php endforeach;?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="tb_title">封面图片：(<span style="color: #ff0000;">必须上传，否则页面无图片显示</span>)</td>
  </tr>
  <tr >
    <td ><input name="attach" type="file" id="attach" />
      <?php if ($model->image):?>
      <a href="<?php echo $this->_baseUrl.'/'. $model->image?>" target="_blank"><img src="<?php echo $this->_baseUrl.'/'. $model->image?>" width="50" align="absmiddle" /></a>
      <?php endif?></td>
  </tr>
  <tr class="submit">
    <td><input name="oAttach" type="hidden" value="<?php echo $model->image ?>" />
      <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" /></td>
  </tr>
</table>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
