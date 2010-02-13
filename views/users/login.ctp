<?php if( !$ktai->is_ktai() ){ ?>
<div style="width:880px;margin:auto;">
<?php echo $html->image('top.gif');?>
</div>
<div id="intro" style="width:840px;margin:auto;text-align:left; background-color:#ffffff; padding:50px 20px 20px 20px;">
<?php } else { ?>
<div id="intro" style="margin:auto;text-align:left; background-color:#ffffff;">
<?php } ?>
<div style="text-align: center">
ログインIDが有効ではありません。<br />
ログインIDを再度確認するか、twitter側で再度認証をしてください。<br /><br />
<?php if( !$ktai->is_ktai() ){
echo $html->link($html->image("login2.gif", array("style" => "border:none;")), '/users/add', null, null, false);
} else {
?>
twitter側での認証はパソコンからしか出来ません。<br />
パソコンから『<a href="http://www.example.com/">けーつい</a>』へアクセスしてください。
<?php } ?>
</div>
<br />
<?php echo $this->renderElement("footer"); ?>
</div>
</div>
<?php if( !$ktai->is_ktai() ){ ?>
<div style="width:880px;margin:auto;">
<?php echo $html->image('bottom.gif');?>
</div>
<?php } ?>