<?php
class TwittersController extends AppController {
	var $name = "Twitters";
	var $components = array('OauthConsumer', 'Ktai');
	var $uses = array();
	var $needAuth = true;
	var $helpers = array('Html', 'Form', 'Time', 'Ktai', 'Css');
	var $ktai = array(							//携帯でのセッション等の設定
			'enable_ktai_session' => true, 			//セッション使用を有効にします
			'use_redirect_session_id' => false, 	//リダイレクトに必ずセッションIDをつけます
			'imode_session_name' => 'csid', 		//iMODE時のセッション名を変更します
			'use_img_emoji' => true, 				//画像絵文字を使用
			'input_encoding'  => 'UTF-8', 			//入力をUTF-8に変更
			'output_encoding' => 'UTF-8', 			//出力をUTF-8に変更
		);
	
	/**
	 * インデックス画面表示(フレンドタイムラインを取得)
	 * http://設置URL/twitters/
	 */
	function index() {
		$auth = $this->Session->read("auth");
		
		//フレンドタイムライン取得
		$count = $auth['count'];
		$url = "http://twitter.com/statuses/friends_timeline.xml";
		if ($count !== 20) {
			$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("count"=>$count));
		} else {
			$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
		}
		$timeline = simplexml_load_string($timeline);
		
		//API残り調査
		$API_nokori = $this->_api_nokori();
		
		$page = 1;
		
