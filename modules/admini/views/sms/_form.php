<?php if (CHtml::errorSummary($model)):?>
<table id="tips">
  <tr>
    <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
  </tr>
</table>
<?php endif?>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform'))); ?>
<table class="form_table">
  <tr>
    <td class="tb_title" >帐号：</td>
  </tr>
  <tr>
    <td ><?php echo $form->textField($model,'uid',array('size'=>20,'maxlength'=>255, 'class'=>'validate[required]')); ?>
  </tr>
  <tr>
    <td class="tb_title" >密码：</td>
  </tr>
  <tr>
    <td ><?php echo $form->textField($model,'password',array('size'=>20,'maxlength'=>255, 'class'=>'validate[required]')); ?>
  </tr>
  <tr>
    <td class="tb_title" >模板ID：</td>
  </tr>
  <tr>
    <td ><?php echo $form->textField($model,'cid',array('size'=>20,'maxlength'=>255, 'class'=>'validate[required]')); ?>
  </tr>
  </tbody>
  <tr class="submit">
    <td colspan="2" >
      <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" /></td>
  </tr>
</table>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();
});
</script>
<?php $form=$this->endWidget(); ?>