<?
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Dave Mertens <dmertens@zyprexia.com>                        |
// +----------------------------------------------------------------------+
//
// $Id: Rc4.php,v 1.6 2003/10/04 16:39:32 zyprexia Exp $

	class Crypt_RC4
	{
	    var $s= array();
	    var $i= 0;
	    var $j= 0;
	    var $_key;
	    function Crypt_RC4($key=null)
	    {
        	if($key!=null)
        	{
	            $this->setKey($key);
        	}
    	}

    	function setKey($key)
    	{
        	if(strlen($key)>0)
	            $this->_key=$key;
	    }

	    function key(&$key)
	    {
        	$len= strlen($key);
        	for ($this->i=0;$this->i<256;$this->i++)
	            $this->s[$this->i]=$this->i;

        	$this->j=0;
        	for ($this->i=0;$this->i<256;$this->i++)
        	{
	            $this->j=($this->j+$this->s[$this->i]+ord($key[$this->i%$len]))%256;
            	$t=$this->s[$this->i];
            	$this->s[$this->i]=$this->s[$this->j];
            	$this->s[$this->j]=$t;
        	}
        	$this->i=$this->j=0;
    	}

    	function crypt(&$paramstr)
    	{
	        $this->key($this->_key);
        	$len= strlen($paramstr);
        	for ($c= 0;$c<$len;$c++)
        	{
	            $this->i=($this->i+1)%256;
            	$this->j=($this->j+$this->s[$this->i])%256;
            	$t=$this->s[$this->i];
            	$this->s[$this->i]=$this->s[$this->j];
            	$this->s[$this->j]=$t;
            	$t=($this->s[$this->i]+$this->s[$this->j])%256;
            	$paramstr[$c]=chr(ord($paramstr[$c])^$this->s[$t]);
    	    }
    	}

       	function decrypt(&$paramstr)
    	{
        	$this->crypt($paramstr);
	    }
	}
?>