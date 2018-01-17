<?php
	#����֧������sdk
	require_once("alipay/AopSdk.php");
	#֧�������װ
	class Alipayment {
		#��ȡaopʵ��
		private $c;
		
		#��aop���г�ʼ��
		function __construct() {
			#��ȡconfig��������ʼ��aop
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
		
		#���𸶿�����
		function payRequest($arr) {
			#��Ҫ�Զ����ʼ�����������returnUrl, notifyUrl, outTradeNo, subject, total_amount, body 
			$request = new AlipayTradePagePayRequest ();
			$request->setReturnUrl($arr['returnUrl']);  
			$request->setNotifyUrl($arr['notifyUrl']);
			$request->setBizContent("{'product_code':'FAST_INSTANT_TRADE_PAY','qr_pay_mode':'2','out_trade_no':'{$arr['out_trade_no']}','subject':'{$arr['subject']}','total_amount':'{$arr['total_amount']}','body':'{$arr['body']}'}");
			
			//����  
			$result = $this->c->pageExecute($request);
			
			//����
			return $result;
		}
		
		#�����ѯ����
		function query($arr) {
			#��Ҫ�Զ����ʼ������������returnUrl, notifyUrl, tradeNo, subject, total_amount, body
			$request = new AlipayTradeQueryRequest ();
			$request->setBizContent("{'trade_no':'{$arr['trade_no']}','out_trade_no':'{$arr['out_trade_no']}'}");
				
			//����  
			$result = $this->c->execute ( $request);
			
			//����		
			$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
			$resultCode = $result->$responseNode->code;
			if(!empty($resultCode)&&$resultCode == 10000){
				return $result -> alipay_trade_query_response;
			} else {
				return "no trade info";
			}
		}
		
		#������֤
		function check($arr) {
			$result = $this->c->rsaCheckV1($arr, $this->c->alipayrsaPublicKey, $this->c->signType);
			return $result;
		}
	
	}
?>