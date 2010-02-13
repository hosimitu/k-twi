<?php
class UsersController extends AppController {
	var $name = "Users";
	var $uses = array('User');
	var $components = array('OauthConsumer', 'Ktai');
	var $needAuth = false;
	var $helpers = array('Html', 'Form', 'Ktai', 'Cache', 'Css');
	var $ktai = array(							//携帯でのセッション等の設定
			'enable_ktai_session' => true, 			//セッション使用を有効にします
			'use_redirect_session_id' => false, 	//リダイレクトに必ずセッションIDをつけます
			'imode_session_name' => 'csid', 		//iMODE時のセッション名を変更します
			'use_img_emoji' => true, 				//画像絵文字を使用
			'input_encoding'  => 'UTF-8', 			//入力をUTF-8に変更
			'output_encoding' => 'UTF-8', 			//出力をUTF-8に変更
		);
	var $cacheAction = array(
			'logout' => '6 hour'
			);
	
	/**
	 * インデックス画面表示
	 * http://設置URL/users
	 */
	function index(){
		//古いセッションを破棄
		$this->Session->delete('auth');
	}
	
	/**
	 * ログイン画面表示
	 * @param varchar $login_id ログイン用のランダム文字列
	 * http://設置URL/users/login
	 */
	function login($login_id = null, $page = null) {
		if ($login_id === null) {
			$this->redirect("/");
		}
		
		//古いセッションを破棄
		$this->Session->delete('auth');

		//ブラックリストに存在しているかチェック（3回以上でホーム画面へ）
		$this->address = ClassRegistry::init('Address');
//		$ipaddress = $this->RequestHandler->getClientIP();
		$ipaddress = $_SERVER["REMOTE_ADDR"];
		$param = array(
			'conditions' => array(
				'ipaddress' => $ipaddress
			)
		);
		$data = $this->address->find('all', $param);
		if ($data !== false) {
			$count = count($data);
			if ($count >= 5){
				$this->redirect('/');
				return;
			}
		}

		//URLから渡された引数を元にユーザーデータの検索
		$params = array(
			'conditions' => array(
				'login_id =' => $login_id
			)
		);
		
		$data = $this->User->find('first', $params);
		
		//データが存在しない場合はブラックリストに追加してホーム画面へ
		if ($data === false) {
			$data = array(
				'Address' => array(
					'id' => null,
					'ipaddress' => $ipaddress,
					'login_id' => $login_id
					)
				);
			$this->address->create();
			$this->address->save($data,false);
			
			$this->set("login_error", true);
			return;
		}
		
		//既存データがある時上書きする(modifiedを更新するため)。
		if ($data !== false) {
			$this->User->id = intval($data['User']['id']);
			$data = array(
				'User' => array(
					'screen_name' => $data['User']['screen_name'],
					)
				);
			$this->User->save($data,false);
		}
		
		//セッションにログイン情報を格納する
		$this->Session->renew();
		$this->Session->write("auth", $data['User']);
		
		//エラー処理
		if ($page === "replies") {
			$this->redirect('/twitters/replies');
		} elseif($page === "matomes"){
			$this->redirect('/matomes');
		} else {
			$this->redirect('/twitters');
		}
	}
	
	/**
	 * ログアウト処理
	 * http://設置URL/users/logout/
	 */
	function logout() {
		//セッションの破棄
		$this->Session->delete("auth");
	}
	
