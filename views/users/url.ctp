<?php if( !$ktai->is_ktai() ){ ?>
<div style="width:880px;margin:auto;">
<?php echo $html->image('top.gif');?>
</div>
<div id="intro" style="width:840px;margin:auto;text-align:left; background-color:#ffffff; padding:50px 20px 20px 20px;">
<?php } else { ?>
<div id="intro" style="margin:auto;text-align:left; background-color:#ffffff;">
<?php } ?>
<div style="text-align: center">
下のURLがあなた専用のログインページです<br />
URLは他人に教えないようにしてください<br />
このページをブックマークすることをお薦めします
<br />
<br />
<p>どうもけーついでAPIが使えなくなっているようです。<br />
申し訳ありませんが他のtwitterツールを使用してください。</p>
<br />
<br />
<?php
echo $html->link('タイムラインへ', "/users/login/".$login_id);
?>
<br /><br />
<?php
echo $form->text('', array("value" => "http://www.example.com/users/url/".$login_id, "size" => "60"));
?>
<br />
<?php
if( !$ktai->is_ktai() ){
	echo $ktai->get_qrcode("http://www.example.com/users/url/".$login_id,$options = array('width' => 220,'height' => 220,'margin' => 0.1));
} ?>
<h4>ツール</h4>
<?php echo $html->link("発言するだけ", "/users/login/".$login_id."/replies"); ?>
<?php if( !$ktai->is_ktai() ){ ?>
<br /><br />
<span style=""><strong>パソコンだけで使えるツール</strong></span><br />
<?php echo $html->link($html->image('matome_title.gif'), "/users/login/".$login_id."/matomes",null,null,false); ?>
<?php } ?>
</div>
<br />
<div style="text-align: center">
<a href="/history.html">更新履歴</a><br />
<a href="https://ssl.form-mailer.jp/fms/df5d9ff778085">ご意見ご感想</a>
</div>
<?php echo $this->renderElement("footer"); ?>
</div>
</div>
<?php if( !$ktai->is_ktai() ){ ?>
<div style="width:880px;margin:auto;">
<?php echo $html->image('bottom.gif');?>
</div>
<?php } ?>