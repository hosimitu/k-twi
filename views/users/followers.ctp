<div><h3>フォローしている人</h3><br />
<?php
	foreach ($friends as $val){
		echo $val['screen_name'];
		echo "<br />";
	}
?>
</div>
<div><h3>フォローしていて半年以上発言がない人</h3><br />
<?php
	foreach ($sinin as $val){
		echo $val['screen_name'];
		echo "<br />";
	}
?>
</div>
<div><h3>片思われ</h3><br />
<?php
	foreach ($kataomoware as $val){
		echo $val['screen_name'];
		echo "<br />";
	}
?>
</div>
