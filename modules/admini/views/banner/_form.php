<?php if (CHtml::errorSummary($model)):?>
<table id="tips">
  <tr>
    <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
  </tr>
</table>
<?php endif?>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform','enctype'=>'multipart/form-data'))); ?>
<table class="form_table">
  <tr>
    <td class="tb_title" >标题：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>255, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">链接：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'url',array('size'=>50,'maxlength'=>255, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">图片：</td>
  </tr>
  <tr >
    <td colspan="2" ><input name="attach" type="file" id="attach" />
      <?php if($model->image):?>
      <a href="<?php echo $this->_baseUrl.'/'. $model->image?>" target="_blank"><img src="<?php echo $this->_baseUrl.'/'. $model->image?>" width="50" align="absmiddle" /></a>
      <?php endif?></td>
  </tr>
  <tr>
    <td class="tb_title">描述：</td>
  </tr>
  <tr >
    <td><?php echo $form->textArea($model,'describe',array('rows'=>5, 'cols'=>90)); ?></td>
  </tr>
  <tr class="submit">
    <td colspan="2" >
      <input name="oAttach" type="hidden" value="<?php echo $model->image; ?>" />
      <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" />
    </td>
  </tr>
</table>
<?php $form=$this->endWidget(); ?>