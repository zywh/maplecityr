<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>房源管理</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="<?php echo $this->createUrl('create', array('original'=>Yii::app()->request->getUrl()))?>" class="actionBtn"><span>录入</span></a></li>
    </ul>
    <div class="search right">
        <?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('index'),'htmlOptions'=>array('name'=>'xform', 'class'=>'right '))); ?>

        省份
        <select name="city_id" id="city_id">
            <option value="">=所有省份=</option>
            <?php foreach((array)$this->city_list as $city):?>
                <option value="<?php echo $city['id']?>" <?php XUtils::selected($city['id'], $model->city_id);?>><?php echo $city['name']?></option>
            <?php endforeach;?>
        </select>&nbsp;&nbsp;
        地区
        <select name="district_id" id="district_id">
            <option value="">=所有地区=</option>
            <?php foreach((array)$this->district_list as $district):?>
                <option value="<?php echo $district['id']?>" <?php XUtils::selected($district['id'], $model->district_id);?>><?php echo $district['name']?></option>
            <?php endforeach;?>
        </select>&nbsp;&nbsp;
<!--        投资类型
        <select name="investType_id" id="investType_id">
            <option value="">=所有类型=</option>
            <?php foreach((array)$this->investType_list as $investType):?>
                <option value="<?php echo $investType['id']?>" <?php XUtils::selected($investType['id'], $model->investType_id);?>><?php echo $investType['name']?></option>
            <?php endforeach;?>
        </select>&nbsp;&nbsp; -->
        物业类型
        <select name="propertyType_id" id="propertyType_id">
            <option value="">=所有类型=</option>
            <?php foreach((array)$this->propertyType_list as $propertyType):?>
                <option value="<?php echo $propertyType['id']?>" <?php XUtils::selected($propertyType['id'], $model->propertyType_id);?>><?php echo $propertyType['name']?></option>
            <?php endforeach;?>
        </select>&nbsp;&nbsp;
        房源编号/地址
        <input id="h_name" type="text" name="h_name" value="" class="txt" size="50"/>&nbsp;&nbsp;
        <input type="checkbox" id="is_recommend" name="is_recommend" value="1"/>是否推荐&nbsp;&nbsp;
        <input name="searchsubmit" type="submit"  value="查询" class="button "/>
    <script type="text/javascript">
    $(function(){
        $("#xform").validationEngine();
    });
    </script>
      <?php $form=$this->endWidget(); ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#city_id").val('<?php echo Yii::app()->request->getParam('city_id'); ?>');
        $("#district_id").val('<?php echo Yii::app()->request->getParam('district_id'); ?>');
        $("#investType_id").val('<?php echo Yii::app()->request->getParam('investType_id'); ?>');
        $("#propertyType_id").val('<?php echo Yii::app()->request->getParam('propertyType_id'); ?>');
        $("#h_name").val('<?php echo Yii::app()->request->getParam('h_name'); ?>');
        var is_recommend = <?php echo Yii::app()->request->getParam('is_recommend') ? Yii::app()->request->getParam('is_recommend') : 0; ?>;
        if(is_recommend == 1) $("#is_recommend").attr('checked', true);
    });
</script>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
      <tr class="tb_header">
          <th width="8%">ID</th>
          <th width="11%">房源编号</th>
        <th width="14%">地址</th>
        <th width="9%">地区</th>
        <th width="11%">挂牌价格</th>
        <th width="11%">物业类型</th>
        <th width="6%">是否推荐</th>
          <th width="6%">是否出售</th>
          <th width="16%">操作</th>
          <th width="8%">房屋布局</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"><?php echo $row->id;?></td>
        <td>
          <span class="alias"><?php echo $row->ml_num; ?></span>
        </td>
        <td><span><?php echo $row->addr; ?></span></td>
        <td><span><?php echo $row->area; ?></span></td>
        <td><span><?php echo $row->lp_dol/10000; ?>万</span></td>
        <td><span><?php echo $row->type_own1_out; ?></span></td>
        <td>
            <?php if($row->recommend == 1){ ?>
                <span style="color: #18C463; font-size: 16px; font-weight: bold;">&radic;</span>
            <?php }else{ ?>
                <span style="color: #ff1721; font-size: 16px; font-weight: bold;">&times;</span>
            <?php } ?>
        </td>
        <td>
            <?php if($row->is_sell == 1){ ?>
                <span style="color: #18C463; font-size: 16px; font-weight: bold;">&radic;</span>
            <?php }else{ ?>
                <span style="color: #ff1721; font-size: 16px; font-weight: bold;">&times;</span>
            <?php } ?>
        </td>
        <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id, 'original'=>Yii::app()->request->getUrl()))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="javascript:void(0);" class="recommend" data-id="<?php echo $row->id; ?>"><?php echo $row->recommend == 1 ? '取消推荐' : '推荐'; ?></a>&nbsp;&nbsp;|&nbsp;
            <a href="javascript:void(0);" class="sell" data-id="<?php echo $row->id; ?>"><?php echo $row->is_sell == 1 ? '停售' : '出售'; ?></a>
        </td>
        <td><a href="<?php echo Yii::app()->createUrl('admini/layout/create',array('id'=>$row->id)); ?>">添加</a>&nbsp;|&nbsp;<a href="<?php echo Yii::app()->createUrl('admini/layout/index',array('house_id'=>$row->id)); ?>">查看布局</a></td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
      <td colspan="10"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel">
          <input type="checkbox" name="chkall" id="chkall" onClick="checkAll(this.form, 'id');" />
          <label for="chkall">全选</label>
          <select name="command">
            <option>选择操作</option>
            <option value="delete">批量删除</option>
            <option value="commend">批量推荐</option>
            <option value="unCommend">取消推荐</option>
          </select>
          <input id="submit_maskall" class="button confirmSubmit" type="submit" value="提交" name="maskall" />
        </div>
      </td>
    </tr>
  </form>
</table>
<?php $this->renderPartial('/_include/footer');?>
<script type="text/javascript">
    $(function(){
        $('.recommend').click(function(){
            var self = $(this);
            var id = self.attr('data-id');
            $.post('<?php echo $this->createUrl('recommend'); ?>',{id:id},function(json){
                if(json.status == 'success'){
                    window.location.reload();
                }else if(json.status == 'failed'){
                    alert('操作失败，请联系管理员');
                    return false;
                }else if(json.status == 'forbid'){
                    alert('当前角色组无权限进行此操作，请联系管理员授权');
                    return false;
                }
            },'json');
        });

        $('.sell').click(function(){
            var self = $(this);
            var id = self.attr('data-id');
            $.post('<?php echo $this->createUrl('sell'); ?>',{id:id},function(json){
                if(json.status == 'success'){
                    window.location.reload();
                }else if(json.status == 'failed'){
                    alert('操作失败，请联系管理员');
                    return false;
                }else if(json.status == 'forbid'){
                    alert('当前角色组无权限进行此操作，请联系管理员授权');
                    return false;
                }
            },'json');
        });

        $('#city_id').change(function() {
            var city_id = $(this).val();
            $.post("<?php echo $this->createUrl('ajax/getDistrict') ?>", {'city_id': city_id}, function(html) {
                $('#district_id').html(html);
            }, 'html');
        });
    });
</script>
