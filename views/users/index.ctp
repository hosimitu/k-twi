<?php if( !$ktai->is_ktai() ){ ?>
<div class="arrow"></div>
<div style="width:880px;margin:auto;">
<?php echo $html->image('top.gif');?>
</div>
<div id="intro" style="width:800px;margin:auto;text-align:left; background-color:#ffffff; padding:20px 25px 20px 55px;">
<p>
	この『k-twi/けーつい』は携帯でtwitterをするために開発中のウェブアプリです。<br />
	外出時に使う事を想定し、TLの確認やリプライ等の『使う』部分に集中しています。<br />
	コンセプトは、なるべくページを軽量化する、です。アイコンは非表示がオススメ。<br />
	こうする理由は、使いやすさは下がるかも知れないがパケ死を防ぎたいからです。<br />
	
</p>
<div style="float:right;">
	<div style="font-weight:bold; font-size:16px; padding:3px; border:1px solid; text-align:center;">
	サンプル<br />
	<?php echo $html->image("/img/home.gif", array('alt'=>'サンプル')) ?>
	</div>
</div>
<div style="font-weight:bold; font-size: 16px;">今できること</div>
	<ul>
		<li>ホームのTLが取得できる</li>
		<li>発言できる</li>
		<li>ふぁぼれる</li>
		<li>自分宛の＠が見れる</li>
		<li>複数人相手に＠が送れる</li>
		<li>自分宛のDMが見れる（送信はまだ）</li>
		<li>特定ユーザーのページが見れる</li>
		<li>アイコンの表示・非表示が切り替えられる</li>
		<li>アイコンは軽量化後キャッシュされたものを使用</li>
		<li>残りAPI数を表示する</li>
	</ul>
<br />
<div style="font-weight:bold; font-size: 16px; color:red;">注意事項</div>
<p>
	まだまだ開発途中です。( ･ω･)っ<a href="http://github.com/hosimitu/k-twi">http://github.com/hosimitu/k-twi</a><br />
	docomoしかテスト出来ていません。<br />
	twitter中毒の人はパケット定額に入らないとキツいかも知れません。<br />
	また突然アクセス不能になったりする可能性があります（レンタルサーバーなので）。<br />
	使用にあたって当アプリ側にOAuth認証トークンが記録されます。<br />
	ですのでユーザーの方でアカウント乗っ取りの対策も取る事ができ非常に安心です。<br />
	一度登録した方はログインURLの再発行も可能です。
</p>
<p>
	それでは下のボタンをクリックしてtwitterの世界へ
</p>
<?
echo $html->link($html->image("Sign-in-with-Twitter-lighter.png", array("style" => "border:none;")), '/users/add', null, null, false);
?>
<p>
	開発は@<?php echo $html->link("hosimitu", 'http://twitter.com/hosimitu');?>が行っています。<br />
	CakePHPで開発しています。<br />
	感想や不具合報告などお待ちしています。
	<?php echo $html->link("感想・不具合報告フォームへ","https://ssl.form-mailer.jp/fms/df5d9ff778085") ?>
</p>
<p>
	名前の由来は、携帯でtwitter、略して『k-twi』です。<br />
	また読み方は『けーつい』で、CakePHPとも若干かかっていたり。<br />
	『<?php echo $html->link("Ｑ＆Ａ","/qanda.html") ?>』も用意しました。
</p>
<div style="font-weight:bold; font-size: 16px;">今後の予定</div>
	<ul>
		<li>UI周りを綺麗にする</li>
		<li>小ネタアプリの集合体をめざす</li>
		<li>DMの送信ができるようにする</li>
		<li>ページを軽くする</li>
		<li>フォロー管理ができるようにする</li>
		<li>負荷を計測する</li>
		<li>一緒に開発してくれる人を探す</li>
	</ul>
<br />
<?php echo $this->renderElement("footer"); ?>
<div style="text-align:center; font-size: 12px;">
<?php echo $html->link("Ｑ＆Ａはこちら", '/qanda.html');?>　開発者 @<?php echo $html->link("hosimitu", 'http://twitter.com/hosimitu');?>
</div>
</div>
<div style="width:880px;margin:auto;">
<?php echo $html->image('bottom.gif');?>
</div>
<?php } else {					//携帯の時のページ生成 ?>
<div id="intro" style="margin:auto; background-color:#ffffff; text-align:left; padding:2px 2px 2px 2spx;">
<p>
	この『k-twi/けーつい』は携帯でtwitterをするために開発中のウェブアプリです。<br />
	外出時に使う事を想定し、TLの確認やリプライ等の『使う』部分に集中しています。<br />
	コンセプトは、なるべくページを軽量化する、です。<br />
	こうする理由は、使いやすさは下がるかも知れないがパケ死を防ぎたいからです。
</p>
<div style="font-weight:bold; font-size: 16px;">今できること</div>
	<ul>
		<li><S>ホームのTLが取得できる</S></li>
		<li>発言できる</li>
		<li>ふぁぼれる</li>
		<li>自分宛の＠が見れる</li>
		<li>複数人相手に＠が送れる</li>
		<li>自分宛のDMが見れる（送信はまだ）</li>
		<li>特定ユーザーのページが見れる</li>
		<li>アイコンの表示・非表示が切り替えられる</li>
		<li>アイコンは軽量化後キャッシュされたものを使用</li>
		<li>残りAPI数を表示する</li>
	</ul>
<p><?php echo $html->link("サンプル","/sample.html") ?></p>
<br />
<div style="font-weight:bold; font-size: 16px; color:red;">注意事項</div>
<p>
	まだまだ開発途中です。<br />
	docomoしかテスト出来ていません。<br />
	突然アクセス不能になったりする可能性があります（レンタルサーバーなので）。<br />
	またtwitter中毒の人はパケット定額に入らないとキツいかも知れません。<br />
	使用にあたって当アプリ側にOAuth認証トークンが記録されます。<br />
	ですのでユーザーの方でアカウント乗っ取りの対策も取る事ができ非常に安心です。<br />
	一度登録した方はログインURLの再発行も可能です。
</p>
<p>
	それでは下のボタンをクリックしてtwitterの世界へ
</p>
<p>
	・・・と言いたい所なんですが、どうやら<span style="color:red;">携帯ではOAuth認証が出来ないようです</span>。<br />
	パソコンを使って認証してください。
</p>
<p>
	開発は@<?php echo $html->link("hosimitu", 'http://twitter.com/hosimitu');?>が行っています。<br />
	CakePHPで開発しています。<br />
	感想等お待ちしてます。<br />
	<?php echo $html->link("感想・不具合報告フォームへ","https://ssl.form-mailer.jp/fms/df5d9ff778085") ?>
</p>
<p>
	名前の由来は、携帯でtwitter、略して『k-twi』です。<br />
	また読み方は『けーつい』で、CakePHPとも若干かかっていたり。
</p>
<?php echo $this->renderElement("footer"); ?>
</div>
<?php } ?>