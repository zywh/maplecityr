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
    <td class="tb_title" >地区名称：</td>
  </tr>
  <tr>
    <td><?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>50, 'class'=>'validate[required]')); ?>
  </tr>
  <tr>
    <td class="tb_title" >名称拼音：</td>
  </tr>
  <tr>
    <td><?php echo $form->textField($model,'pinyin',array('size'=>20,'maxlength'=>50, 'class'=>'validate[required]')); ?>
  </tr>
  <tr>
    <td class="tb_title" >英文名称：</td>
  </tr>
  <tr>
    <td><?php echo $form->textField($model,'englishName',array('size'=>20,'maxlength'=>50, 'class'=>'validate[required]')); ?>
  </tr>
  <tr>
    <td class="tb_title">所在城市:</td>
  </tr>
  <tr>
    <td>
      <select name="District[city_id]" id="District_city_id" class='validate[required]'>
        <option value>==所在城市==</option>
        <?php foreach((array)Bagecms::getList('City', '_city') as $city):?>
          <option value="<?php echo $city['id']?>" <?php XUtils::selected($city['id'], $model->city_id);?>><?php echo $city['name']?></option>
        <?php endforeach;?>
      </select>
    </td>
  </tr>
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