<style type="text/css">
    #get_lng_lat{text-decoration: none; width: 40px; height: 6px; line-height: 6px; border: 1px solid #3079ED; background: #4D90FE; padding: 4px; color: #fff;}
    #get_lng_lat:hover{background: #3079ED}
</style>
<?php if (CHtml::errorSummary($model)): ?>
    <table id="tips">
        <tr>
            <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
        </tr>
    </table>
<?php endif ?>
<?php $form = $this->beginWidget('CActiveForm', array('id' => 'xform', 'htmlOptions' => array('name' => 'xform'))); ?>
<table class="form_table">
    <tr>
        <td class="tb_title" >省份名称：</td>
    </tr>
    <tr>
        <td ><?php echo $form->textField($model, 'name', array('size' => 20, 'maxlength' => 50, 'class' => 'validate[required]')); ?>
    </tr>
    <tr>
        <td class="tb_title" >名称拼音：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'pinyin', array('size' => 20, 'maxlength' => 50, 'class' => 'validate[required]')); ?>
    </tr>
    <tr>
        <td class="tb_title" >英文名称：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'englishName', array('size' => 20, 'maxlength' => 50, 'class' => 'validate[required]')); ?>
    </tr>
    <tr>
        <td class="tb_title">省份简介：</td>
    </tr>
    <tr >
        <td><?php echo $form->textArea($model, 'describe', array('rows' => 5, 'cols' => 90)); ?></td>
    </tr>
    <tr>
        <td>
            <a href="javascript:void(0);" id="get_lng_lat">获取经纬度</a>
            经度：<?php echo $form->textField($model, 'lnt', array('size' => 10, 'maxlength' => 50, 'readonly' => 'readonly', 'class' => 'validate[required]')); ?>
            纬度：<?php echo $form->textField($model, 'lat', array('size' => 10, 'maxlength' => 50, 'readonly' => 'readonly', 'class' => 'validate[required]')); ?>
        </td>
    </tr>
</tbody>
<tr class="submit">
    <td colspan="2" >
        <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" /></td>
</tr>
</table>
<script type="text/javascript">
    $(function() {
        $("#xform").validationEngine();
        $("#get_lng_lat").click(function() {
            var location = $.trim($('#Province_name').val());
            if (location.length == 0) {
                alert('请输入省份名称！');
                return false;
            } else {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('map/getCodeAddress'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        location: location
                    },
                    success: function(data) {
                        if (data.IsError) {
                            alert(data.Message);
                            return false;
                        } else {
                            $('#Province_lnt').val(data.lng);
                            $('#Province_lat').val(data.lat);
                        }
                    }
                });
            }
        });
    });
</script>
<?php $form = $this->endWidget(); ?>