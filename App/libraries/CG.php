<?php
/*存管接口调用*/
class CG{

	private $merchantNo;              // 交易商户号
	private $mer_pri_file;           // 商户RSA私钥，解密用
	private $pay_pub_file;          // 平台RSA公钥，加密用
	private $req_head;             //组织报文头
	private $req_body;            //请求报文体
    private $serverLocation;     //存管平台请求地址
	private $cgNo;              //存管接口编号
	private $key;              //key
	private $pubKey = null;  
	private $priKey = null;

    /** 
     * 构造函数
     */  
	public function __construct()
	{
		//$config= require_once dirname(__FILE__).'/CG.conf.php';
		$this->merchantNo='134020000011001';
		$this->mer_pri_file='MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDfmDJSKTse/q9YxQZ9HBYItAqI2XbshMTPZBwIqUWhM9ykLYsOnK7JuJrNq0jnQrKENlJSSNE9Q7NQ7lMaFlIuqi5o9KtlwEg+0iCaEr3aQyWMGL3Z0T/KINIe/IwCgrlY26t/1ysdyufp9HzZYrNd1kYYWG0akiSLN0pR+tO0Gw7e7gKCyr2jMm/29CM7KA6P3jcf3VkZMTJ986uTZy4aZ/qMeN9yNQigFzwdV8yAOk6cVQ1pjOOKEUH3ElcenyMmShLCnenLcdxyPPR9W/oyIs6uqn2CfqX96lrBgwAZR6tUdBbXbeVKCNEHqZ/cBVIKVHfNxbQS7b/IU9bii3W5AgMBAAECggEACBiVsd3mUx/7r2Yq7vrGXXhPbVcadEP2cF/6l+GxML4U/ijcASJxPcKecFYEZMNIfXsODgau9N3vrz2B6s7mGOR2m7xIPH3y37tyDMIi97U4tdLrhOiLu8DmetLAKxMarPn/QrjCyehf71u37ZMiVYJ9tUH2aWDjS9srqL5BTiMfV+Fr0s0gvlauCNDAo29T4OLclKdu01h1EOVfTF+OFxXWevyX4LopUVW/qdgdbO2TAgMrfhjgGr3kSmtZz6CyB7I/MFD+KtkPzA+D6OQBft/qIneqsmGjprkg7JXeTMINOp3fjGNbYKKZNPJcNBc9QZYJ/xrHLIa8PpBjLLyLRQKBgQD3Of0O3e+R9GEkxym8SfHMdFw08jOQ7lQkU9lYG2pugU2bdfu8S/Y4Jjot5HUB2dTN6B9mhKazDEcb9KAIgsOW5NaoHWhYwaIts7w5hbYuwqaNFPdYzRUQyhFAYpiXTBXYQ/irF2HrupXchXbxrSdVHFBUCT5Rv97sKb94Ekfj3wKBgQDnh4Pqqnid/FZ8U6FAMWeGlqCYnaQ6b8S85HkGJV3mCtEPRnzUCd5dUG4nnjWdVer1JsJhmy1eYb29HcdmWEUXfKWzgv3ZHpuBvq9WfKxVbWQyq/P2NP+kDQjumioSNsWDl6UnHC/Q/4oO/QMyZUyHJK39Be27gjYeq/8XmWIZZwKBgCeJpZfoFHVCIPsRwvHy3XG2NjzdczO9ecVph3vYvpe3pCK1prHBcDogmvNV8wR9wtO/SK5LjqFZ2FF3ONTDnOYX6OBv+40eQoKOmQ6V/MHTrXEopjoUakcKGeIzInYn2+J/VFiAX8+kQm1HBvO1nnG1Gn+mDspiJhOcxZs9STP/AoGBAMz/fPrLQ47GvSDJv22iEMLnHXzjn2SqT6zcMpgRf8lObHq085OexUckDtR/TYNobmfvkI5xokL+Ecyf0Ey6/TnlgKWn5vFWG2ROnNrb+o6NE/mVrKWjmXH8pJr23ydPXaS9IVq2p1PK3seUmD8H2DGUdYUhubObgCW6LlwL0AMjAoGAI5Sv4yXSS7DUcxZIQDs+WwCkKxCEzdgMO/XptSR6fKtCFqxqzO31F0VxY1AmSi4Tu3JThYvbEY6ZKRyraXKayYrfivRIMKmbVtXhAQVIUJjmTIVHCMITzoc3pmoyrxcd1mkp9I0dZHlDpQP3iIxvJKHrMliUBfO+BX0JLC4KOl8=';
		$this->pay_pub_file='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2gTIrhjgqxWbHIAX2HMXa1bD5s4VIk3pm+H/q0zUFXLD+cD1MAWr5EX7dM3V9eh8slt7dfbbJSDFKxEUOxjl850Ba8CLof8m+rciXKp1DpwrjtP8iCgoIoqUCD+vS9fJNfx4d+oz6eRyEwF0uZw9r0FMAI+GLcerNNJFevj/q4PYyhu9ulRb5EyRBLd3vpRRwZbyuj+w6yTh2JC0DaNHhRSd4h1pdqe886nl4ygr1rd5lkX/fQY/xbbsj9USXY15StTc1V3vjL90hGSdDpM2nhgBES3PFv7wPEgYi536wXq4pX6rM+Pruu/EM/bZYt6qHZhctzJEB5R2IGC8cwLDCQIDAQAB';
		$this->serverLocation='http://220.248.70.91:8180/dep-page-service/cg';
        $this->_getPublicKey($this->pay_pub_file);
        $this->_getPrivateKey($this->mer_pri_file);
	}