	/**
	 * ユーザー追加処理
	 * http://設置URL/users/add/
	 */
	function add() {
		//本番用
		$requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'http://twitter.com/oauth/request_token', 'http://www.example.com/users/twitter_callback');
		//ローカル用
//		$requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'http://twitter.com/oauth/request_token', 'http://localhost/cake/users/twitter_callback');
		$this->Session->write('twitter_request_token', $requestToken);
		$this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
	}
	
	/**
	 * twitterからのコールバック用関数
	 * http://設置URL/users/twitter_callback/
	 */
	public function twitter_callback() {
		if (!empty($this->params['url']['denied']))
		{
			$this->Session->setFlash('拒否されました');
			$this->redirect('/');
		}
		$requestToken = $this->Session->read('twitter_request_token');
		$accessToken = $this->OauthConsumer->getAccessToken('Twitter', 'http://twitter.com/oauth/access_token', $requestToken);
		if (empty($accessToken)) $this->redirect('/');
		$xml = $this->OauthConsumer->get('Twitter', $accessToken->key, $accessToken->secret, 'http://twitter.com/account/verify_credentials.xml', array());
		$xml = simplexml_load_string($xml);
		$screen_name = $xml->screen_name;
		settype($screen_name, "string");
		
		$login_id = $this->_getRandomString(8);
		
		//古いデータがあるかユーザーデータの検索
		$param = array(
			'conditions' => array(
				'access_token_secret' => $accessToken->secret
			)
		);
		
		$data = $this->User->find('first', $param);
		
		//同じlogin_idがあったら作り直し
		while ($data['User']['login_id'] === $login_id) {
			$login_id = $this->_getRandomString(8);
		}
		
		//既存データがある時上書きする。
		if ($data !== false) {
			$this->User->id = intval($data['User']['id']);
			$data = array(
				'User' => array(
					'login_id' => $login_id,
					'screen_name' => $screen_name,
					'access_token' => $accessToken->key,
					'access_token_secret' => $accessToken->secret
					)
				);
			$this->User->save($data,false);
			//url表示関数に飛ばす
			$this->redirect('/users/url/'.$login_id);
			return;
		}

		//既存データがなかった場合ユーザ情報を登録(ユーザーの追加をしない時はリダイレクトまでコメントアウトする)
		$data = array(
			'User' => array(
				'id' => null,
				'login_id' => $login_id,
				'screen_name' => $screen_name,
				'access_token' => $accessToken->key,
				'access_token_secret' => $accessToken->secret,
				'icon' => 1
				)
			);
		$this->User->create();
		$this->User->save($data,false);
		//エラー処理
		$this->log("twitterコールバックエラー");
		
		//url表示関数に飛ばす
		$this->redirect('/users/url/'.$login_id);
	}
	
	/**
	 * ランダムな文字列を生成する。
	 * @param int $nLengthRequired 必要な文字列長。省略すると 8 文字
	 * @return String ランダムな文字列
	 */
	function _getRandomString($nLengthRequired = null){
		$i = 0;
		$kaisuu_hantei = true;
		while($kaisuu_hantei){
			$sCharList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";
			mt_srand();
			$sRes = "";
			for($i = 0; $i < $nLengthRequired; $i++)
				$sRes .= $sCharList{mt_rand(0, strlen($sCharList) - 1)};
			
			//生成した$login_idを元にユーザーデータの検索
			$params = array(
				'conditions' => array(
					'login_id =' => $sRes
				)
			);
			$data = $this->User->find('first', $params);
			//データが存在しない場合は登録する
			if ($data === false) {
				return $sRes;
			}
			
			//データが存在した場合ループ（3回でブレイク。indexに戻る）
			$i++;
			if ($i > 3) {
				$this->redirect('index');
				return;
			}
		}
	}
	
	/**
	 * ログイン用URLを表示する。
	 * @param varchar $login_id ログイン用のランダム文字列
	 * @return String ランダムな文字列
	 */
	function url($login_id = null) {
		if ($login_id === null){
			$this->redirect('index');
			return;
		}
		//エラー処理
		$this->log("ログインURL表示エラー");
		//ビューに値を渡す
		$this->set("login_id", $login_id);
	}
	
	/**
	 * 設定を保存する
	 * http://設置URL/users/savesetting
	 */
	function savesetting(){
		$auth = $this->Session->read("auth");
		if (!isset($auth['login_id'])) {
			$this->redirect('/twitters/');
		}
		//登録するデータ配列
		$data = array(
			'User' => array(
				'id' => $auth['id'],
				'icon' => $this->data['User']['icon'],
				'count' => $this->data['User']['count'],
				'footer' => $this->data['User']['footer']
				)
			);
		$params = array(
			'fieldList' => array('icon', 'count', 'footer')
		);
		$result = $this->User->save($data, $params);
		
		//セッションの更新
		//$login_idを元にユーザーデータの検索
		$params = array(
			'conditions' => array(
				'login_id =' => $auth['login_id']
			)
		);
		
		$data = $this->User->find('first', $params);
		
		//データが存在しない場合はインデックス画面へ
		if ($data === false) {
			$this->set("login_error", true);
			$this->redirect('/users/');
			return;
		}
		
		//古いセッションを破棄する
		$this->Session->delete("auth");
		
		//セッションにログイン情報を格納する
		$this->Session->renew();
		$this->Session->write("auth", $data['User']);
		//エラー処理
		$this->log("設定保存エラー");
		$this->redirect("/twitters");
	}
	
	/**
	 * 設定編集画面（設定の保存はsavesettingでしている）
	 * http://設置URL/users/setting
	 */
	function setting(){
		
	}
	
	/**
	 * フォロー/フォロワー管理
	 * http://設置URL/users/followers
	 */
	 //pageはいずれ廃止になるそうです。cursorは戻り値がエラーを吐いたりするのでどうしたものか。
