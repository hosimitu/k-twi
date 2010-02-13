<div style="font-weight:bold;">アイコンの設定</div>
<?php
echo $form->create("", array('action' => 'savesetting'));
$icon = array(
	'1' => 'アイコンを表示する',
	'2' => 'アイコンを表示しない',
	'3' => 'デフォルトアイコンにする'
);
echo $form->select('icon', $icon, $auth['icon'], array('div' => false ), false);
?>
<br /><br />
<div style="font-weight:bold;">ホームのTL取得件数</div>
<div>（過去のページはすべて20件ずつの表示になります）</div>
<?php
$count = array(
	'20' => '20',
	'50' => '50',
	'75' => '75',
	'100' => '100',
	'150' => '150',
	'200' => '200'
);
echo $form->select('count', $count, $auth['count'], array('div' => false ), false);
?>
<br /><br />
<div style="font-weight:bold;">フッターの設定</div>
<?php
echo $form->input('footer', array('value'=> $auth['footer'], 'div' => false, 'label' => false ));
echo $form->submit('変更する', array('div' => false));
echo $form->end()
?>
<br />
<a href="https://ssl.form-mailer.jp/fms/df5d9ff778085">ご意見ご感想</a><br />
<?php echo $this->renderElement("footer"); ?>