<?php

require_once('application/libraries/APN.php');


$apn = new APN();

$device_token = '6decb0f4d10c6dcfc416551073da108bd22c7d2b88965f5ac8a5114f2c754e5e1c1'; //设备token ,64位
if($apn->push($device_token,'test',2) ) {

	echo "Success! \n";

}else{
    echo "\n".$apn->error_number();
    echo "\n".$apn->error_message();
}
sleep(2);
