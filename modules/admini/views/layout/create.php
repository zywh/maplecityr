<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>房屋布局管理</h3>
  <div class="searchArea">
    <ul class="action left" >
      <li class="current"><a href="<?php echo $this->createUrl('index')?>" class="actionBtn"><span>返回</span></a></li>
    </ul>
  </div>
</div>

<?php $this->renderPartial('_create_form',array('model'=>$model, 'house_id'=>$house_id)); ?>

<?php $this->renderPartial('/_include/footer'); ?>
