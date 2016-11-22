<style type="text/css">
    .ui-datepicker .ui-datepicker-prev, .ui-datepicker .ui-datepicker-next {
        top: 6px;
    }
    .ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year {
        width: 45%;
    }
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
<?php $form = $this->beginWidget('CActiveForm', array('id' => 'xform', 'htmlOptions' => array('name' => 'xform', 'enctype' => 'multipart/form-data'))); ?>
<table class="form_table">
    <tr>
        <td class="tb_title" >房源名称：</td>
    </tr>
    <tr>
        <td ><?php echo $form->textField($model, 'name', array('size' => 50, 'maxlength' => 255, 'class' => 'validate[required]')); ?>
    </tr>
    <tr>
        <td class="tb_title">首付：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'prepay', array('size' => 10, 'class' => 'validate[required]')); ?>&nbsp;万加币起</td>
    </tr>
    <tr>
        <td class="tb_title">总价：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'total_price', array('size' => 10, 'class' => 'validate[required]')); ?>&nbsp;万加币起</td>
    </tr>
    <tr>
        <td class="tb_title">开发商：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'developer', array('size' => 50, 'maxlength' => 255)); ?></td>
    </tr>
    <tr>
        <td class="tb_title">所属项目：</td>
    </tr>
    <tr >
        <td >
            <select name="House[subject_id]" id="House_subject_id" class="validate[required]">
                <option value>==项 目==</option>
                <?php foreach ((array) $this->subject_list as $subject): ?>
                    <option value="<?php echo $subject['id'] ?>" <?php XUtils::selected($subject['id'], $model->subject_id); ?>><?php echo $subject['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tb_title">挂牌日期：</td>
    </tr>
    <tr>
        <td>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'accessDate',
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
					'class' => 'validate[required]'
				),
            ));
            ?>
        </td>
    </tr>
    <tr>
        <td class="tb_title">城市：</td>
    </tr>
    <tr >
        <td >
            <select name="House[city_id]" id="House_city_id" class='validate[required]'>
                <option value>==城市==</option>
                <?php foreach ((array) $this->city_list as $city): ?>
                    <option value="<?php echo $city['id'] ?>" <?php XUtils::selected($city['id'], $model->city_id); ?>><?php echo $city['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tb_title">地区：</td>
    </tr>
    <tr >
        <td >
            <select name="House[district_id]" id="House_district_id" class='validate[required]'>
                <?php foreach ((array) $this->district_list as $district): ?>
                    <option value="<?php echo $district['id'] ?>" <?php XUtils::selected($district['id'], $model->district_id); ?>><?php echo $district['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tb_title">社区/学区(<span style="color: #ff0000;">如果是学区房</span>)：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'community', array('size' => 30, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">投资类型：</td>
    </tr>
    <tr >
        <td >
            <select name="House[investType_id]" id="House_investType_id" class="validate[required]">
                <option value>==投资类型==</option>
                <?php foreach ((array) $this->investType_list as $investType): ?>
                    <option value="<?php echo $investType['id'] ?>" <?php XUtils::selected($investType['id'], $model->investType_id); ?>><?php echo $investType['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tb_title">物业类型：</td>
    </tr>
    <tr >
        <td >
            <select name="House[propertyType_id]" id="House_propertyType_id" class="validate[required]">
                <option value>==物业类型==</option>
                <?php foreach ((array) $this->propertyType_list as $propertyType): ?>
                    <option value="<?php echo $propertyType['id'] ?>" <?php XUtils::selected($propertyType['id'], $model->propertyType_id); ?>><?php echo $propertyType['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="tb_title">地址：</td>
    </tr>
    <tr>
        <td><?php echo $form->textField($model, 'location', array('size' => 50, 'maxlength' => 255, 'class' => 'validate[required]')); ?></td>
    </tr>

    <tr>
        <td class="tb_title">房源描述：</td>
    </tr>
    <tr>
        <td ><?php echo $form->textArea($model, 'introduction', array('class' => 'validate[required]')); ?>
            <?php
            $this->widget('application.widget.kindeditor.KindEditor', array(
                'target' => array(
                    '#House_introduction' => array('uploadJson' => $this->createUrl('upload'), 'extraFileUploadParams' => array(array('sessionId' => session_id()))))));
            ?>
        </td>
    </tr>
    <tr>
        <td class="tb_title">房源图片：(<span style="color: #ff0000;">最好上传，否则列表面无图片显示</span>)</td>
    </tr>
    <tr >
        <td colspan="2" ><input name="house_image" type="file" id="house_image" />
            <?php if ($model->house_image): ?>
                <a href="<?php echo $this->_baseUrl . '/' . $model->house_image ?>" target="_blank"><img src="<?php echo $this->_baseUrl . '/' . $model->house_image ?>" width="50" align="absmiddle" /></a>
            <?php endif ?>
        </td>
    </tr>

    <!--新增开始-->
    <tr>
        <td class="tb_title">土地面积：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'land_area', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?>&nbsp;m&sup2;</td>
    </tr>
    <tr>
        <td class="tb_title">房屋面积：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'house_area', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?>&nbsp;m&sup2;</td>
    </tr>
    <tr>
        <td class="tb_title">房屋层数：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'floor_num', array('size' => 10, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">卧室数量：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'bedroom_num', array('size' => 10, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">卫生间数量：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'toilet_num', array('size' => 10, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">厨房数量：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'kitchen_num', array('size' => 10, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">停车位数量：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'park_num', array('size' => 10, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">房屋规格(英尺)：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'house_size', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">土地所有权：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'land_ownership', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">大门朝向：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'door_direction', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">建造年份：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'construction_year', array('size' => 10, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">邮编：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'zipcode', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">认证房源：</td>
    </tr>
    <tr >
        <td><?php echo $form->radioButtonList($model, 'certificate', array('1' => '是', '0' => '否'), array('class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">电梯：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'lift', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">车库：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'carport', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">会客厅：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'embassy', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">MLS编号：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'mls_code', array('size' => 10, 'maxlength' => 50, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">附近设施：</td>
    </tr>
    <tr >
        <td><?php echo $form->textArea($model, 'facilities', array('rows' => 4, 'cols' => 60, 'class' => 'validate[required]')); ?></td>
    </tr>
    <tr>
        <td class="tb_title">经纬度(<span style="color: #FF0000;">根据所填地址获取</span>)：</td>
    </tr>
    <tr >
        <td>
            <a href="javascript:void(0);" id="get_lng_lat">获取经纬度</a>
            经度：<?php echo $form->textField($model, 'longitude', array('size' => 10, 'maxlength' => 50, 'readonly' => 'readonly', 'class' => 'validate[required]')); ?>
            纬度：<?php echo $form->textField($model, 'latitude', array('size' => 10, 'maxlength' => 50, 'readonly' => 'readonly', 'class' => 'validate[required]')); ?>
        </td>
    </tr>

    <tr>
        <td class="tb_title">房源组图：(<span style="color: #ff0000;">最好上传，否则页面无图片显示。如果插件无法运行，请检查flash插件是否安装，或使用火狐浏览器。</span>)</td>
    </tr>
    <tr>
        <td>
            <div>
                <p><a href="javascript:uploadifyAction('fileListWarp')" ><img src="<?php echo $this->_baseUrl ?>/static/admin/images/create.gif" align="absmiddle">添加图片</a></p>
                <ul id="fileListWarp">
                    <?php foreach ((array) $imageList as $key => $row): ?>
                        <?php if ($row): ?>
                            <li id="image_<?php echo $row['fileId'] ?>"><a href="<?php echo $this->_baseUrl ?>/<?php echo $row['file'] ?>" target="_blank"><img src="<?php echo $this->_baseUrl ?>/<?php echo $row['file'] ?>" width="40" height="40" align="absmiddle"></a>&nbsp;<br>
                                <a href='javascript:uploadifyRemove("<?php echo $row['fileId'] ?>", "image_")'>删除</a>
                                <input name="imageList[fileId][]" type="hidden" value="<?php echo $row['fileId'] ?>">
                                <input name="imageList[file][]" type="hidden" value="<?php echo $row['file'] ?>">
                            </li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            </div>
        </td>
    </tr>

    <tr>
        <td class="tb_title">房源视频路径(完整路径)：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'video_url', array('size' => 50, 'maxlength' => 255)); ?></td>
    </tr>

    <tr>
        <td class="tb_title">房屋配套：</td>
    </tr>
    <tr>
        <td>
            <input id="ytHouse_match" type="hidden" value="" name="House[match]" class="validate[required]">
            <span id="House_match">
                <?php
                $matches = explode(',', $model->match);
                foreach ((array) $this->match_list as $k => $match) {
                    ?>
                    <?php if (in_array($match['id'], $matches)) { ?>
                        <input id="House_match_<?php echo $k; ?>" value="<?php echo $match['id']; ?>" type="checkbox" name="House[match][]" checked="checked">&nbsp;<label for="House_match_0"><?php echo $match['name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php } else { ?>
                        <input id="House_match_<?php echo $k; ?>" value="<?php echo $match['id']; ?>" type="checkbox" name="House[match][]">&nbsp;<label for="House_match_0"><?php echo $match['name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php } ?>
                    <?php if (($k + 1) % 4 == 0) { ?><br/><?php } ?>
                <?php } ?>
            </span>
        </td>
    </tr>
    <tr>
        <td class="tb_title">编辑者：</td>
    </tr>
    <tr >
        <td><?php echo $form->textField($model, 'author', array('size' => 20, 'maxlength' => 255, 'class' => 'validate[required]')); ?></td>
    </tr>
</tbody>
<tr class="submit">
    <td colspan="2" >
        <input name="oAttach" type="hidden" value="<?php echo $model->house_image; ?>" />
        <input type="submit" name="editsubmit" value="提交" class="button" tabindex="3" />
    </td>
</tr>
</table>
<script type="text/javascript">
    $(function() {
        $("#xform").validationEngine();
        $("#House_accessDate").val('<?php echo $model->accessDate ? date('Y-m-d', $model->accessDate) : ''; ?>');
        $('#House_city_id').change(function() {
            var city_id = $(this).val();
            $.post("<?php echo $this->createUrl('ajax/getDistrict') ?>", {'city_id': city_id}, function(html) {
                $('#House_district_id').html(html);
            }, 'html');
        });

        $("#get_lng_lat").click(function(){
            var location = $.trim($('#House_location').val());
            if (location.length == 0) {
                alert('请输入房源地址！');
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
                            $('#House_longitude').val(data.lng);
                            $('#House_latitude').val(data.lat);
                        }
                    }
                });
            }
        });
    });
</script>
<?php $form = $this->endWidget(); ?>