<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>会员管理</h3>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <thead>
      <tr class="tb_header">
          <th width="8%">ID</th>
          <th width="10%">会员名称</th>
          <th width="15%">邮箱</th>
          <th width="12%">手机号</th>
          <th width="20%">注册时间</th>
          <th width="10%">登录次数</th>
          <th width="15%">上次登录时间</th>
          <th width="10%">操作</th>
      </tr>
    </thead>
    <?php if(is_array($datalist) && !empty($datalist)):?>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
        <td ><input type="checkbox" name="id[]" value="<?php echo $row->id; ?>"><?php echo $row->id;?></td>
        <td ><span class="alias"><?php echo $row->username; ?></span></td>
        <td ><span class="alias"><?php echo $row->email; ?></span></td>
        <td ><span class="alias"><?php echo $row->phone; ?></span></td>
        <td ><span class="alias"><?php echo date('Y-m-d H:i:s', $row->create_time); ?></span></td>
        <td ><span class="alias"><?php echo $row->login_count; ?></span></td>
        <td ><span class="alias"><?php echo date('Y-m-d H:i:s', $row->last_login_time); ?></span></td>
        <td>
        <a href="<?php echo $this->createUrl('member/userInfo', array('userid'=>$row->id)); ?>">查看信息</a>&nbsp;|&nbsp;
        <a href="<?php echo  $this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a>
        </td>
    </tr>
    <?php endforeach;?>
    <?php endif?>
    <tr class="operate">
      <td colspan="8"><div class="cuspages right">
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
