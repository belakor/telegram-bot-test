<?php
include('curl.php');

class telegram
{
	private $url = 'https://api.telegram.org/bot';
	private $api_key;

	public function __construct($api_key)
	{
		$this->api_key = $api_key;
	}

	public function request($method, $params = array())
	{
		$c = new curl();
		$r = $c->request($this->url.$this->api_key."/".$method, 'POST', $params);

		$j = json_decode($r, true);
		if($j)
			return $j;
		else
			return $r;
	}

	public function parse($item)
	{
		$text = $item['message']['text'];
		$chat_id = $item['message']['chat']['id'];
                $reply_to_message_id = $item['message']['message_id'];

		switch($text)
		{
			case '/fecha':
			{
				$response = "Estamos a " . date("d") . " del " . date("m") . " de " . date("Y");

				$params = array
				(
					'chat_id' => $chat_id,
					'text' => $response,
					'disable_web_page_preview' => null,
					'reply_to_message_id' => $reply_to_message_id
				);
				$this->request('sendMessage', $params);
				break;
			}
			
			case '/teclado':
			{
				$response = "¿Funciona el teclado personalizado?";
				// $keyboardButton1 = array(
				// 	'text' => "si"
				// );
				// $keyboardButton2 = array(
				// 	'text' => "no"
				// );
				// $keyboardButton3 = array(
				// 	'text' => "depende"
				// );
				// $keyboard = array(
				// 	'keyboard' => array($keyboardButton1, $keyboardButton2, $keyboardButton3)
				// );
				$params = array
				(
					'chat_id' => $chat_id,
					'text' => $response,
					'disable_web_page_preview' => null,
					'reply_to_message_id' => $reply_to_message_id,
					// 'reply_markup' => $keyboard
				);
				$this->request('sendMessage', $params);
				break;
			}

			case '/golazo':
			{
				$filename = PATH."/images/senor.jpg";

				if(class_exists('CURLFile'))
					$cfile = new CURLFile($filename);
				else
					$cfile = "@".$filename;

                                $params = array
                                (
					'chat_id' => $chat_id,
					'photo' => $cfile,
					'reply_to_message_id' => $reply_to_message_id,
					'reply_markup' => null
                                );
				$this->request('sendPhoto', $params);

				break;
			}

			case '/getInfo':
                                $params = array
                                (
                                        'chat_id' => $chat_id,
                                        'text' => print_r($item, true),
                                        'disable_web_page_preview' => null,
                                        'reply_to_message_id' => $reply_to_message_id
                                );
                                $this->request('sendMessage', $params);
			default:
				break;
		}
	}
}
