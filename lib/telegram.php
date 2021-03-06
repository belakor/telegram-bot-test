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
				$keyboard = [
				    ['si'],
				    ['no'],
				    ['depende']
				];
				
				$parametros = array(
				    'keyboard' => $keyboard, 
				    'resize_keyboard' => true, 
				    'one_time_keyboard' => true
				);
				
				// $reply_markup = $this->request('replyKeyboardMarkup', $parametros);
				
				$params = array
				(
					'chat_id' => $chat_id,
					'text' => $response,
					'disable_web_page_preview' => null,
					'reply_to_message_id' => $reply_to_message_id,
					'reply_markup' => json_encode($parametros)
				);
				$this->request('sendMessage', $params);
				break;
			}

			case '/info':
				$response = "Bot creado para pruebas";
				$params = array
                (
                	'chat_id' => $chat_id,
                    'text' => $response,
                    'disable_web_page_preview' => null,
                    'reply_to_message_id' => $reply_to_message_id
                );
                $this->request('sendMessage', $params);
				default:
				break;
			
		}
	}
}