/*
	function followers(){
		$auth = $this->Session->read("auth");
		if (!isset($auth['screen_name'])) {
			$this->redirect('/twitters/');
		}
		//フォロー/フォロワー数を取得
		$xml = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], 'http://twitter.com/account/verify_credentials.xml', array());
		$xml = simplexml_load_string($xml);
		$screen_name = $xml->screen_name;
		settype($screen_name, "string");
		
		$friends_count = $xml->friends_count;
		$followers_count = $xml->followers_count;
		
		$friends_count = $friends_count/100;
		$friends_count = floor($friends_count);
		$followers_count = $followers_count/100;
		$followers_count = floor($followers_count);

		$nowtime = time();
//pageを使う時
		//API呼び出し
		$url = "http://twitter.com/statuses/friends/".$screen_name.".xml";
		$page = 1;
		$friends_array = array();
		$sinin = array();
		$array = array();
		$i = $j = 0;
		for($a = 0; $a < $friends_count; $a++){
			$friends = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("page" => $page));
			$friends = simplexml_load_string($friends);
			
			foreach ($friends->user as $val){
				$timestamp = $val->status->created_at;
				$timestamp = strtotime($timestamp);
				$jikan_sa = $nowtime - $timestamp;
				if ($jikan_sa < 15552000) {					//6ヶ月以内
					$friends_array[$i]['screen_name'] = $val->screen_name;
					++$i;
				} else {
					$sinin[$j]['screen_name'] = $val->screen_name;
					$sinin[$j]['time'] = $jikan_sa;
					++$j;
				}
				$name = $val->screen_name;
				settype($name, "string");
				$array[] = $name;
			}
			++$page;
		}

		$url = "http://twitter.com/statuses/followers/".$screen_name.".xml";
		$page = 1;
		$followers_array = array();
		$kataomoware = array();
		$i = $j = 0;
		for($a = 0; $a < $followers_count; $a++){
			$followers = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("page" => $page));
			$followers = simplexml_load_string($followers);
			
			foreach ($followers->user as $val){
				$name = $val->screen_name;
				settype($name, "string");
				$key = array_search($name, $array);
				if ($key === false) {
					$kataomoware[$j]['screen_name'] = $val->screen_name;
					++$j;
				} else {
					$followers_array[$i]['screen_name'] = $val->screen_name;
					++$i;
				}
				reset($array);
			}
			++$page;
		}
//cursorを使う時
		//API呼び出し
		$url = "http://twitter.com/statuses/friends/".$screen_name.".xml";
		$cursor = -1;
		$friends_array = array();
		$sinin = array();
		$array = array();
		$i = $j = 0;
		for($a = 0; $a < $friends_count; $a++){
			$friends = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("cursor" => $cursor));
			if (!simplexml_load_string($friends)){
				sleep(10);
				$friends = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("cursor" => $cursor));
				$friends = simplexml_load_string($friends);
			} else {
				$friends = simplexml_load_string($friends);
			}
			
			foreach ($friends->users->user as $val){
				$timestamp = $val->status->created_at;
				$timestamp = strtotime($timestamp);
				$jikan_sa = $nowtime - $timestamp;
				if ($jikan_sa < 15552000) {					//6ヶ月以内
					$friends_array[$i]['screen_name'] = $val->screen_name;
					++$i;
				} else {
					$sinin[$j]['screen_name'] = $val->screen_name;
					$sinin[$j]['time'] = $jikan_sa;
					++$j;
				}
				$name = $val->screen_name;
				settype($name, "string");
				$array[] = $name;
			}
			$cursor = $friends->next_cursor;
			settype($cursor, "string");
			sleep(2);
		}

		$url = "http://twitter.com/statuses/followers/".$screen_name.".xml";
		$cursor = -1;
		$followers_array = array();
		$kataomoware = array();
		$i = $j = 0;
		for($a = 0; $a < $followers_count; $a++){
			$followers = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("cursor" => $cursor));
			if (!simplexml_load_string($followers)){
				sleep(10);
				$followers = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("cursor" => $cursor));
				$followers = simplexml_load_string($followers);
			} else {
				$followers = simplexml_load_string($followers);
			}
			
			foreach ($followers->users->user as $val){
				$name = $val->screen_name;
				settype($name, "string");
				$key = array_search($name, $array);
				echo "<br />";
				if ($key === false) {
					$kataomoware[$j]['screen_name'] = $val->screen_name;
					++$j;
				} else {
					$followers_array[$i]['screen_name'] = $val->screen_name;
					++$i;
				}
				reset($array);
			}
			$cursor = $followers->next_cursor;
			settype($cursor, "string");
			sleep(2);
		}

		
		$this->set("friends", $friends_array);
		$this->set("followers", $followers_array);
		$this->set("sinin", $sinin);
		$this->set("kataomoware", $kataomoware);
	}
*/
}