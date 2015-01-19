<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	class Compiler
	{
		var $_tokens;
		var $_counter;
		var $_allowed;
		var $_compiled;

		function Compiler()
		{
			$this->_control=Array("break","case","continue","default","do","else","elseif","endfor","endforeach","endif","endswitch","endwhile","for","foreach","if","while","return","switch","as");

			$this->_allowed=Array("array","array_change_key_case","array_chunk","array_count_values","array_diff","array_flip","array_fill","array_intersect","array_key_exists","array_keys","array_merge","array_merge_recursive","array_multisort","array_pad","array_pop","array_push","array_rand","array_reverse","array_shift","array_slice","array_splice","array_sum","array_unique","array_unshift","array_values","arsort","asort","count","current","each","end","in_array","array_search","key","krsort","ksort","list","natsort","natcasesort","next","pos","prev","range","reset","rsort","shuffle","sizeof","sort","get_object_vars",
			"checkdate","date","getdate","gettimeofday","gmdate","gmmktime","gmstrftime","localtime","microtime","mktime","strftime","time","strtotime","easter_days","easter_date",
			"abs","acos","acosh","asin","asinh","atan","atanh","atan2","base_convert","bindec","ceil","cos","cosh","decbin","dechex","decoct","deg2rad","exp","expm1","floor","getrandmax","hexdec","hypot","lcg_value","log","log10","log1p","max","min","mt_rand","mt_srand","mt_getrandmax","number_format","octdec","pi","pow","rad2deg","rand","round","sin","sinh","sqrt","srand","tan","tanh","bcadd","bccomp","bcdiv","bcmod","bcmul","bcpow","bcscale","bcsqrt","bcsub",
			"mail","pack","get_browser",
			"checkdnsrr","gethostbyaddr","gethostbyname","gethostbynamel","getmxrr","getservbyport","getservbyname","ip2long","long2ip",
			"ereg","ereg_replace","eregi","eregi_replace","split","spliti","preg_match","preg_match_all","preg_split","preg_quote","preg_grep",
			"addslashes","addcslashes","bin2hex","chop","chr","chunk_split","convert_cyr_string","count_chars","crc32","crypt","echo","explode","flush","get_html_translation_table","htmlentities","htmlspecialchars","implode","join","levenshtein","localeconv","ltrim","md5","metaphone","nl2br","ord","print","printf","quoted_printable_decode","quotemeta","rawurldecode","rawurlencode","rtrim","str_rot13","sscanf","setlocale","similar_text","soundex","sprintf","strncasecmp","strcasecmp","strchr","strcmp","strcoll","strcspn","strip_tags","stripcslashes","stripslashes","stristr","strlen","strnatcmp","strnatcasecmp","strncmp","str_pad","strpos","strrchr","str_repeat","strrev","strrpos","strspn","strstr","strtok","strtolower","strtoupper","str_replace","strtr","substr","substr_count","substr_replace","trim","ucfirst","ucwords","vprintf","vsprintf","wordwrap","base64_decode","base64_encode","urldecode","urlencode","parse_url",
			"doubleval","empty","floatval","gettype","intval","is_array","is_bool","is_double","is_float","is_int","is_integer","is_long","is_null","is_numeric","is_object","is_real","is_resource","is_scalar","is_string","isset","settype","serialize","strval","unserialize","unset","print_r","var_dump","var_export");
		}

		function compile($code)
		{
			$this->_tokens=token_get_all("<?\n".$code."\n?>");
			$ret=true;
			if($this->_tokens[0][0]==T_INLINE_HTML)
			{
				error("There is currently a problem with the output of the compiler, please try again in a few moments.","Compiler Error");
				$ret=false;
			}
			$this->_compiled="";
			$this->_counter=0;
			$this->_make();
			return $ret;
		}
		
		function get()
		{
			return $this->_compiled;
		}

		function _make()
		{
			$count=0;
			while(($token=$this->_getToken())!=false)
			{
				$count++;
				if(is_string($token))
				{
					//String
					$this->_compiled.=$token;
				}
				else
				{
					list($id,$val)=$token;
					switch($id)
					{
						//Variables
						case T_VARIABLE: /*$foo variables */
							$this->_compiled.=ereg_replace("^\\$(.*)$","\$shipping_\\1",$val);
							break;

						//Numbers
						case T_LNUMBER: /*123, 012, 0x1ac, etc integers */
						case T_DNUMBER: /*0.12, etc floating point numbers */
							$this->_compiled.=$val;
							break;

						//Strings
						case T_CONSTANT_ENCAPSED_STRING: /*"foo" or 'bar' string syntax */
							$this->_compiled.=$val;
							break;

						//Functions
						case T_STRING: /*    */
						case T_STRING_VARNAME: /*    */
							//Check if is function
							if($this->_lookAheadNoWS()=="(")
							{
								if(!in_array($val,$this->_allowed))
									$this->_compiled.="shipping_".$val;
								else
									$this->_compiled.=$val;
							}
							else
								$this->_compiled.=$val;
							break;

						case T_FUNCTION: /*function or cfunction functions */
							$this->_compiled.=$val;
							while(($tok=$this->_getToken())!=false)
							{
								if(is_array($tok) && $tok[0]!=T_WHITESPACE)
								{
									$this->_compiled.=" shipping_".$tok[1];
									break;
								}
							}
							break;

						case T_WHITESPACE: /*    */
							$this->_compiled.=$val;
							break;

						//Control Flow
						case T_IF: /*if if */
						case T_ELSE: /*else else */
						case T_ELSEIF: /*elseif elseif */
						case T_DO: /*do do..while */
						case T_WHILE: /*while while, do..while */
						case T_FOR: /*for for */
						case T_SWITCH: /*switch switch */
						case T_FOREACH: /*foreach foreach */
						case T_AS: /*as foreach */
						case T_CASE: /*case switch */
						case T_BREAK: /*break break */
						case T_CONTINUE: /*continue   */
						case T_ENDFOR: /*endfor for, alternative syntax */
						case T_ENDFOREACH: /*endforeach foreach, alternative syntax */
						case T_ENDIF: /*endif if, alternative syntax */
						case T_ENDSWITCH: /*endswitch switch, alternative syntax */
						case T_ENDWHILE: /*endwhile while, alternative syntax */
						case T_RETURN: /*return returning values */
						case T_DEFAULT: /*default switch */
							if(in_array($val,$this->_control))
								$this->_compiled.=$val;
							else
								$this->_compiled.="die($val)";
							break;

						//Assignment
						case T_AND_EQUAL: /*&= assignment operators */
						case T_CONCAT_EQUAL: /*.= assignment operators */
						case T_DIV_EQUAL: /*/= assignment operators */
						case T_DEC: /*-- incrementing/decrementing operators */
						case T_PLUS_EQUAL: /*+= assignment operators */
						case T_MINUS_EQUAL: /*-= assignment operators */
						case T_MOD_EQUAL: /*%= assignment operators */
						case T_MUL_EQUAL: /**= assignment operators */
						case T_OR_EQUAL: /*|= assignment operators */
						case T_SL_EQUAL: /*<<= assignment operators */
						case T_SR_EQUAL: /*>>= assignment operators */
						case T_XOR_EQUAL: /*^= assignment operators */
						case T_INC: /*++ incrementing/decrementing operators */
							$this->_compiled.=$val;
							break;

						//Comparison
						case T_IS_EQUAL: /*== comparison operators */
						case T_IS_GREATER_OR_EQUAL: /*>= comparison operators */
						case T_IS_IDENTICAL: /*=== comparison operators */
						case T_IS_NOT_EQUAL: /*!= or <> comparison operators */
						case T_IS_NOT_IDENTICAL: /*!== comparison operators */
						case T_IS_SMALLER_OR_EQUAL: /*<= comparison operators */
							$this->_compiled.=$val;
							break;

						//Logical Operators
						case T_BOOLEAN_AND: /*&& logical operators */
						case T_BOOLEAN_OR: /*|| logical operators */
						case T_LOGICAL_AND: /*and logical operators */
						case T_LOGICAL_OR: /*or logical operators */
						case T_LOGICAL_XOR: /*xor logical operators */
							$this->_compiled.=$val;
							break;

						//Arrays
						case T_ARRAY: /*array() array(), array syntax */
						case T_ARRAY_CAST: /*(array) type-casting */
						case T_DOUBLE_ARROW: /*=> array syntax */
							$this->_compiled.=$val;
							break;

						//Casting
						case T_BOOL_CAST: /*(bool) or (boolean) type-casting */
						case T_DOUBLE_CAST: /*(real), (double) or (float) type-casting */
						case T_INT_CAST: /*(int) or (integer) type-casting */
						case T_STRING_CAST: /*(string) type-casting */
							$this->_compiled.=$val;
							break;

						//Bitwise
						case T_SL: /*<< bitwise operators */
						case T_SR: /*>> bitwise operators */
							$this->_compiled.=$val;
							break;

						//Other allowed
						case T_PRINT: /*print() print() */
						case T_ECHO: /*echo echo() */
						case T_CURLY_OPEN: /*    */
						case T_DOLLAR_OPEN_CURLY_BRACES: /*${ complex variable parsed syntax */
						case T_ISSET: /*isset() isset() */
							$this->_compiled.=$val;
							break;

						//Script construction
						case T_OPEN_TAG: /*<?php, <? or <% escaping from HTML */
						case T_OPEN_TAG_WITH_ECHO: /*<?= or <%= escaping from HTML */
						case T_CLOSE_TAG: /*?> or %>   */
							$this->_compiled.=$val;
							break;

						//Strip
						case T_COMMENT: /*// or #, and  on PHP 5 comments */
						case T_BAD_CHARACTER: /*  anything below ASCII 32 except \t (0x09), \n (0x0a) and \r (0x0d) */
						case T_ML_COMMENT: /* comments (PHP 4 only) */
						case T_CHARACTER: /*    */
						case T_EMPTY: /*empty empty() */
						case T_ENCAPSED_AND_WHITESPACE: /*    */
						case T_ENDDECLARE: /*enddeclare declare, alternative syntax */
						case T_INLINE_HTML: /*    */
						case T_LINE: /*__LINE__ constants */
							break;

						case T_NEW: /*new classes and objects */
							//$this->_compiled.=$val;
							//token_name($id).":".$val;
							break;

						//Include, require and variable findout functions
						case T_INCLUDE: /*include() include() */
						case T_INCLUDE_ONCE: /*include_once() include_once() */
						case T_REQUIRE: /*require() require() */
						case T_REQUIRE_ONCE: /*require_once() require_once() */
							$this->_compiled.=notallowed;
							break;

						//Don't use
						case T_PAAMAYIM_NEKUDOTAYIM: /*:: ::. Also defined as T_DOUBLE_COLON. */
						case T_OBJECT_CAST: /*(object) type-casting */
						case T_OBJECT_OPERATOR: /*-> classes and objects */
						case T_OLD_FUNCTION: /*old_function old_function */
						case T_DECLARE: /*declare declare */
						case T_UNSET: /*unset() unset() */
						case T_UNSET_CAST: /*(unset) (not documented; casts to NULL) */
						case T_CLASS: /*class classes and objects */
						case T_STATIC: /*static variable scope */
						case T_START_HEREDOC: /*<<< heredoc syntax */
						case T_END_HEREDOC: /*  heredoc syntax */
						case T_CONST: /*const   */
						case T_VAR: /*var classes and objects */
						case T_EVAL: /*eval() eval() */
						case T_EXIT: /*exit or die exit(), die() */
						case T_EXTENDS: /*extends extends, classes and objects */
						case T_FILE: /*__FILE__ constants */
						case T_GLOBAL: /*global variable scope */
							$this->_compiled.="//".token_name($id)." Not Allowed\n";
							break;

						default:
							$this->_compiled.="//".token_name($id)." Invalid\n";
					}
				}
			}
		}

		function _getToken()
		{
			$this->_counter++;
			if($this->_counter<=count($this->_tokens))
				return $this->_tokens[$this->_counter-1];
			else
				return false;
		}

		function _lookAheadNoWS()
		{
			$ret=false;
			for($i=$this->_counter;$i<count($this->_tokens);$i++)
			{
				if(is_array($this->_tokens[$i]))
				{
					if($this->_tokens[$i][0]!=357)
					{
						$ret=$this->_tokens[$i];
						break;
					}
				}
				else
				{
					$ret=$this->_tokens[$i];
					break;
				}

			}
			return $ret;
		}
	}
?>
