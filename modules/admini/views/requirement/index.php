<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>客户房源需求</h3>
    <div class="searchArea">
        <div class="search right">
            <?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('index'),'htmlOptions'=>array('name'=>'xform', 'class'=>'right '))); ?>
            <input type="checkbox" name="is_handle" value="0"/>未处理&nbsp;&nbsp;
            <input type="checkbox" name="no_handle" value="1"/>已处理&nbsp;&nbsp;
            <input name="searchsubmit" type="submit"  value="查询" class="button "/>
            <?php $form=$this->endWidget(); ?>
        </div>
    </div>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
    <thead>
      <tr class="tb_header" style="line-height: 20px;">
          <th width="5%">ID</th>
          <th width="8%">城市</th>
          <th width="8%">地区</th>
          <th width="8%">投资类型</th>
          <th width="8%">物业类型</th>
          <th width="8%">总&nbsp;&nbsp;价<br/>(加币)</th>
          <th width="8%">房屋面积<br/>(平方英尺)</th>
          <th width="8%">土地面积<br/>(平方英尺)</th>
          <th width="5%">卧室数量</th>
          <th width="8%">建造年份</th>
          <th width="8%">手&nbsp;&nbsp;机</th>
          <th width="8%">邮&nbsp;&nbsp;箱</th>
          <th width="5%">是否处理</th>
          <th width="5%">操作</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td><?php echo $row->id;?></td>
        <td><span class="alias"><?php echo empty($row->city_id) ? '不限' : $row->city->name; ?></span></td>
        <td><span class="alias"><?php echo empty($row->district_id) ? '不限' : $row->district->name; ?></span></td>
        <td><span class="alias"><?php echo empty($row->investType_id) ? '不限' : $row->investType->name; ?></span></td>
        <td><span class="alias"><?php echo empty($row->propertyType_id) ? '不限' : $row->propertyType->name; ?></span></td>
        <td><span class="alias"><?php echo empty($row->total_price) ? '不限' : $row->total_price; ?></span></td>
        <td><span class="alias"><?php echo empty($row->house_area) ? '不限' : $row->house_area; ?></span></td>
        <td><span class="alias"><?php echo empty($row->land_area) ? '不限' : $row->land_area; ?></span></td>
        <td><span class="alias"><?php echo empty($row->bedroom_num) ? '不限' : $row->bedroom_num; ?></span></td>
        <td><span class="alias"><?php echo empty($row->construction_year) ? '不限' : $row->construction_year; ?></span></td>
        <td><span class="alias"><?php echo $row->phone; ?></span></td>
        <td><span class="alias"><?php echo $row->email; ?></span></td>
        <td style="text-align: center">
            <span class="alias">
                <?php if($row->status == 1){ ?>
                    <span style="color: #18C463; font-size: 16px; font-weight: bold;">&radic;</span>
                <?php }else{ ?>
                    <span style="color: #ff1721; font-size: 16px; font-weight: bold;">&times;</span>
                <?php } ?>
            </span>
        </td>
        <td><?php if($row->status == 1){ ?>已处理<?php }else{ ?><a href="javascript:void(0);" class="handle" data-id="<?php echo $row->id; ?>">处理</a><?php } ?></td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
        <td colspan="14">
            <div class="cuspages right">
                <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
            </div>
        </td>
    </tr>
</table>
<?php $this->renderPartial('/_include/footer');?>
<script type="text/javascript">
    $(function(){
        $('.handle').click(function(){
            var self = $(this);
            var id = self.attr('data-id');
            if(confirm('发送邮件或致电客户后方可确认已处理。\n您确认该需求已处理吗？')){
                $.post('<?php echo $this->createUrl('requirementHandle'); ?>',{id: id},function(json){
                    if(json.success){
                        window.location = location;
                    }else{
                        alert(json.msg);
                    }
                },'json');
            }else{
                return false;
            }
        });
    });
</script>