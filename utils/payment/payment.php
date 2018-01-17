<?php
	#载入支付宝的sdk
	require_once("alipay/AopSdk.php");
	#支付宝类包装
	class Alipayment {
		#获取aop实例
		private $c;
		
		#对aop进行初始化
		function __construct() {
			#读取config参数，初始化aop
			require_once("config.php");
			$this->c = new AopClient();
			$this->c->gatewayUrl = $alipayConfig['gatewayUrl'];
			$this->c->appId = $alipayConfig['appId'];
			$this->c->rsaPrivateKey = $alipayConfig['rsaPrivateKey'];
			$this->c->alipayrsaPublicKey = $alipayConfig['alipayrsaPublicKey'];
			$this->c->format = $alipayConfig['format'];
			$this->c->charset= $alipayConfig['charset'];
			$this->c->signType= $alipayConfig['signType'];
		}
		
		#发起付款请求
		function payRequest($arr) {
			#需要自定义初始化五个参数：returnUrl, notifyUrl, outTradeNo, subject, total_amount, body 
			$request = new AlipayTradePagePayRequest ();
			$request->setReturnUrl($arr['returnUrl']);  
			$request->setNotifyUrl($arr['notifyUrl']);
			$request->setBizContent("{'product_code':'FAST_INSTANT_TRADE_PAY','qr_pay_mode':'2','out_trade_no':'{$arr['out_trade_no']}','subject':'{$arr['subject']}','total_amount':'{$arr['total_amount']}','body':'{$arr['body']}'}");
			
			//请求  
			$result = $this->c->pageExecute($request);
			
			//返回
			return $result;
		}
		
		#发起查询请求
		function query($arr) {
			#需要自定义初始化两个参数：returnUrl, notifyUrl, tradeNo, subject, total_amount, body
			$request = new AlipayTradeQueryRequest ();
			$request->setBizContent("{'trade_no':'{$arr['trade_no']}','out_trade_no':'{$arr['out_trade_no']}'}");
				
			//请求  
			$result = $this->c->execute ( $request);
			
			//返回		
			$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
			$resultCode = $result->$responseNode->code;
			if(!empty($resultCode)&&$resultCode == 10000){
				return $result -> alipay_trade_query_response;
			} else {
				return "no trade info";
			}
		}
		
		#进行验证
		function check($arr) {
			$result = $this->c->rsaCheckV1($arr, $this->c->alipayrsaPublicKey, $this->c->signType);
			return $result;
		}
	
	}
?>