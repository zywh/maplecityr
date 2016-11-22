<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>资料上传</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="<?php echo $this->createUrl('create')?>" class="actionBtn"><span>上传</span></a></li>
    </ul>
  </div>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
    <tr class="tb_header">
      <th width="10%">ID</th>
      <th width="30%">文件名称</th>
      <th width="20%">文件图片</th>
      <th width="20%">上传时间</th>
      <th width="10%">推荐下载</th>
      <th width="10%">操作</th>
    </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
      <?php foreach ($datalist as $row):?>
        <tr class="tb_list">
            <td><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"><?php echo $row->id;?></td>
            <td><span class="alias"><a href="<?php echo Yii::app()->createUrl('admini/file/download',array('id'=>$row->id)); ?>"><?php echo $row->name; ?></a></span></td>
            <td><?php if(!empty($row->image)){ ?><img src="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo $row->image; ?>" alt="" width="120" height="80"/><?php }else{ ?><span style="color: #ff1721; font-size: 16px; font-weight: bold;">无图片</span><?php } ?></td>
            <td><span class="alias"><?php echo date('Y-m-d H:i:s', $row->upload_time); ?></span></td>
            <td>
            <?php if($row->recommend == 1){ ?>
                <span style="color: #18C463; font-size: 16px; font-weight: bold;">&radic;</span>
            <?php }else{ ?>
                <span style="color: #ff1721; font-size: 16px; font-weight: bold;">&times;</span>
            <?php } ?>
            </td>
            <td>
              <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>
              <?php if(!empty($row->image)){ ?>&nbsp;&nbsp;<a href="javascript:;" class="recommend" data-id="<?php echo $row->id; ?>"><?php echo $row->recommend == 1 ? '取消推荐' : '推荐下载'; ?></a><?php } ?>
            </td>
        </tr>
      <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
      <td colspan="5"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel">
          <input type="checkbox" name="chkall" id="chkall" onClick="checkAll(this.form, 'id');" />
          <label for="chkall">全选</label>
          <select name="command">
            <option>选择操作</option>
            <option value="delete">批量删除</option>
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
    });
</script>