<?php
class CssHelper extends Helper{
	var $helpers = array( 'Html' );
	
	function css( $path = '', $rel = 'stylesheet', $htmlAttributes = null, $return = false)	{
		if( !$path ){
			$path	= $this->params['controller'] . '_' . $this->action;
		}
		$url	= "{$this->webroot}". CSS_URL . $this->themeWeb . $path . ".css";
		$fpath	= CSS . $path . '.css';
		if( !file_exists( $fpath ) )	return '';
		if ($rel == 'import') {
			return $this->output(sprintf($this->Html->tags['style'], $this->Html->_parseAttributes($htmlAttributes, null, '', ' '), '@import url(' . $url . ');'), $return);
		} else {
			return $this->output(sprintf($this->Html->tags['css'], $rel, $url, $this->Html->_parseAttributes($htmlAttributes, null, '', ' ')), $return);
		}
	}
}
