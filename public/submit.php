<?php
$host = "/createOrder";
if(!empty($_GET) && $_GET['pid']){
    $param = http_build_query($_GET);
}else if(!empty($_POST)){
    $param = http_build_query($_POST);
}else{
    exit(['code' => 0,'msg' => '请传入参数']);
}
echo '<script>location.href = "'.$host."?".$param.'"</script>';