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
            <td ><?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255, 'class'=>'validate[required]')); ?></td>
        </tr>
        <tr>
            <td class="tb_title">封面图片：</td>
        </tr>
        <tr >
            <td colspan="2" ><input name="image" type="file" id="image" />
            <?php if($model->image):?>
                <a href="<?php echo $this->_baseUrl.'/'. $model->image?>" target="_blank"><img src="<?php echo $this->_baseUrl.'/'. $model->image?>" width="50" align="absmiddle" /></a>
            <?php endif?></td>
        </tr>
        <tr>
            <td class="tb_title">简介：</td>
        </tr>
        <tr >
            <td><?php echo $form->textArea($model,'summary',array('rows'=>5, 'cols'=>90, 'class'=>'validate[required]')); ?></td>
        </tr>
        <tr>
            <td class="tb_title">详细内容：</td>
        </tr>
        <tr >
            <td ><?php echo $form->textArea($model,'content', array('class'=>'validate[required]')); ?>
                <?php $this->widget('application.widget.kindeditor.KindEditor',array(
                    'target'=>array('#Service_content'=>array('uploadJson'=>$this->createUrl('upload'),'extraFileUploadParams'=>array(array('sessionId'=>session_id()))))));?>
            </td>
        </tr>
        <tr class="submit">
            <td colspan="2" >
                <input name="oAttach" type="hidden" value="<?php echo $model->image; ?>" />
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