	/**
	 * 统一接口
	 */
	public function getInstance($merOrderNo,$cgNo,$reqBody)
	{
		$reqHead=$this->getReqHead($merOrderNo,$cgNo);
		$data=array("head"=>$reqHead,"body"=>$reqBody);
		$json_ori=json_encode($data);
		//生成报文
		$req=$this->getReq($merOrderNo,$json_ori);
		//请求存管接口
		$this->cgNo=$cgNo;
		//向存管接口发起请求
		$result=json_decode($this->request($req),true);
		//解密并验签返回报文
		$key = $this->decrypt($result['keyEnc'],'hex');
		$json = $this->desDecrypt($result['jsonEnc'],$key);
		if(!$this->verify($json,$result['sign'],'hex')) $this->_error("Signature error");
		//签名验证成功后，返回结果
		$reData=json_decode($json,true);
		return $reData;
	}

	/**
	 * 生成报文头
	 * merOrderNo  商户订单号
	 * cgNo  存管接口的编号
	 */
	private function getReqHead($merOrderNo,$cgNo)
	{
		$req_head['merchantNo' ] = $this->merchantNo;
		$req_head['tradeType' ] =  '00' ;
		$req_head['version' ] = '1.0.0' ;
		$req_head['merOrderNo' ] =  $merOrderNo;
		$req_head['tradeDate' ] = date ('Ymd'); 
		$req_head['tradeTime' ] = date ('His');
		$req_head['tradeCode' ] =  'CG'.$cgNo;
		return $req_head;
	}

	/**
	 * 生成报文
	 */
	private function getReq($merOrderNo,$json_ori)
	{
		$this->key = rand(pow(10,(8-1)), pow(10,8)-1);
		$req['merchantNo'] = $this->merchantNo;          //为平台为商户分配的商户号明文
		$req['merOrderNo'] = $merOrderNo;                //商户订单号，商户请求的唯一标识
		$req['jsonEnc'] = $this->desEncrypt($json_ori,$this->key); //报文密文
		$req['keyEnc'] = $this->encrypt($this->key,'hex');        //会话密钥密文
		$req['sign' ]  = $this->sign($json_ori,'hex');       //报文签名
		return $req;
	}

    /** 
     * 发送http请求
     */  
	private function request($postData){
		$post_string=json_encode($postData);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->serverLocation.$this->cgNo);
		curl_setopt($ch, CURLOPT_POST,strlen($post_string));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

    /** 
     * 自定义错误处理 
     */  
    private function _error($msg){  
        die('RSA Error:' . $msg); //TODO  
    } 

	/************************************ENC*******************************************/
    /** 
     * 生成签名 
     * 
     * @param string 签名材料 
     * @param string 签名编码（base64/hex/bin） 
     * @return 签名值 
     */  
    public function sign($data, $code = 'base64'){  
        $ret = false;  
        if (openssl_sign($data, $ret, $this->priKey)){  
            $ret = $this->_encode($ret, $code);  
        }  
        return $ret;  
    }  
  
    /** 
     * 验证签名 
     * 
     * @param string 签名材料 
     * @param string 签名值 
     * @param string 签名编码（base64/hex/bin） 
     * @return bool  
     */  
    public function verify($data, $sign, $code = 'base64'){  
        $ret = false;      
        $sign = $this->_decode($sign, $code);  
        if ($sign !== false) {  
            switch (openssl_verify($data, $sign, $this->pubKey)){  
                case 1: $ret = true; break;      
                case 0:      
                case -1:       
                default: $ret = false;       
            }  
        }  
        return $ret;  
    }  
  
