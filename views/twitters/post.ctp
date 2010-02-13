<?php
if ($moji_suu === false) {
	echo "投稿文が長すぎます<br />";
}
if ($toukou_kanryo === true) {
	echo "投稿しました<br />";
}
echo $form->create("", array('action' => 'post'));
echo $form->input('mes', array('label'=>array('text'=>''),"value" => $value, 'div' => false ));
echo $form->submit('投稿する', array('div' => false));
echo $form->end();
?>
<hr />
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
<?php echo $this->renderElement("footer"); ?>