<style type="text/css">
  .ui-datepicker .ui-datepicker-prev, .ui-datepicker .ui-datepicker-next {
    top: 6px;
  }
  .ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year {
    width: 45%;
  }
</style>
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
    <td class="tb_title" >项目名称：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'validate[required]')); ?></td>
  </tr>
    <tr>
        <td class="tb_title">城市：</td>
    </tr>
    <tr >
        <td >
            <select name="Subject[city_id]" id="Subject_city_id" class="validate[required]">
                <option value>==所有城市==</option>
                <?php foreach ((array) $this->city_list as $city): ?>
                    <option value="<?php echo $city['id'] ?>" <?php XUtils::selected($city['id'], $model->city_id); ?>><?php echo $city['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
  <tr>
    <td class="tb_title">项目时间：</td>
  </tr>
  <tr>
    <td>
      <?php
      $this->widget('zii.widgets.jui.CJuiDatePicker', array(
          'attribute' => 'date',
          'model' => $model,
          'language' => 'zh',
          'options' => array(
              'showAnim' => 'fold',
              'dateFormat' => 'yy-mm-dd',
              'changeMonth' => 'true',
              'changeYear' => 'true',
              'autoSize' => 'true',
              'showMonthAfterYear' => 'true',
          ),
          'htmlOptions' => array(
              'class'=>'validate[required]'
          ),
      ));
      ?>
    </td>
  </tr>
  <tr>
    <td class="tb_title">项目概况：</td>
  </tr>
  <tr >
    <td><?php echo $form->textArea($model,'summary',array('rows'=>5, 'cols'=>90, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">项目重点：(<span style="color: #ff0000;">每条重点之间用"|"分割</span>)</td>
  </tr>
  <tr >
    <td><?php echo $form->textArea($model,'point',array('rows'=>5, 'cols'=>90, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">项目组图：(<span style="color: #ff0000;">最好上传，否则页面无图片显示。如果插件无法运行，请检查flash插件是否安装，或使用火狐浏览器。</span>)</td>
  </tr>
  <tr>
    <td>
      <div>
        <p><a href="javascript:uploadifyAction('fileListWarp')" ><img src="<?php echo $this->_baseUrl?>/static/admin/images/create.gif" align="absmiddle">添加图片</a></p>
        <ul id="fileListWarp">
          <?php foreach((array)$imageList as $key=>$row):?>
            <?php if($row):?>
              <li id="image_<?php echo $row['fileId']?>"><a href="<?php echo $this->_baseUrl?>/<?php echo $row['file']?>" target="_blank"><img src="<?php echo $this->_baseUrl?>/<?php echo $row['file']?>" width="40" height="40" align="absmiddle"></a>&nbsp;<br>
                <a href='javascript:uploadifyRemove("<?php echo $row['fileId']?>", "image_")'>删除</a>
                <input name="imageList[fileId][]" type="hidden" value="<?php echo $row['fileId']?>">
                <input name="imageList[file][]" type="hidden" value="<?php echo $row['file']?>">
              </li>
            <?php endif?>
          <?php endforeach?>
        </ul>
      </div>
    </td>
  </tr>
  <tr>
    <td class="tb_title">开发商介绍：</td>
  </tr>
  <tr >
    <td><?php echo $form->textArea($model,'developer_intro',array('rows'=>5, 'cols'=>90, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">房型图：(<span style="color: #ff0000;">一张大图，包含所有房间设计图</span>)</td>
  </tr>
  <tr >
    <td colspan="2" ><input name="attach" type="file" id="attach" />
      <?php if($model->room_type_image):?>
        <a href="<?php echo $this->_baseUrl.'/'. $model->room_type_image?>" target="_blank"><img src="<?php echo $this->_baseUrl.'/'. $model->room_type_image?>" width="50" align="absmiddle" /></a>
      <?php endif?></td>
  </tr>
  <tr class="submit">
    <td colspan="2" ><input name="oAttach" type="hidden" value="<?php echo $model->room_type_image; ?>" />
      <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" /></td>
  </tr>
</table>
<?php $form=$this->endWidget(); ?>
<script type="text/javascript">
  $(function(){
    $("#xform").validationEngine();
    $("#Subject_date").val('<?php echo $model->date ? date('Y-m-d', $model->date) : ''; ?>');
  });
</script>