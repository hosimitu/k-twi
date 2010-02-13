<?php
echo "残りAPIは".$nokori."です<br />"
?>
<hr id="top" />
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
<br />
<?php
$ktai->emoji(0xE6E5);
echo $html->link('設定を変更', '/users/setting', array('accesskey' => 4));
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
<?php echo $form->create("", array('action' => 'replies')); $i = 0;?>
<?php if ($auth['icon'] === "1"){ ?>
	<?php foreach ($timeline as $status): ?>
	<?php if(($i%2) === 0){ ?>
	<div style="background-color:#e0ffff";>
	<?php }else{ ?>
	<div>
	<?php }?>
	<?php echo $html->image("http://img.tweetimag.es/i/".$status->user->id."_m", array('width'=>'32', 'height'=>'32')); ?>
	<?php echo $html->link(h($status->user->screen_name), "/twitters/user/".$status->user->id); ?>
	<br />
	<?php if (preg_match("/".$screen_name."/", $status->text)) { ?>
	<span style="color:#ff3300;">
	<?php } ?>
	<?php
	$text = preg_replace('/@(\w+)/', '@<a href="/twitters/user/$1">$1</a>', $status->text);
	$text = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1">$1</a>$2', $text);
	echo $text;
	?>
	<?php if (preg_match("/".$screen_name."/", $status->text)) { ?>
	</span>
	<?php } ?>
	<br /><small>
	<?php echo h($time->format('Y/m/d H:i:s', $status->created_at)); ?></small><br />
	<?php echo $html->link("Re", "/twitters/reply/".h($status->user->screen_name)."/".h($status->id)); ?> 
	<?php echo $html->link("☆", "/twitters/fav/".h($status->id)); ?> 
	<?php echo $html->link("RT", "/twitters/rt/".h($status->id)); ?> 
	<?php  echo '<input type="checkbox" name="data[replies]['.$i.']" value="'.h($status->user->screen_name).'" id="replies'.$i.'" />'; $i++; ?>
	<?php if (intval($status->in_reply_to_status_id) > 1 ) {
	echo "[";
	echo $html->link("返事元", "/twitters/inreply/".h($status->in_reply_to_status_id));
	echo "]";
	}
	?>
	</div>
	<hr />
	<?php endforeach; ?>
<?php }elseif($auth['icon'] === "3"){ ?>
	<?php foreach ($timeline as $status): ?>
	<?php if(($i%2) === 0){ ?>
	<div style="background-color:#e0ffff";>
	<?php }else{ ?>
	<div>
	<?php }?>
	<?php echo $html->image("/img/default.gif", array('width'=>'32', 'height'=>'32')); ?>
	<?php echo $html->link(h($status->user->screen_name), "/twitters/user/".$status->user->id); ?>
	<br />
	<?php if (preg_match("/".$screen_name."/", $status->text)) { ?>
	<span style="color:#ff3300;">
	<?php } ?>
	<?php
	$text = preg_replace('/@(\w+)/', '@<a href="/twitters/user/$1">$1</a>', $status->text);
	$text = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1">$1</a>$2', $text);
	echo $text;
	?>
	<?php if (preg_match("/".$screen_name."/", $status->text)) { ?>
	</span>
	<?php } ?>
	<br /><small>
	<?php echo h($time->format('Y/m/d H:i:s', $status->created_at)); ?></small><br />
	<?php echo $html->link("Re", "/twitters/reply/".h($status->user->screen_name)."/".h($status->id)); ?> 
	<?php echo $html->link("☆", "/twitters/fav/".h($status->id)); ?> 
	<?php echo $html->link("RT", "/twitters/rt/".h($status->id)); ?> 
	<?php  echo '<input type="checkbox" name="data[replies]['.$i.']" value="'.h($status->user->screen_name).'" id="replies'.$i.'" />'; $i++; ?>
	<?php if (intval($status->in_reply_to_status_id) > 1 ) {
	echo "[";
	echo $html->link("返事元", "/twitters/inreply/".h($status->in_reply_to_status_id));
	echo "]";
	}
	?>
	</div>
	<hr />
	<?php endforeach; ?>
<?php }else{ ?>
	<?php foreach ($timeline as $status): ?>
	<?php if(($i%2) === 0){ ?>
	<div style="background-color:#e0ffff";>
	<?php }else{ ?>
	<div>
	<?php }?>
	<?php echo $html->link(h($status->user->screen_name), "/twitters/user/".$status->user->id); ?>
	<br />
	<?php if (preg_match("/".$screen_name."/", $status->text)) { ?>
	<span style="color:#ff3300;">
	<?php } ?>
	<?php
	$text = preg_replace('/@(\w+)/', '@<a href="/twitters/user/$1">$1</a>', $status->text);
	$text = preg_replace('/(https?:\/\/[-_\.!~a-zA-Z0-9;\/?:@&=+$,%#]+)(\s?)/i', '<a href="$1">$1</a>$2', $text);
	echo $text;
	?>
	<?php if (preg_match("/".$screen_name."/", $status->text)) { ?>
	</span>
	<?php } ?>
	<br /><small>
	<?php echo h($time->format('Y/m/d H:i:s', $status->created_at)); ?></small><br />
	<?php echo $html->link("Re", "/twitters/reply/".h($status->user->screen_name)."/".h($status->id)); ?> 
	<?php echo $html->link("☆", "/twitters/fav/".h($status->id)); ?> 
	<?php echo $html->link("RT", "/twitters/rt/".h($status->id)); ?> 
	<?php  echo '<input type="checkbox" name="data[replies]['.$i.']" value="'.h($status->user->screen_name).'" id="replies'.$i.'" />'; $i++; ?>
	<?php if (intval($status->in_reply_to_status_id) > 1 ) {
	echo "[";
	echo $html->link("返事元", "/twitters/inreply/".h($status->in_reply_to_status_id));
	echo "]";
	}
	?>
	</div>
	<hr />
	<?php endforeach; ?>
<?php } ?>
<?php echo $form->submit('複数リプライする', array('div' => false)); ?>
<?php echo $form->end() ?>
</div>

