<?php
/**
 * 借款合同上传到e签宝服务器
 *
 */
class SceneAPI {
	//构建证据链
	public function SceneAPI() {
		// 项目ID(公共应用ID)-模拟环境,正式环境下贵司将拥有独立的应用ID
		define("PROJECT_ID","1111563517");
		// 项目Secret(公共应用Secret)-模拟环境,正式环境下贵司将拥有独立的应用Secret
		define("PROJECT_SECRET","95439b0863c241c63a861b87d1e647b7");
		// 编码格式
		define("ENCODING","UTF-8");
		// 哈希算法
		define("ALGORITHM","HmacSHA256");

		/*
		 * 模拟环境_HTTP调用地址
		 */
		define("API_HOST","http://smlcunzheng.tsign.cn:8083");// API接口调用地址_模拟环境_HTTP
		define("VIEWPAGE_HOST","https://smlcunzheng.tsign.cn");// 存证证明查看页面Url（模拟环境）,仅有Https一种

		/*
		 * 模拟环境_HTTPS调用地址
		 */
		//define("API_HOST","https://smlcunzheng.tsign.cn:9443");//API接口调用地址_模拟环境_HTTPS
		//define("VIEWPAGE_HOST","https://smlcunzheng.tsign.cn");//存证证明查看页面Url（模拟环境）,仅有Https一种

		/*
		 * 正式环境_HTTP调用地址
		 */
		//define("API_HOST","http://evislb.tsign.cn:8080");// API接口调用地址_正式环境_HTTP
		//define("VIEWPAGE_HOST","https://eviweb.tsign.cn");// 存证证明查看页面Url（正式环境）,仅有Https一种

		/*
		 * 正式环境_HTTPS调用地址
		 */
		//define("API_HOST","https://evislb.tsign.cn:443");// API接口调用地址_正式环境_HTTPS
		//define("VIEWPAGE_HOST","https://eviweb.tsign.cn");// 存证证明查看页面Url（正式环境）,仅有Https一种


		// 定义所属行业类型API接口
		define("BUS_ADD_API",API_HOST."/evi-service/evidence/v1/sp/temp/bus/add");

		// 定义业务凭证（名称）API接口
		define("SCENE_ADD_API",API_HOST."/evi-service/evidence/v1/sp/temp/scene/add");

		// 定义业务凭证中某一证据点名称API接口
		define("SEG_ADD_API", API_HOST."/evi-service/evidence/v1/sp/temp/seg/add");
			// 定义业务凭证中某一证据点的字段属性API接口
		define("SEGPROP_ADD_API",API_HOST."/evi-service/evidence/v1/sp/temp/seg-prop/add");

			// 构建证据链API接口
		define("VOUCHER_API", API_HOST."/evi-service/evidence/v1/sp/scene/voucher");

			// 创建原文存证（基础版）证据点API接口
		define("ORIGINAL_STANDARD_API", API_HOST."/evi-service/evidence/v1/sp/segment/original-std/url");

			// 创建原文存证（高级版）证据点API接口
		define("ORIGINAL_ADVANCED_API", API_HOST."/evi-service/evidence/v1/sp/segment/original-adv/url");

			// 创建摘要存证证据点API接口
		define("ORIGINAL_DIGEST_API", API_HOST."/evi-service/evidence/v1/sp/segment/abstract/url");

			// 关联证据点到证据链上API接口
		define("VOUCHER_APPEND_API", API_HOST."/evi-service/evidence/v1/sp/scene/append");

			// 场景式存证编号(证据链编号)关联到指定用户API接口
		define("RELATE_API", API_HOST."/evi-service/evidence/v1/sp/scene/relate");

			// 存证证明查看页面Url
		define("VIEWPAGE_URL",VIEWPAGE_HOST."/evi-web/static/certificate-info.html");
	}
	public function creatEviChain($sceneTemplateId){
		//设置构建证据链参数
		$param = array(
			"sceneName"=>"想要显示的场景式存证名称",
			"sceneTemplateId"=>$sceneTemplateId,
			"linkIds"=>array(),
		);
		$pa=json_encode($param);
		$signature =  getSignature($pa,PROJECT_SECRET);
		echo "【1】创建证据链参数";
		echo  $pa;
		echo "<br/>";
		$array = http_post_data($pa, $signature, PROJECT_ID , VOUCHER_API);
		$json_string=$array[1];
		echo " 创建证据链参数返回结果：";
		echo $json_string;
		echo "<br/>";
		return $json_string;

	}
    //创建 基础版 存证证据点
    public function creatEviSpotBasics($filePath,$segmentTempletId){
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);
        $contentBase64Md5 = getContentBase64Md5($filePath);
        //设置创建证据点参数
        $param = array(
          "segmentTempletId" => $segmentTempletId,
           "segmentData"=>"{\"realName1\":\"赵明丽\",\"userName1\":\"zhaomingli@贵司系统内的登录账号\",\"realName2\":\"金明丽\",\"userName2\":\"jinmingli@贵司系统内的登录账号\"}",
            "content"=>array(
                "contentDescription"=>$fileName,
                "contentLength"=>$fileSize,
                "contentBase64Md5"=>$contentBase64Md5
            )
        );
        $pa=json_encode($param);
        echo "创建证据点参数：";
        echo $pa;
        echo "<br/>";

