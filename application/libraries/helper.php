<?php

class Helper
{
	public static function avatar($user_id, $size = '64x64')
	{
		// Set the full path to the file.
		$file_path = 'img/avatars/' . $user_id . '_'.$size.'.jpg';
		$null_path = 'img/avatars/' . $size.'.gif';

		// Return the avatar.
		return File::exists(path('public') . $file_path) ? URL::to($file_path) : URL::to($null_path);
	}

	/**
	 * Parse string using blade parser.
	 * @param  string $string The string to be parsed.
	 * @return string         The parsed string.
	 */
	public static function bladeParse($string = '')
	{
		return eval('?>' . Blade::compile_string($string) . '<?php ');
	}

	/**
	 * Check if last word in a string matches a particular letter.
	 * @param  string $string The string of data.
	 * @param  string $letter The letter we're looking to match.
	 * @return boolean         TRUE = match, FALSE = no match.
	 */
	public static function lastLetterMatch($string, $letter)
	{
		return (substr(Str::lower($string), -1) === Str::lower($letter));
	}

	/**
	 * 
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public static function apostrophes($string)
	{
		// Is "s" the last letter in the string?
		$match 		= self::lastLetterMatch($string, 's');

		// Example: Chris = Chris' , Jordan = Jordan's
		$postfix 	= $match ? "'" : "'s"; 

		return $string . $postfix;
	}

	/**
	 * Displays the timestamp's age in human readable format
	 * @param  int $timestamp 		The unix timestamp or dateime to convert.
	 * @param  boolean $isDateTime 	Is the timestamp supplied in datetime format?
	 * @return string
	 */
    public static function age($timestamp, $isDateTime = false)
    {
    	if($isDateTime){
			$dateTime 	= new DateTime($timestamp, new DateTimeZone('America/Chicago'));
			$timestamp 	= $dateTime->getTimestamp();
    	}

	    $timestamp 	= (int) $timestamp;
	    $difference = time() - $timestamp;
	    $periods 	= array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
	    $lengths 	= array('60','60','24','7','4.35','12','10');

	    for( $j = 0; $difference >= $lengths[$j]; $j++ )
	    {
	    	$difference /= $lengths[$j];
	    }

	    $difference = round( $difference );

	    if( $difference != 1 )
	    {
	    	$periods[$j] .= 's';
	    }

	    // Return the difference
	    return $difference . ' ' . $periods[$j] . ' ago';
    }

    /**
     * Convert new lines to paragraphcs. 
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
	public static function nl2p($string)
	{
		return preg_replace('/[\r\n]{4,}/', '</p><p>', $string);
	}

	/**
	 * Run string through series of sanitization.
	 * @param  string $string The string to be sanitized.
	 * @return string         The sanitized string.
	 */
	public static function sanitize($string)
	{
		$santized_string = self::removeScripTags($string);
		$santized_string = self::remove_event_attributes($santized_string);
		$santized_string = self::remove_event_attributes_from_tag($santized_string);

		return $santized_string;
	}

	/**
	 * Remove Script Tags From HTML
	 * @param  string $string The string of data to be sanitized.
	 * @return string         The sanitized string.
	 */
	public static function removeScripTags($string)
	{
		return preg_replace('#<script.*</script>#is', "", $string);
	}

	/**
	 * Removes onanything attributes from all matched HTML tags
	 * @param  string $html The string of data to be sanitized.
	 * @return string       The sanitized string.
	 */
	public static function remove_event_attributes($html){
	    $re = '(?&tag)' . self::redefs();
	    return preg_replace("~$re~xie", 'Helper::remove_event_attributes_from_tag("$0")', $html);
	}

	/**
	 * Removes onanything attributes from a single opening tag.
	 * @param  string $tag 	The string of data to be sanitized.
	 * @return string       The sanitized string.
	 */
	public static function remove_event_attributes_from_tag($tag){
	    $re = '( ^ <(?&tagname) ) | \G \s*+ (?> ((?&attrib)) | ((?&crap)) )' . self::redefs();
	    return preg_replace("~$re~xie", '"$1$3"? "$0": (preg_match("/^on/i", "$2")? " ": "$0")', $tag);
	}

	/**
	 * Redefines
	 * @return string
	 */
	public static function redefs()
	{
		return '(?(DEFINE)
		    (?<tagname> [a-z][^\s>/]*+    )
		    (?<attname> [^\s>/][^\s=>/]*+    )  # first char can be pretty much anything, including =
		    (?<attval>  (?>
		                    "[^"]*+" |
		                    \'[^\']*+\' |
		                    [^\s>]*+            # unquoted values can contain quotes, = and /
		                )
		    ) 
		    (?<attrib>  (?&attname)
		                (?: \s*+
		                    = \s*+
		                    (?&attval)
		                )?+
		    )
		    (?<crap>    [^\s>]    )             # most crap inside tag is ignored, will eat the last / in self closing tags
		    (?<tag>     <(?&tagname)
		                (?: \s*+                # spaces between attributes not required: <b/foo=">"style=color:red>bold red text</b>
		                    (?>
		                        (?&attrib) |    # order matters
		                        (?&crap)        # if not an attribute, eat the crap
		                    )
		                )*+
		                \s*+ /?+
		                \s*+ >
		    )
		)';
	}
}