<?php
$ktai->emoji(0xE6E2);
echo $html->link('ホームへ', '/twitters', array('accesskey' => 1)); ?>
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
<br />
<?php
$ktai->emoji(0xE6EB);
echo $html->link('ページトップへ', '#top', array('accesskey' => 0));
?>
<hr />
<?php echo $form->create("", array('action' => 'post')); ?>
<?php echo $form->input('mes', array('label'=>array('text'=>'いまどうしてる？'), 'div' => false )); ?>
<?php echo $form->input("in_reply_id", array("type" => "hidden", "value" => ""));?>
<?php echo $form->submit('投稿する', array('div' => false)); ?>
<?php echo $form->end() ?>
<hr />
<span style="text-align: left"><small>
<?php if ($page >= 2) {
$ktai->emoji(0xE6E8);
echo $html->link("前ページへ", "/twitters/page/".($page - 1), array('accesskey' => 7));
echo "<br />";
} ?>
</small></span>
<span style="text-align: right"><small>
<?php if ($page <= 9) {
$ktai->emoji(0xE6EA);
echo $html->link("次ページへ", "/twitters/page/".($page + 1), array('accesskey' => 9));
} ?>
</small></span>
<br />
<?php 
$data = array(
'1' => '1',
'2' => '2',
'3' => '3',
'4' => '4',
'5' => '5',
'6' => '6',
'7' => '7',
'8' => '8',
'9' => '9',
'10' => '10'
);
echo $form->create("", array('action' => 'page'));
?>
ページ：
<?php
echo $form->select('page', $data, $page, false, false);
echo $form->submit('移動する', array('div' => false));
echo $form->end() ?>
<?php echo $this->renderElement("footer"); ?>