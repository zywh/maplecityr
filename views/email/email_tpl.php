<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title></title>
</head>
<body>
<div>
    <p>亲爱的<?php echo $name; ?>，您好！</p>
    <p>您已申请修改密码，请点击下面的链接即可完成修改&nbsp;<a href="<?php echo Yii::app()->request->hostInfo.Yii::app()->createUrl('user/update', array('email' => base64_encode($email))); ?>">点击链接修改密码</a></p>
    <p>感谢您光临用户中心，为您提供优质的服务！</p>
</div>
</body>
</html>