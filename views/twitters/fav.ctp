お気に入りに追加しました。
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