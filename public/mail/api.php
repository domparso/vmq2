<?php
require_once("./functions.php");

if(empty($_GET)){
    exit(json_encode(['code' => 0,'msg' => '请求参数为空']));
}else if(empty($_GET['sign']) || $_GET['sign'] != md5($_GET['name'].$_GET['content'].$_GET['address'].$row['password']) ){
    exit(json_encode(['code' => 0,'msg' => '缺少sign参数或者sign错误']));
}
$userName = trim($_GET['name']);
$certno = trim($_GET['content']);
// $mobile=trim($_GET['mobile']);
$address = trim($_GET['address']);

$datetime = date("Y-m-d h:i:s", time()); //时间

//多邮件示例
$email = array($address);

//$subject为邮件标题
$subject = $userName;

//$content为邮件内容
$content=$certno;


//执行发信
$flag = sendMail($email,$subject,$content);
if($flag){
    echo json_encode(['code' => 1,'msg' => '发送成功']);
}else{
    echo json_encode(['code' => 0,'msg' => '发送失败']);
}
