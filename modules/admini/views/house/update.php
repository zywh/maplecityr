<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
    <h3>房源管理</h3>
    <div class="searchArea">
        <ul class="action left" >
            <li class="current"><a href="<?php echo $original; ?>" class="actionBtn"><span>返回</span></a></li>
        </ul>
        <div class="search right"> </div>
    </div>
</div>
<?php $this->renderPartial('_form',array('model'=>$model, 'imageList'=>$imageList));?>
<?php $this->renderPartial('/_include/footer');?>
