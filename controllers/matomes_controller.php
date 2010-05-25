<?php
class MatomesController extends AppController {
	var $name = "Matomes";
	var $uses = array('Toi');
	var $components = array('OauthConsumer', 'Ktai');
	var $helpers = array('Html', 'Form', 'Time');

	/**
	 * インデックス画面表示
	 * http://設置URL/matomes/
	 */
	function index() {
		//PHPのformから値を取得する
		$haba = $this->data['haba'];
		$sort = $this->data['sort'];
		$namecheck = $this->data['namecheck'];
		$timecheck = $this->data['timecheck'];
		$idcheck = $this->data['idcheck'];
		$input_text = $this->data['input_text'];
		$API_nokori = $this->data['API_nokori'];
		if (empty($input_text)) {
			//API残り調査
			$API_nokori = $this->_api_nokori();
			//Viewにセット
			$this->set("API_nokori",$API_nokori);
			$this->set("haba", "600");
			$this->set("sort", "checked");
			$this->set("namecheck", "");
			$this->set("timecheck", "");
			$this->set("idcheck", "checked");
			$this->set("out_text", "");
			$this->set("tag", "");
			$this->set("input_text", "");
			$this->set("css_specify", 'matome');
			return;
		}
		$auth = $this->Session->read("auth");
		
		if (empty($haba)) {
			$out_text = "<table>\n";
			$haba2 = "";
		} else {
			$out_text = '<table width="'.$haba.'">'."\n";
			$haba2 = " width:".$haba."px;";
		}
		
		if ($timecheck == "timecheck") {
			$timecheck = "checked";
		} else {
			$timecheck = "";
		}
		
		if ($namecheck == "namecheck") {
			$namecheck = "checked";
		} else {
			$namecheck = "";
		}
		
		if ($idcheck == "idcheck") {
			$idcheck = "checked";
		} else {
			$idcheck = "";
		}
		
		$in_text2 = $input_text;
		$input_text = nl2br($input_text);
		$textArray = explode( "\n", $input_text );
		
		$count = count($textArray);
		$textArray = array_unique($textArray);
		$matome_hituyousuu = $API_nokori - $count;
		$matome_ok = false;
		if ($count != 0) {
			if ($matome_hituyousuu >= 0){
				$matome_ok = true;
			}
		}

		$i = 0;
		if ($matome_ok){
			foreach ($textArray as $value) {
				$st = $ed = 0;
				$mes = trim($value);
				$mes = str_replace('<br />', '', $mes);
				$mes = $mes."]";
				$st = strpos($mes, 'twitter.com/', $ed);
				if($st===false) break;
				$ed = strpos($mes, '/sta', $st) or die("エラー発生。ステータスURLがおかしい可能性があります。");
				$line = substr($mes, $st, $ed-$st);
				preg_match('/twitter.com\/(.+)/', $line, $matches);
				$username = $matches[1];
				$st = strpos($mes, 'tus', $ed) or die("エラー発生。ステータスURLがおかしい可能性があります。");
				$ed = strpos($mes, ']', $st);
				$line = substr($mes, $st, $ed-$st);
		
				preg_match('/tuse?s?\/(.+)/', $line, $matches);
				$status_number = $matches[1];
		
				settype($status_number, "float");
				if($status_number > 0){
					$url = 'http://api.twitter.com/1/statuses/show/'.$status_number.'.xml';
					$timeline = $this->OauthConsumer->get('Twitter', $auth['access_token'], $auth['access_token_secret'], $url, array());
				}
				$xml = simplexml_load_string($timeline);
				$st = $ed = 0;
				$mes = $timeline;
				$error = "";
				if(strpos($mes, '<error>', $ed)){
					$st = strpos($mes, '<error>', $ed);
					$ed = strpos($mes, '</error>', $st);
					$line = substr($mes, $st, $ed-$st);
					preg_match('/<error>(.+)/', $line, $matches);
					$error = $matches[1];
				}
				if ($error === "Sorry, you are not authorized to see this status."){
					$j = $i + 1;
					print "プロテクトに人が含まれていたよ？（".$j."人目）";
				}elseif($error === "No status found with that ID."){
					$j = $i + 1;
					print "どうやら".$j."番目の発言が存在しないようです";
				} else {
					$st = $ed = 0;
					$created_at = $xml->created_at;
				    $status_number = $xml->id;
				    $naiyo = $xml->text;
				    $name = $xml->user->name;
					$screen_name = $xml->user->screen_name;
					settype($created_at, "string");
					settype($status_number, "string");
					settype($naiyo, "string");
					settype($name, "string");
					settype($screen_name, "string");
					$timestamp = date('Y-m-d H:i:s', strtotime($created_at));
					$timestamp = preg_replace('/\s/i', '<br />', $timestamp);
		
					$twitter_list[$i] = array("timestamp"=>$timestamp, "status_number"=>$status_number, "naiyo"=>$naiyo, "name"=>$name, "screen_name"=>$screen_name);
					$i++;
				}
			}
			
			if ($sort == "sort") {
				if(empty($twitter_list)){
				} else {
					foreach ($twitter_list as $key => $row) {
					    $sort_key[$key] = $row['status_number'];
					}
					array_multisort($sort_key, SORT_ASC, $twitter_list);
				}
				$sort = "checked";
			}
			
			for ($i = 0; $i < $count; $i++) {
				if( empty($twitter_list[$i][naiyo]) ) break;
				$twitter_list[$i][naiyo] = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1" target="_blank">$1</a>$2', $twitter_list[$i][naiyo]);
				$twitter_list[$i][naiyo] = preg_replace('/@(\w+)/', '@<a href="http://twitter.com/$1" target="_blank">$1</a>', $twitter_list[$i][naiyo]);
			}
			
			
			for ($i = 0; $i < $count; $i++) {
				if( empty($twitter_list[$i][screen_name]) ) break;
				$out_text = $out_text."<tr>";
				$out_text = $out_text.'<td><a href="http://twitter.com/'.$twitter_list[$i][screen_name].'"><img src="http://usericons.relucks.org/twitter/'.$twitter_list[$i][screen_name].'" height="32" width="32"></a></td>';
				
				if ($namecheck == "checked" || $idcheck == "checked" ) {
					$out_text = $out_text.'<td><a href="http://twitter.com/'.$twitter_list[$i][screen_name].'">';
					if($namecheck == "checked"){
						$out_text = $out_text.$twitter_list[$i][name]."<br />";
					}
					if($idcheck == "checked"){
						$out_text = $out_text.$twitter_list[$i][screen_name];
					}
					$out_text = $out_text.'</a></td>';
				}
				
				$out_text = $out_text.'<td>'.$twitter_list[$i][naiyo].'</td>';
				if ($timecheck == "checked") {
					$out_text = $out_text.'<td style="font-size: 12px; line-height: 90%;">'.$twitter_list[$i][timestamp].'</td>';
				}
				$out_text = $out_text.'<td><a href="http://twitter.com/'.$twitter_list[$i][username].'/status/'.$twitter_list[$i][status_number].'">link</a></td>';
				$out_text = $out_text."</tr>\n";
		
				$st = $ed = 0;
				$st = strpos($tag, $twitter_list[$i][screen_name], $ed);
				if ($st == 0){
					$tag .= "[".$twitter_list[$i][screen_name]."]";
				}
			}
			$out_text = $out_text."</table>\n";
			$preview = str_replace('<table', '<table id="preview" ', $out_text);			//プレビュー用コードを入れておく
		
			$out_text =	'<textarea id="area" rows="15" cols="80">'.$out_text."</textarea>\n<h3>プレビュー</h3>\n";
			$out_text .= '<input type="button" value="並び替えをコードに反映" id="update" onClick="hanei()" />';
			$out_text .= "<span>　　ドラッグ＆ドロップで並び替え出来ます</span>\n".'<div id="sample">'.$preview."</div>";
		
			if($tag != ""){
				$tag = '<div><h3>はてダ用タグ</h3><input onfocus="this.select()" value="'.$tag.'" size="130" /></div>';
			}
		
			if(empty($twitter_list)){
				$out_text = "";
			}
			//API残り調査
			$API_nokori = $this->_api_nokori();
		} else {
			$sort = " checked";
			if ($count > 1) {
				$out_text = "残APIではまとめきれません。1時間くらい経ってからお試しください。";
			} else {
				$out_text = "";
			}
			//Viewにセット
			$this->set("API_nokori",$API_nokori);
			$this->set("haba", $haba);
			$this->set("sort", "checked");
			$this->set("namecheck", $namecheck);
			$this->set("timecheck", $timecheck);
			$this->set("idcheck", $idcheck);
			$this->set("out_text", "");
			$this->set("tag", "");
			$this->set("preview", "");
			$this->set("input_textarea", $input_text);
			$this->set("css_specify", 'matome');
		}

		//Viewにセット
		$this->set("API_nokori",$API_nokori);
		$this->set("haba", $haba);
		$this->set("sort", $sort);
		$this->set("namecheck", $namecheck);
		$this->set("timecheck", $timecheck);
		$this->set("out_text", $out_text);
		$this->set("tag", $tag);
		$this->set("preview", $preview);
		$this->set("input_text", $input_text);
		$this->set("css_specify", 'matome');
	}
	
	/**
	 * API残りチェック
	 */
	function _api_nokori(){
		$auth = $this->Session->read("auth");
		
		//API残り調査
		$url = "http://api.twitter.com/1/account/rate_limit_status.xml";
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
}