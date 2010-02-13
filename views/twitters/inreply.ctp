<?php
echo "残りAPIは".$nokori."です<br />"
?>
<hr id="top" />
<br />
<?php
$ktai->emoji(0xE6E2);
echo $html->link('ホームへ', '/twitters', array('accesskey' => 1));
?>
<br />
<?php
$ktai->emoji(0xE6E3);
echo $html->link('リプライを取得', '/twitters/at', array('accesskey' => 2));
?>
<br />
<?php
$ktai->emoji(0xE6E4);
echo $html->link('DMを取得', '/twitters/direct', array('accesskey' => 3));
?>
<hr />
<?php echo $form->create("", array('action' => 'post')); ?>
<?php echo $form->input('mes', array('label'=>array('text'=>'いまどうしてる？'), 'div' => false )); ?>
<?php echo $form->input("in_reply_id", array("type" => "hidden", "value" => ""));?>
<?php echo $form->submit('投稿する', array('div' => false)); ?>
<?php echo $form->end() ?>
<?php /* if( $ktai->is_ktai() ){ ?>
<?php echo $this->renderElement("adsense"); ?>
<?php } */?>
<div style="text-align: left">
<?php if ($auth['icon'] === "1"){ ?>
<?php echo $html->image("http://img.tweetimag.es/i/".$timeline->user->id."_m", array('width'=>'32', 'height'=>'32')); ?>
<?php echo $html->link(h($timeline->user->screen_name), "/twitters/user/".$timeline->user->id); ?>
<br />
<?php
$text = preg_replace('/@(\w+)/', '@<a href="/user/$1">$1</a>', $timeline->text);
$text = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1">$1</a>$2', $text);
echo $text;
?>
<br /><small>
<?php echo h($time->format('Y/m/d H:i:s', $timeline->created_at)); ?></small><br />
<?php echo $html->link("Re", "/twitters/reply/".h($timeline->user->screen_name)."/".h($timeline->id)); ?>
<?php echo $html->link("☆", "/twitters/fav/".h($timeline->id)); ?>
<?php echo $html->link("RT", "/twitters/rt/".h($status->id)); ?> 
<?php if (intval($timeline->in_reply_to_status_id) > 1 ) {
echo "[";
echo $html->link("返事元", "/twitters/inreply/".h($timeline->in_reply_to_status_id));
echo "]";
}
?>
<hr />
<?php }elseif($auth['icon'] === "3"){ ?>
<?php echo $html->image("/img/default.gif", array('width'=>'32', 'height'=>'32')); ?>
<?php echo $html->link(h($timeline->user->screen_name), "/twitters/user/".$timeline->user->id); ?>
<br />
<?php
$text = preg_replace('/@(\w+)/', '@<a href="/twitters/user/$1">$1</a>', $timeline->text);
$text = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1">$1</a>$2', $text);
echo $text;
?>
<br /><small>
<?php echo h($time->format('Y/m/d H:i:s', $timeline->created_at)); ?></small><br />
<?php echo $html->link("Re", "/twitters/reply/".h($timeline->user->screen_name)."/".h($timeline->id)); ?>
<?php echo $html->link("☆", "/twitters/fav/".h($timeline->id)); ?>
<?php echo $html->link("RT", "/twitters/rt/".h($status->id)); ?> 
<?php if (intval($timeline->in_reply_to_status_id) > 1 ) {
echo "[";
echo $html->link("返事元", "/twitters/inreply/".h($timeline->in_reply_to_status_id));
echo "]";
}
?>
<hr />
<?php }else{ ?>
<?php echo $html->link(h($timeline->user->screen_name), "/twitters/user/".$timeline->user->id); ?>
<br />
<?php
$text = preg_replace('/@(\w+)/', '@<a href="/twitters/user/$1">$1</a>', $timeline->text);
$text = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1">$1</a>$2', $text);
echo $text;
?>
<br /><small>
<?php echo h($time->format('Y/m/d H:i:s', $timeline->created_at)); ?></small><br />
<?php echo $html->link("Re", "/twitters/reply/".h($timeline->user->screen_name)."/".h($timeline->id)); ?>
<?php echo $html->link("☆", "/twitters/fav/".h($timeline->id)); ?>
<?php echo $html->link("RT", "/twitters/rt/".h($status->id)); ?> 
<?php if (intval($timeline->in_reply_to_status_id) > 1 ) {
echo "[";
echo $html->link("返事元", "/twitters/inreply/".h($timeline->in_reply_to_status_id));
echo "]";
}
?>
<hr />
<?php } ?>
</div>
<?php echo $this->renderElement("footer"); ?>