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
        <td class="tb_title">房源名称：</td>
    </tr>
    <tr >
        <?php if($model->isNewRecord){ ?>
            <td>
                <?php echo $form->hiddenField($model,'house_id',array('value'=>$house_id)); ?>
                <p><?php echo House::model()->findByPk($house_id)->name; ?></p>
            </td>
        <?php }else{ ?>
            <td>
                <?php echo $form->hiddenField($model,'house_id',array('value'=>$model->house_id)); ?>
                <p><?php echo $model->house->name; ?></p>
            </td>
        <?php } ?>
    </tr>
    <tr>
        <td class="tb_title">楼层：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model,'floor',array('size'=>10,'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">房间：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model,'room',array('size'=>10,'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">长(m)：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model,'length',array('size'=>10,'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">宽(m)：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model,'width',array('size'=>10,'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">面积(m&sup2;)：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model,'area',array('size'=>10,'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">描述：</td>
    </tr>
    <tr >
        <td><?php echo $form->textArea($model,'describe',array('rows'=>3,'cols'=>60, 'class'=>'validate[required]')); ?></td>
    </tr>
    </tbody>
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