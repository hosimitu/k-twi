	<div id="page">
		<a href="/matomes"><img src="/img/matome_title.gif" alt="twitterまとめツール" style="border:none;" /></a>
		<div class="container">
			<p>ステータスURLを入力してね</p>
			<?php echo $form->create("", array('action' => 'index')); ?>
			<p><?php echo $form->textarea('input_text', array('rows'=>"15", 'cols' =>"80", 'div' => false )); ?></p>
			<p><?php echo $form->input('haba', array('label'=>array('text'=>'テーブル幅 ： '),'value' => $haba, 'size'=>"5", 'title'=>"空欄もOK",'div' => false)); ?>　
			<?php echo $form->submit('　実 行　', array('div' => false)); ?>　
			<?php echo $form->checkbox('sort', array('value' => "sort", 'title' => "チェックしていると発言時間順でソートする",'checked' => $sort)); ?>ソートする　
			<?php echo $form->checkbox('idcheck', array('value' => "idcheck", 'title' => "IDを表示する",'checked' => $idcheck)); ?>IDを表示する　
			<?php echo $form->checkbox('namecheck', array('value' => "namecheck", 'title' => "名前を表示する",'checked' => $namecheck)); ?>名前を表示する　
			<?php echo $form->checkbox('timecheck', array('value' => "timecheck", 'title' => "発言時間を取得する",'checked' => $timecheck)); ?>発言時間を取得する</p>
			<?php echo $form->hidden('API_nokori', array('value'=>$API_nokori)); ?>
			<?php echo $form->end() ?>
			<?php echo($out_text); ?>
			<?php echo($tag); ?>
		</div>
		<div class="sidebar">
			<?php echo $html->image('matome_top.gif');?>
			<div class="con">
				あなたのAPI残数は<font style="color:blue; font-size: 16pt;"><strong><?php echo $API_nokori;?></strong></font>です。<br />
				1時間当たりの使用回数は150です。
				<br /><br />
				<h2>使い方</h2>
				<p>1.ステータスURLを貼り付ける。<br />ステータスURLとは『http://twitter.com/hosimitu/statuses/1620185105』みたいなのです。</p>
				<p>2.実行ボタンを押す。</p>
				<p>3.生成されたコードを自分のブログ等に貼り付ける。</p>
				<h2>特徴</h2>
				<p>ドラッグ＆ドロップで並び替えが出来ます。<br />重複した発言は自動で除去されます。</p>
				<br />
				<h2>リンク</h2>
				<p><strong><a href="http://www.hosimitu.com/twitter/stotreblog.htm" target="_blank">STOT to HTML</a></strong><br />APIを使わないまとめツール<br />STOT形式を使う</p>
				<p><strong><a href="https://ssl.form-mailer.jp/fms/df5d9ff778085" title="お問い合わせ" rel="nofollow">お問い合わせ</a></strong></p>
			</div>
			<?php echo $html->image('matome_bottom.gif');?>
		</div>
	</div>
	<div class="footer">　</div>
	
	<script type="text/javascript" src="./js/jquery.js" ></script>
	<script type="text/javascript" src="./js/jquery.tablednd.js" ></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
		    $("#preview").tableDnD();
		});
	<!--
		function hanei() {
			var area = document.getElementById("area");
			var sample = document.getElementById("sample");
			var moto = sample.innerHTML;
			moto =	moto.replace(/<table id="preview" /,'<table ');
			moto =	moto.replace(/<tr class="" style="cursor: move;">/g,'<tr>');
			moto =	moto.replace(/<tr style="cursor: move;">/g,'<tr>');
			moto =	moto.replace(/<\/tr><tr/gm,'</tr>\n<tr');
			moto =	moto.replace(/\n\n/gm,'\n');
			area.value = moto;
		};
	// -->
	</script>