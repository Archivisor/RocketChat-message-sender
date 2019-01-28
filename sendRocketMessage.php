<?php
/**
 * Created by PhpStorm.
 * User: V. Bolshakov
 * Date: 25.01.2019
 * Time: 16:18
 */
class sendRocketMessage{
    private $rocket_creds = array(
        'username' => '',
        'password' => ''
    );
    private $rocket_url = 'https://messenger.8bitgroup.com';
    private $session_params = array();
    private $authorized = false;

    public function __construct(){
		if(!$this->authorized) $this->auth();
	}

	private function auth(){
        # Отправка запроса на авторизацию на API Рокет.чата
        $url = $this->rocket_url.'/api/v1/login';
		$headers = array(
            'Content-type: application/x-www-form-urlencoded',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        );
		$postString = http_build_query($this->rocket_creds, '', '&');
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => implode("\r\n", $headers),
                'content' => $postString
            ));

        $context  = stream_context_create($opts);
        $ch = file_get_contents($url, false, $context);

		# Обработка ответа и сохранения данных авторизации для последующих запросов
		$resp_obj = json_decode($ch);
		if ($resp_obj->{"data"}->{"userId"}!==null && $resp_obj->{"data"}->{"authToken"}!==null){
            $this->session_params['user-id'] = $resp_obj->{"data"}->{"userId"};
            $this->session_params['auth-token'] = $resp_obj->{"data"}->{"authToken"};
            $this->authorized = true;
            return true;
        } else {
            return false;
        }
	}

	# Пользовательская функци отправки сообщения
    public function send($channel = false, $message = ''){
        if($channel) {
            $data = array(
                'channel' => '@' . $channel,
                'text' => $message
            );
            return $this->postmeth($data);
        } else return false;
	}


	# Функция генерации и отправки HTTP запроса типа POST
    private function postmeth($postdata=''){
		if($this->authorized){
            $headers = array(
                'X-Auth-Token: ' . $this->session_params['auth-token'],
                'X-User-Id: ' . $this->session_params['user-id'],
                'Content-Type: application/json'
            );
            $url = $this->rocket_url.'/api/v1/chat.postMessage';
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => implode("\r\n", $headers),
                    'content' => json_encode($postdata)
                ));

            $context  = stream_context_create($opts);
            $result = file_get_contents($url, false, $context);
            return $result;
        } else return false;
	}
}