        $signature =  getSignature($pa,PROJECT_SECRET);
        $array = http_post_data($pa, $signature, PROJECT_ID , ORIGINAL_STANDARD_API);
        $json_string=$array[1];
        echo " 创建证据点返回结果：";
        echo $json_string;
        echo "<br/>";
        return $json_string;
    }


	//创建 高级 存证证据点
	public function creatEviSpotsenior($filePath,$segmentTempletId){
		$fileName = basename($filePath);
		$fileSize = filesize($filePath);
		$contentBase64Md5 = getContentBase64Md5($filePath);
		//设置创建证据点参数
		$param = array(
			"segmentTempletId" => $segmentTempletId,
			"segmentData"=>"{\"realName1\":\"赵明丽\",\"userName1\":\"zhaomingli@贵司系统内的登录账号\",\"realName2\":\"金明丽\",\"userName2\":\"jinmingli@贵司系统内的登录账号\"}",
			"content"=>array(
				"contentDescription"=>$fileName,
				"contentLength"=>$fileSize,
				"contentBase64Md5"=>$contentBase64Md5
			)
		);
		$pa=json_encode($param);
		echo "创建证据点参数：";
		echo $pa;
		echo "<br/>";
		$signature =  getSignature($pa,PROJECT_SECRET);
		$array = http_post_data($pa, $signature, PROJECT_ID , ORIGINAL_ADVANCED_API);
		$json_string=$array[1];
		echo " 创建证据点返回结果：";
		echo $json_string;
		echo "<br/>";
		return $json_string;
	}

	//创建 摘要 存证证据点
	public function creatEviSpotAbstract($filePath,$segmentTempletId){
		$fileName = basename($filePath);
		$filesha256 = hash_file('sha256',$filePath,false);
		//设置创建证据点参数
		$param = array(
			"segmentTempletId" => $segmentTempletId,
			"segmentData"=>"{\"realName1\":\"赵明丽\",\"userName1\":\"zhaomingli@贵司系统内的登录账号\",\"realName2\":\"金明丽\",\"userName2\":\"jinmingli@贵司系统内的登录账号\"}",
			"content"=>array(
				"contentDescription"=>$fileName,
				"contentDigest"=>$filesha256,
			)
		);
		$pa=json_encode($param);
		echo "创建证据点参数：";
		echo $pa;
		echo "<br/>";
		$signature =  getSignature($pa,PROJECT_SECRET);
		$array = http_post_data($pa, $signature, PROJECT_ID , ORIGINAL_DIGEST_API);
		$json_string=$array[1];
		echo " 创建证据点返回结果：";
		echo $json_string;
		echo "<br/>";
		return $json_string;
	}

    //根据url 上传文件
    public function uploadFile($fileUploadUrl,$filePath){
        echo "<br/>";
        echo "上传文件...";
        echo "<br/>";
        $fileContent = file_get_contents($filePath);
        $contentBase64Md5 = getContentBase64Md5($filePath);
        $status = sendHttpPUT($fileUploadUrl, $contentBase64Md5, $fileContent);
        if ($status == 200) {
            echo "上传成功！！！";
            echo "<br/>";
            return ['status' => 'success', 'code' => $status, 'msg' => '待保全文档上传成功'];
        }
        echo "上传失败！！！";
        echo "<br/>";
        return ['status' => 'error', 'code' => $status, 'msg' => '待保全文档上传失败'];
    }
	//将存证证据点追加到证据链中
    public function addEviChain($sceneId,$evid){
        //设置行业类型参数
        $param = array(
            "evid"=>$sceneId,
            'linkIds' => array(
                array(
                    "type"=>"0",
                    "value"=>$evid
                ),
                array(
                    "type"=>"1",
                    "value"=>"953477160000098310"
                ),
                array(
                    "type"=>"1",
                    "value"=>"953474322771513346"
                ),
                array(
                    "type"=>"1",
                    "value"=>"953474984796262406"
                )
            )
        );
        $pa=json_encode($param);
        $signature =  getSignature($pa,PROJECT_SECRET);
        echo "将证据点追加到证据链的参数：";
        echo  $pa;
        echo "<br/>";
        $array = http_post_data($pa, $signature, PROJECT_ID , VOUCHER_APPEND_API);
        $json_string=$array[1];
        $obj = json_decode($json_string);
        $errcode = $obj->errCode;
        echo " 将证据点追加到证据链返回结果：";
        echo $json_string;
        echo "<br/>";
        return $json_string;
    }
    //存证记录关联到指定用户
    public function sceneEvIdWithUser($sceneId){
        $param = array(
            "evid"=>$sceneId,
            'certificates' => array(
                array(
                    "type"=>"ID_CARD",
                    "number"=>"540101198709260015",
                    "name"=>"赵明丽"
                ),
                array(
                    "type"=>"ID_CARD",
                    "number"=>"540101198709260058",
                    "name"=>"金明丽"
                ),
                array(
                    "type"=>"CODE_USC",
                    "number"=>"913301087458306077",
                    "name"=>"杭州天谷信息科技有限公司"
                )
            )
        );
        $pa=json_encode($param);
        $signature =  getSignature($pa,PROJECT_SECRET);
        echo "关联到指定用户参数：";
        echo  $pa;
        echo "<br/>";
        $array = http_post_data($pa, $signature, PROJECT_ID , RELATE_API);
        $json_string=$array[1];
        echo " 关联到指定用户返回结果：";
        echo $json_string;
        echo "<br/>";
        return $json_string;
    }
    //拼接查看存证证明URL
    public function getViewCertificateInfoUrl($sceneId){

        $timestampString = null;
        // 存证证明页面查看地址Url的有效期：
        $reverse = "false";
        if ("false"==$reverse) {
            // false表示timestamp字段为链接的生效时间，在生效30分钟后该链接失效
            $timestampString = get_total_millisecond();// 当前系统的时间戳
        } else {
            // true表示timestamp字段为链接的失效时间
            $year = '2018';
            $month = '12';
            $day = '31';
            $hour = '23';
            $minute = '59';
            $second = '00';
            $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
            $timestampString = $timestamp . '000';// 假设在2017年12月31日的23点59分00秒失效
            //echo $timestampString;
        }

        $param ="id=" . $sceneId . "&projectId=" . PROJECT_ID . "&timestamp=" . $timestampString .
            "&reverse=" . $reverse . "&type=" . "ID_CARD" . "&number=" . "540101198709260015";
        echo "<br/>";

        $signture = getSignature($param,PROJECT_SECRET);
        echo "查看存证明跳转链接：";
        $viewUrl = VIEWPAGE_URL . "?" . $param . "&signature=" . $signture;
        echo $viewUrl;
        echo "<br/>";
        return $viewUrl;

    }
	
	
	//工具类
	//模拟发送POST请求
	/**
	 * 模拟发送POST 方式请求
	 * @param $url
	 * @param $data
	 * @param $projectId
	 * @param $signature
	 * @return array
	 */
	public function http_post_data( $data,  $signature, $projectId, $url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-timevale-mode:package", "X-timevale-project-id:" . $projectId, "X-timevale-signature:" . $signature, "X-timevale-signature-algorithm:hmac-sha256", "Content-Type:application/json"));
		ob_start();
		curl_exec($ch);
		$return_content = ob_get_contents();

		ob_end_clean();
		$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return array($return_code, $return_content);
	}

	/**
	 *
	 * 模拟发送PUT方式请求
	 * @param $url
	 * @param $contentBase64Md5
	 * @param $fileContent
	 * @return mixed|string
	 * @author Ayz
	 */
	public function sendHttpPUT($url, $contentBase64Md5, $fileContent)
	{
		$header = [
			'Content-Type:application/octet-stream',
			'Content-Md5:' . $contentBase64Md5
		];

		$status = '';
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_FILETIME, true);
		curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
		curl_setopt($curl_handle, CURLOPT_HEADER, true); // 输出HTTP头 true
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);

		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');

		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fileContent);
		$result = curl_exec($curl_handle);
		$status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

		if ($result === false) {
			$status = curl_errno($curl_handle);
			$result = 'put file to oss - curl error :' . curl_error($curl_handle);
		}
		curl_close($curl_handle);
	//    $this->debug($url, $fileContent, $header, $result);
		return $status;
	}

	/**
	获取文件的Content-MD5
	原理：1.先计算MD5加密的二进制数组（128位）。
	2.再对这个二进制进行base64编码（而不是对32位字符串编码）。
	 */
	public function getContentBase64Md5($filePath){
		//获取文件MD5的128位二进制数组
		$md5file = md5_file($filePath,true);
		//计算文件的Content-MD5
		$contentBase64Md5 = base64_encode($md5file);
		return $contentBase64Md5;
	}

	/**
	 * 计算文件的sha256
	 * @param $filePath
	 * @return string
	 */
	public function getFileSha256($filePath){
		return  hash_file('sha256',$filePath,false);
	}

	//计算请求签名值
	public function getSignature($message, $projectSecret) {
		$signature = hash_hmac('sha256', $message, $projectSecret, FALSE);
		return $signature;
	}

	//获取当前时间戳（毫秒级）
	public function get_total_millisecond()
	{
		$time = explode (" ", microtime () );
		$time = $time [1] . ($time [0] * 1000);
		$time2 = explode ( ".", $time );
		$time = $time2 [0];
		return $time;
	}
}