    /** 
     * 加密 
     * 
     * @param string 明文 
     * @param string 密文编码（base64/hex/bin） 
     * @param int 填充方式（貌似php有bug，所以目前仅支持OPENSSL_PKCS1_PADDING） 
     * @return string 密文 
     */  
    public function encrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING){
        $ret = false;      
        if (!$this->_checkPadding($padding, 'en')) $this->_error('padding error');  
        if (openssl_public_encrypt($data, $result, $this->pubKey, $padding)){  
            $ret = $this->_encode($result, $code);  
        }  
        return $ret;  
    }  
  
    /** 
     * 解密 
     * 
     * @param string 密文 
     * @param string 密文编码（base64/hex/bin） 
     * @param int 填充方式（OPENSSL_PKCS1_PADDING / OPENSSL_NO_PADDING） 
     * @param bool 是否翻转明文（When passing Microsoft CryptoAPI-generated RSA cyphertext, revert the bytes in the block） 
     * @return string 明文 
     */  
    public function decrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false){  
        $ret = false;  
        $data = $this->_decode($data, $code);  
        if (!$this->_checkPadding($padding, 'de')) $this->_error('padding error');  
        if ($data !== false){  
            if (openssl_private_decrypt($data, $result, $this->priKey, $padding)){  
                $ret = $rev ? rtrim(strrev($result), "\0") : ''.$result;  
            }   
        }  
        return $ret;  
    }  

     public function desEncrypt($str,$key) {
        $iv = $key;
         $size = 8;//mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
         $str = $this->_pkcs5_pad ( $str, $size );
         return strtoupper( bin2hex( mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $iv ) ) );
     }
     
     public function desDecrypt($str,$key) {
        $iv = $key;
         $strBin = $this->_hex2bin( strtolower( $str ) );
         $str = mcrypt_cbc( MCRYPT_DES, $key, $strBin, MCRYPT_DECRYPT, $iv );
         $str = $this->_pkcs5_unpad( $str );
         return $str;
     }
    
  
    // 私有方法  
  
    /** 
     * 检测填充类型 
     * 加密只支持PKCS1_PADDING 
     * 解密支持PKCS1_PADDING和NO_PADDING 
     *  
     * @param int 填充模式 
     * @param string 加密en/解密de 
     * @return bool 
     */  
    private function _checkPadding($padding, $type){  
        if ($type == 'en'){  
            switch ($padding){  
                case OPENSSL_PKCS1_PADDING:  
                    $ret = true;  
                    break;  
                default:  
                    $ret = false;  
            }  
        } else {  
            switch ($padding){  
                case OPENSSL_PKCS1_PADDING:  
                case OPENSSL_NO_PADDING:  
                    $ret = true;  
                    break;  
                default:  
                    $ret = false;  
            }  
        }  
        return $ret;  
    }  
  
    private function _encode($data, $code){  
        switch (strtolower($code)){  
            case 'base64':  
                $data = base64_encode(''.$data);  
                break;  
            case 'hex':  
                $data = bin2hex($data);  
                break;  
            case 'bin':  
            default:  
        }  
        return $data;  
    }  
  
    private function _decode($data, $code){  
        switch (strtolower($code)){  
            case 'base64':  
                $data = base64_decode($data);  
                break;  
            case 'hex':  
                $data = $this->_hex2bin($data);  
                break;  
            case 'bin':  
            default:  
        }  
        return $data;  
    }  
  
    private function _getPublicKey($file){  
        /* $key_content = $this->_readFile($file);  
        if ($key_content){  
            $this->pubKey = openssl_get_publickey($key_content);  
        }   */
		$this->pubKey = openssl_get_publickey($file); 
    }  
  
    private function _getPrivateKey($file){  
        /* $key_content = $this->_readFile($file);  
        if ($key_content){  
            $this->priKey = openssl_get_privatekey($key_content);  
        }   */
		$this->pubKey = openssl_get_privatekey($file); 
    }  
  
    private function _readFile($file){  
        $ret = false;  
        if (!file_exists($file)){  
            $this->_error("The file {$file} is not exists");  
        } else {  
            $ret = file_get_contents($file);  
        }  
        return $ret;  
    }  
  
  
    private function _hex2bin($hex = false){  
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;      
        return $ret;  
    }  
  
     private function _pkcs5_pad($text,$block=8){
         $pad = $block - (strlen($text) % $block);
         return $text . str_repeat(chr($pad), $pad);
     }
     
     private function _pkcs5_unpad($text) {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return $text;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
        return substr($text, 0, -1 * $pad);
     }  

}