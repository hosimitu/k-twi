<?php
/* SVN FILE: $Id$ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
if( $ktai->is_ktai() ){
  // Copyright 2009 Google Inc. All Rights Reserved.
  $GA_ACCOUNT = "**************";
  $GA_PIXEL = "ga.php";

  function googleAnalyticsGetImageUrl() {
    global $GA_ACCOUNT, $GA_PIXEL;
	$GA_ACCOUNT = "MO-4251498-11";
	$GA_PIXEL = "ga.php";
    $url = "http://www.example.com/";
    $url .= $GA_PIXEL . "?";
    $url .= "utmac=" . $GA_ACCOUNT;
    $url .= "&utmn=" . rand(0, 0x7fffffff);
    $referer = $_SERVER["HTTP_REFERER"];
    $query = $_SERVER["QUERY_STRING"];
    $path = $_SERVER["REQUEST_URI"];
    if (empty($referer)) {
      $referer = "-";
    }
    $url .= "&utmr=" . urlencode($referer);
    if (!empty($path)) {
      $url .= "&utmp=" . urlencode($path);
    }
    $url .= "&guid=ON";
    return str_replace("&", "&amp;", $url);
  }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $html->charset(); ?>
<title>k-twi/けーつい</title>
<?php echo $css->css( empty( $css_specify ) ? '' : $css_specify ); ?>
<style type="text/css">
<!--
hr {
border-color:white;
border-style:ridge;
}
<?php if( !$ktai->is_ktai() && (!$auth) ){ ?>
.arrow {
width:700px;
margin:auto;
font-size: 0px; line-height: 0%; width: 0px;
border-bottom: 20px solid #ffffff;
border-left: 10px solid #ccffcc;
border-right: 10px solid #ccffcc;
}
<?php } ?>
-->
</style>
</head>
<?php if( !$ktai->is_ktai() ){ //パソコンだった時 ?>
<body bgcolor="#ccffcc" text="#000000" link="#3333ff" vlink="#3333ff" alink=#ff6600>
<?php }else{ //携帯だった時?>
<body bgcolor="#ffffff" text="#000000" link="#3333ff" vlink="#3333ff" alink=#ff6600>
<?php } ?>
<?php if( !$ktai->is_ktai() && (!$auth) ){ ?>
<div>
<?php }; ?>
	<div id="container" style="overflow:auto;">
		<div id="header" style="text-align:center;">
			<?php echo $html->image('title2.gif', array('alt'=> 'k-twi', 'border'=>"0")) ?>
		</div>
		<div id="content" style="padding:0 10px 0 10px;">
			<?php echo $content_for_layout; ?>
		</div>
	</div>
	<?php echo $cakeDebug; ?>
<?php if( !$ktai->is_ktai() && (!$auth) ){	//パソコンの時のページ生成?>
</div>
<?php }; ?>
<?php
if( $ktai->is_ktai() ){
	//google analytics
	$googleAnalyticsImageUrl = googleAnalyticsGetImageUrl();
?>
<img src="<?php echo $googleAnalyticsImageUrl ?>" /><?php } ?>
</body>
</html>