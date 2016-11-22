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
    <td class="tb_title" >名称：</td>
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
  <tr class="submit">
    <td colspan="2" >
      <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" />
    </td>
  </tr>
</table>
    <script type="text/javascript">
        $(function(){
            $("#xform").validationEngine();
        });
    </script>
<?php $form=$this->endWidget(); ?>