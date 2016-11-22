<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>管理员日志</h3>
  <div class="searchArea">
    <div class="search right">
      <?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('index'),'htmlOptions'=>array('name'=>'xform'))); ?>
      <select name="catalog" id="catalog">
        <option value="">==操作类型==</option>
        <?php foreach(XParams::$adminiLoggerType as $key=>$val):?>
        <option value="<?php echo $key?>"><?php echo $val?></option>
        <?php endforeach?>
      </select>&nbsp;&nbsp;
      <select name="adminId" id="adminId">
        <option value="">==所有管理员==</option>
        <?php foreach(Admin::model()->findAll() as $obj):?>
          <option value="<?php echo $obj->id; ?>"><?php echo $obj->username; ?></option>
        <?php endforeach?>
      </select>
      <input name="searchsubmit" type="submit" value="查询" class="button"/>
      <script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
      <?php $form=$this->endWidget(); ?>
      <script type="text/javascript">
$(document).ready(function(){
	$("#catalog").val('<?php echo $this->_gets->getParam('catalog')?>');
	$("#adminId").val('<?php echo $this->_gets->getParam('adminId')?>');
});
</script> 
    </div>
  </div>
</div>
<table class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <tr class="tb_header">
      <th width="10%">ID</th>
      <th width="5%">类型</th>
      <th width="5%">用户</th>
      <th width="60%">动作</th>
      <th width="5%">IP</th>
      <th width="10%">操作时间</th>
      <th width="5%">操作</th>
    </tr>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
      <td ><input type="checkbox" name="id[]" value="<?php echo $row->id?>">
        <?php echo $row->id?></td>
      <td ><?php echo XParams::get($row->catalog,'adminiLoggerType')?></td>
      <td ><?php echo CHtml::encode($row->user->username)?></td>
      <td ><?php echo CHtml::encode($row->intro)?><br />
        <span style="color:#999"><?php echo CHtml::encode($row->url)?></span></td>
      <td ><span ><?php echo $row->ip?></span></td>
      <td ><?php echo date('Y-m-d H:i',$row->create_time)?></td>
      <td ><a href="<?php echo  $this->createUrl('batch',array('command'=>'adminLoggerDelete', 'id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a></td>
    </tr>
    <?php endforeach;?>
    <tr class="submit">
      <td colspan="7"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel" >
          <input type="checkbox" name="chkall" id="chkall" onclick="checkAll(this.form, 'id')" />
          <label for="chkall">全选</label>
          <select name="command">
            <option value="">选择操作</option>
            <option value="adminLoggerDelete">删除</option>
          </select>
          <input id="submit_maskall" class="button confirmSubmit" type="submit" value="提交" name="maskall" />
        </div></td>
    </tr>
  </form>
</table>
<?php $this->renderPartial('/_include/footer');?>