		//エラー処理
		$this->log("ホームTL表示エラー");
		//Viewにセット
		$this->set("screen_name",$auth['screen_name']);
		$this->set("timeline", $timeline);
		$this->set("nokori", $API_nokori);
		$this->set("page", $page);
	}
	/**
	 * APIの残り数をチェックする
	 */
	function _api_nokori(){
		$auth = $this->Session->read("auth");
		
		//API残り調査
		$url = "http://twitter.com/account/rate_limit_status.xml";
		$nokori = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
		$st = $ed = 0;
		$matches = array();
		$st = strpos($nokori, '<remaining-hits type="integer">', $ed);
//		if($st===false) break;
		$ed = strpos($nokori, '</remaining-hits>', $st);
		$line = substr($nokori, $st, $ed-$st);
		preg_match('/<remaining-hits type="integer">(.+)/', $line, $matches);
		return $matches[1];
	}
	
	/**
	 * twitterに投稿する
	 * http://設置URL/twitters/post
	 */
	function post(){
		if (empty($this->data['mes'])){
			$this->render("/twitters/post");
			return;
		}
		$auth = $this->Session->read("auth");
		$mes = $this->data['mes'].$auth['footer'];
		if (mb_strlen($mes,"UTF-8") > 140){
			$this->set("moji_suu", false);
			$this->set("value", $mes);
			$this->set("toukou_kanryo", true);
			$this->render("/twitters/post");
			return;
		}

		if ( ($this->data['mes'] !== null) && isset($this->data['in_reply_id']) ) {
			$req = $this->OauthConsumer->post('Twitter', $auth['access_token'], $auth['access_token_secret'], 'http://twitter.com/statuses/update.xml', array('status' => $mes, "in_reply_to_status_id" => $this->data['in_reply_id']) );
		} elseif( ($this->data['mes'] !== null) && !isset($this->data['in_reply_id']) ) {
			$req = $this->OauthConsumer->post('Twitter', $auth['access_token'], $auth['access_token_secret'], 'http://twitter.com/statuses/update.xml', array('status' => $mes) );
		} else {
			$this->redirect("/twitters");
		}
		//エラー処理
		$this->log("投稿エラー");
		$this->set("moji_suu", true);
		$this->set("value", "");
		$this->set("toukou_kanryo", true);
		$this->render("/twitters/post");
	}
	
	/**
	 * リプライを返す
	 * http://設置URL/twitters/reply
	 */
	function reply($user_id = null, $in_reply_id = null){
		if ($user_id === null) {
			$this->redirect("/twitters");
		}
		$auth = $this->Session->read("auth");
		$this->set("value", "@".$user_id." ");
		$this->set("in_reply_id", $in_reply_id);
		//エラー処理
		$this->log("リプライ画面表示エラー");
		$this->render("/twitters/reply");
	}
	
	/**
	 * 複数にリプライを返す
	 * http://設置URL/twitters/replies
	 */
	function replies() {
		$auth = $this->Session->read("auth");
		$mes = "";
		if ($this->data['replies'] !== null){
			$mes = ".";
			foreach ($this->data['replies'] as $key => $value){
				if ($value !== "0"){
					$mes .= "@".$value." ";
				}
			}
		}
		
		$this->set("value", $mes);
		$this->set("in_reply_id", null);
		$this->render('/twitters/replies');
	}
	
	/**
	 * ふぁぼる
	 * http://設置URL/twitters/fav
	 */
	function fav($status_number = null){
		$auth = $this->Session->read("auth");
		if ($status_number !== "") {
			$req = $this->OauthConsumer->post('Twitter', $auth['access_token'], $auth['access_token_secret'], 'http://twitter.com/favorites/create/'.$status_number.'.xml' );
		} else {
			$this->redirect("/twitters");
		}
	}
	
	/**
	 * RT
	 * http://設置URL/twitters/rt
	 */
	function  rt($status_number = null){
		if ($status_number === null) {
			$this->redirect("/twitters");
		}

		$auth = $this->Session->read("auth");
		$url = "http://twitter.com/statuses/retweet/".$status_number.".xml";
		$req = $this->OauthConsumer->post('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array() );
	}
	
	/**
	 * ユーザー個別のTLを表示
	 * http://設置URL/twitters/user
	 */
	function user($user_id = null, $page = 1){
		if ( ($user_id === null) && ($this->data['user_id'] !== null) ) {
			$user_id = $this->data['user_id'];
		}
		if ($user_id === null) {
			$this->redirect("/twitters");
		}
		if (isset($this->data["page"])) {
			$page = $this->data["page"];
		}
		$auth = $this->Session->read("auth");
		
		//ユーザー情報を取得
		$url = "http://twitter.com/users/show/".$user_id.".xml";
		$show = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
		$show = simplexml_load_string($show);
		
		//ユーザー個別のTLを取得
		$url = "http://twitter.com/statuses/user_timeline/".$user_id.".xml";
		$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("page" => $page));
		$timeline = simplexml_load_string($timeline);

		//API残り調査
		$API_nokori = $this->_api_nokori();
		//エラー処理
		$this->log("個別ユーザー表示エラー");
		//Viewにセット
		$this->set("show", $show);
		$this->set("timeline", $timeline);
		$this->set("user_id", $user_id);
		$this->set("nokori", $API_nokori);
		$this->set("page", $page);
	}
	
	/**
	 * ＠一覧を表示
	 * http://設置URL/twitters/at
	 */
	function at($page = 1){
		$auth = $this->Session->read("auth");
		//リプライズ取得
		if (isset($this->data["page"])) {
			$page = $this->data["page"];
		}
		$url = "http://twitter.com/statuses/replies.xml";
		$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("page" => $page));
		$timeline = simplexml_load_string($timeline);
		
		//API残り調査
		$API_nokori = $this->_api_nokori();
		//エラー処理
		$this->log("＠表示エラー");
		//Viewにセット
		$this->set("timeline", $timeline);
		$this->set("nokori", $API_nokori);
		$this->set("page", $page);
	}
	
	/**
	 * ダイレクトメッセージ一覧を表示
	 * http://設置URL/twitters/direct
	 */
	function direct($page = 1){
		$auth = $this->Session->read("auth");
		//リプライズ取得
		if (isset($this->data["page"])) {
			$page = $this->data["page"];
		}
		$url = "http://twitter.com/direct_messages.xml";
		$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("page" => $page));
		$timeline = simplexml_load_string($timeline);
		
		//API残り調査
		$API_nokori = $this->_api_nokori();
		//エラー処理
		$this->log("DM表示エラー");
		//Viewにセット
		$this->set("timeline", $timeline);
		$this->set("nokori", $API_nokori);
		$this->set("page", $page);
	}
	
	/**
	 * ユーザーをフォローする
	 * http://設置URL/twitters/follow
	 */
	function follow($user_id = null) {
		if ($user_id === null) {
			$this->redirect("/twitters");
		}
		
		$auth = $this->Session->read("auth");
		
		//フォローする
		$url = "http://twitter.com/friendships/create/".$user_id.".xml";

		$timeline = $this->OauthConsumer->post('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
		//エラー処理
		$this->log("フォロー出来なかったエラー");
		//homeへリダイレクト
		$this->redirect("/twitters");
	}
	
	/**
	 * ○ページ目を表示する
	 * http://設置URL/twitters/page
	 */
	function page($page = 1){
		$auth = $this->Session->read("auth");
		//リプライズ取得
		if (isset($this->data["page"])){
			$page = $this->data["page"];
		}
		$url = "http://twitter.com/statuses/friends_timeline.xml";
		$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("page" => $page));
		$timeline = simplexml_load_string($timeline);
		
		//API残り調査
		$API_nokori = $this->_api_nokori();
		//エラー処理
		$this->log("過去ページ表示エラー");
		//Viewにセット
		$this->set("screen_name",$auth['screen_name']);
		$this->set("timeline", $timeline);
		$this->set("nokori", $API_nokori);
		$this->set("page", $page);
	}
	
	/**
	 * in_reply_toを表示する
	 * http://設置URL/twitters/inreply
	 */
	function inreply($status_id = null){
		if ($status_id === null) {
			$this->redirect("/twitters");
		}
		
		$auth = $this->Session->read("auth");
		//リプライズ取得
		$url = "http://twitter.com/statuses/show/".$status_id.".xml";
		$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
		$timeline = simplexml_load_string($timeline);
		
		//API残り調査
		$API_nokori = $this->_api_nokori();
		//エラー処理
		$this->log("in_reply_to表示エラー");
		//Viewにセット
		$this->set("timeline", $timeline);
		$this->set("nokori", $API_nokori);
	}
	
	/**
	 * インデックス画面で大量に表示する
	 * http://設置URL/twitters/count
	 */
	function count($count = 20) {
		$auth = $this->Session->read("auth");
		
		//フレンドタイムライン取得
		if (isset($this->data["count"])){
			$count = $this->data["count"];
		}
		$url = "http://twitter.com/statuses/friends_timeline.xml";
		if ($count !== 20) {
			$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array("count"=>$count));
		} else {
			$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
		}
		$timeline = simplexml_load_string($timeline);
		
		//API残り調査
		$API_nokori = $this->_api_nokori();
		
		$page = 1;
		
		//エラー処理
		$this->log("ホームTL表示エラー");
		//Viewにセット
		$this->set("screen_name",$auth['screen_name']);
		$this->set("timeline", $timeline);
		$this->set("nokori", $API_nokori);
		$this->set("page", $page);
		$this->render("/twitters/index");
	}
}