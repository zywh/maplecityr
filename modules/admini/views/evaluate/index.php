<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
    <h3>客户投资评估</h3>
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
    <tr class="tb_header">
        <th width="10%">ID</th>
        <th width="12%">意向城市</th>
        <th width="12%">投资类型</th>
        <th width="12%">投资目的</th>
        <th width="12%">首付预算(加币)</th>
        <th width="12%">称&nbsp;&nbsp;谓</th>
        <th width="12%">回拨电话</th>
        <th width="8%">是否处理</th>
        <th width="10%">操作</th>
    </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
        <?php foreach ($datalist as $row):?>
            <tr class="tb_list">
                <td><?php echo $row->id;?></td>
                <td><span class="alias"><?php echo empty($row->city) ? '不限' : $row->city; ?></span></td>
                <td><span class="alias"><?php echo empty($row->type) ? '不限' : $row->type; ?></span></td>
                <td><span class="alias"><?php echo empty($row->aim) ? '不限' : $row->aim; ?></span></td>
                <td><span class="alias"><?php echo empty($row->prepay) ? '不限' : $row->prepay; ?></span></td>
                <td><span class="alias"><?php echo $row->name; ?></span></td>
                <td><span class="alias"><?php echo $row->phone; ?></span></td>
                <td>
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
        <td colspan="9">
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
            if(confirm('致电客户后方可确认已处理。\n您确认该评估已处理吗？')){
                $.post('<?php echo $this->createUrl('evaluateHandle'); ?>',{id: id},function(json){
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