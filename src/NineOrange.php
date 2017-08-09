<?php 
	namespace HskyZhou\NineOrange;

	use Ixudra\Curl\CurlService;

	use Log;

	class NineOrange{
		/**
		 * 发送 普通短信
		 * @param		string $verify  验证码
		 * @param       array  $mobile  手机号
		 * @param       array  $sendTime 发送时间
		 * @author		xezw211@12.com
		 * @date		2016-09-16 07:24:54
		 * @return		
		 */
		public function sendMore($verify, $mobile, $sendTime = '', $extno = ''){
			$results = [
				'result' => false,
				'message' => '数据错误'
			];
			
			if(is_array($mobile)){
				foreach($mobile as $phone){
					$returnSendData = $this->sendOne($verify, $phone, $sendTime);
				}
			}
			
			return array_merge($results, $returnSendData, [
				'result' => true,
				'message' => '发送成功'
			]);
		}

		public function sendOne($verify_code, $mobile, $time = 0)
		{	
			$params = array(
			    'SpCode'=> config('nineorange.SpCode'),
			    'LoginName'=> config('nineorange.LoginName'),
			    'Password'=> config('nineorange.Password'),
			    'MessageContent'=> iconv("UTF-8", "GB2312//IGNORE", sprintf( config('nineorange.msg_template'), $verify_code)),
			    'UserNumber'=> $mobile,
			    'SerialNumber'=> $time ?: time(),
			    'ScheduleTime'=> config('nineorange.ScheduleTime'),
			    'ExtendAccessNum'=> config('nineorange.ExtendAccessNum'),
			    'f'=> config('nineorange.f'),
			);

			$results = $this->send($params);

			if($results['result']){
				Log::info('[success]手机号:' . $mobile . '; 验证码: ' . $verify_code);
			}else{
				Log::info('[fail]手机号:' . $mobile . '; 验证码: ' . $verify_code);
			}

			return $results;
		}

		public function sendCustom($content, $data, $mobile, $time = 0)
		{
			$params = array(
			    'SpCode'=> config('nineorange.SpCode'),
			    'LoginName'=> config('nineorange.LoginName'),
			    'Password'=> config('nineorange.Password'),
			    'MessageContent'=> iconv("UTF-8", "GB2312//IGNORE", vsprintf($content, $data),
			    'UserNumber'=> $mobile,
			    'SerialNumber'=> $time ?: time(),
			    'ScheduleTime'=> config('nineorange.ScheduleTime'),
			    'ExtendAccessNum'=> config('nineorange.ExtendAccessNum'),
			    'f'=> config('nineorange.f'),
			);

			$results = $this->send($params);

			if($results['result']){
				Log::info('[success]手机号:' . $mobile . '; 验证码: ' . $verify_code);
			}else{
				Log::info('[fail]手机号:' . $mobile . '; 验证码: ' . $verify_code);
			}

			return $results;
		}
		
		/*发送请求*/
		private function send($params){
			$service = new CurlService();
			$response =  $service->to(config('nineorange.apiURL'))->withData($params)->post();

			return $this->deal($response);
		}

		/*处理返回数据*/
		private function deal($response){
			$results = [
				'result' => true,
				'message' => "发送成功",
			];

			$response = iconv("gbk", "utf-8//IGNORE", $response);

			$info = [];
			parse_str($response, $info);

			if(empty($info) || $info['result'] != 0){
				$results = array_merge($results, [
					'result' => false,
					'message' => $info['description'],
				]);
			}

			return $results;
		}
	}