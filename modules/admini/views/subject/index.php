<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>项目管理</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="<?php echo $this->createUrl('create')?>" class="actionBtn"><span>录入</span></a></li>
    </ul>
  </div>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
      <tr class="tb_header">
          <th width="10%">ID</th>
          <th width="25%">项目名称</th>
          <th width="15%">城市</th>
          <th width="20%">项目时间</th>
          <th width="10%">热点推荐</th>
          <th width="20%">操作</th>
      </tr>
    </thead>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td><input type="checkbox" name="id[]" value="<?php echo $row->id?>">
        <?php echo $row->id; ?></td>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $row->city->name; ?></td>
        <td><?php echo date('Y年m月d日', $row->date); ?></td>
        <td>
            <?php if($row->recommend == 1){ ?>
                <span style="color: #18C463; font-size: 16px; font-weight: bold;">&radic;</span>
            <?php }else{ ?>
                <span style="color: #ff1721; font-size: 16px; font-weight: bold;">&times;</span>
            <?php } ?>
        </td>
        <td>
            <a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>&nbsp;&nbsp;
            <a href="javascript:void(0);" class="recommend" data-id="<?php echo $row->id; ?>"><?php echo $row->recommend == 1 ? '取消推荐' : '热点推荐'; ?></a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr class="operate">
      <td colspan="6"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel">
          <input type="checkbox" name="chkall" id="chkall" onClick="checkAll(this.form, 'id')" />
          <label for="chkall">全选</label>
          <select name="command">
            <option>选择操作</option>
            <option value="delete">删除</option>
          </select>
          <input id="submit_maskall" class="button confirmSubmit" type="submit" value="提交" name="maskall" />
        </div></td>
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
    });
</script>
