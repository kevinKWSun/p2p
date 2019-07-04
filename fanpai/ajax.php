<?php

$prize_arr = array(
    '0' => array('id' => 1, 'prize' => '京东卡100', 'v' => 0),
    '1' => array('id' => 2, 'prize' => '话费100', 'v' => 0),
    '2' => array('id' => 3, 'prize' => '京东卡200', 'v' => 0),
    '3' => array('id' => 4, 'prize' => '话费200', 'v' => 0),
    '4' => array('id' => 5, 'prize' => '京东卡300', 'v' => 0),
    '5' => array('id' => 6, 'prize' => '京东卡500', 'v' => 0),
	'6' => array('id' => 7, 'prize' => '苹果 AirPods', 'v' => 0.8),
	'7' => array('id' => 8, 'prize' => '苹果 iWatch 4', 'v' => 0.4),
	'8' => array('id' => 9, 'prize' => 'Mate20X 128G', 'v' => 0.3),
	'9' => array('id' => 10, 'prize' => 'Xsmax 256G', 'v' => 0.1),
	'10' => array('id' => 11, 'prize' => '小满限量版礼包', 'v' => 0),
	'11' => array('id' => 12, 'prize' => 'MacAir13.3 256G', 'v' => 0),
);
foreach ($prize_arr as $key => $val) {
    $arr[$val['id']] = $val['v'];
}

$rid = get_rand($arr); //根据概率获取奖项id 

$res['yes'] = $prize_arr[$rid - 1]['prize']; //中奖项 

unset($prize_arr[$rid - 1]); //将中奖项从数组中剔除，剩下未中奖项 

shuffle($prize_arr); //打乱数组顺序 

for ($i = 0; $i < count($prize_arr); $i++) {
    $pr[] = $prize_arr[$i]['prize'];
}
$res['no'] = $pr;
echo json_encode($res);

function get_rand($proArr) {
    $result = '';
    $proSum = 100;//array_sum($proArr);//总数
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
		if($proCur == 0){
			continue;
		}else{
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
    }
    unset($proArr);
    return $result;
}
