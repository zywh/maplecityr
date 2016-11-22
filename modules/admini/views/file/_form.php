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
            <td class="tb_title">文件：</td>
        </tr>
        <tr >
            <td colspan="2"><input name="attach" type="file" id="attach" /></td>
        </tr>
        <tr>
            <td class="tb_title">文件图片：(<span style="color: #ff0000;">只有上传图片的文件才能被推荐下载</span>)</td>
        </tr>
        <tr >
            <td colspan="2"><input name="image" type="file" id="image" /></td>
        </tr>
        <tr class="submit">
            <td colspan="2">
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