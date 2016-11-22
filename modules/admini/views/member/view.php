<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>会员信息</h3>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="content_list">
    <thead>
      <tr class="tb_header">
          <th width="8%">会员ID</th>
          <th width="10%">会员名称</th>
          <th width="10%">手机号</th>
          <th width="10%">昵称</th>
          <th width="8%">称谓</th>
          <th width="8%">城市</th>
          <th width="10%">通知类型</th>
          <th width="12%">意向城市</th>
          <th width="12%">用途</th>
          <th width="12%">补充说明</th>
      </tr>
    </thead>
    <tr class="tb_list">
        <td><?php echo $model->userId; ?></td>
        <td><?php echo $model->username; ?></td>
        <td><?php echo $model->phone; ?></td>
        <td><?php echo $model->nickname; ?></td>
        <td><?php echo $model->gender; ?></td>
        <td><?php echo $model->city; ?></td>
        <td><?php echo $model->inform_type; ?></td>
        <td><?php echo $model->aim_city; ?></td>
        <td><?php echo $model->purpose; ?></td>
        <td><?php echo $model->instruction; ?></td>
    </tr>
</table>

<?php $this->renderPartial('/_include/footer');?>