<?php

namespace imonroe\ana;

class Ana
{
    /*
	This is the newest version of my Ana helper library, that I've converted for use in Laravel.
	It's probably not of interest to anyone except me, but if you should find some use for it
	in some other project, please drop me a line at ian@ianmonroe.com and let me know!
	*/

///////////////////////////////////////////////////////////////////
    /* Date, time functions in this section. */

    public static function standard_date_format($timestamp = '')
    {
        // My preferred datetime format for presentation
        if ($timestamp == '') {
            $timestamp = time();
        }
        return date('M j, Y, g:i a T', $timestamp);
    }

    public static function sql_datetime($timestamp = '')
    {
        // Returns an MySQL-friendly datetime string.
        if ($timestamp == '') {
            $timestamp = time();
        }
        return date("Y-m-d H:i:s", $timestamp);
    }

    public static function google_datetime($timestamp = '')
    {
        // Google likes RFC3339-style datetimes
        if ($timestamp == '') {
            $timestamp = time();
        }
        return date(DATE_RFC3339, $timestamp);
    }

    public static function is_today($date_string)
    {
        $today_string = date('M j, Y', time());
        $target_string = date('M j, Y', strtotime($date_string));
        if ($today_string == $target_string) {
            return true;
        } else {
            return false;
        }
    }

    public static function sooner_than($date_string)
    {
        if ((strtotime("now")) < (strtotime($date_string))) {
            return true;
        } else {
            return false;
        }
    }


    public static function later_than($date_string)
    {
        if ((strtotime("now")) > (strtotime($date_string))) {
            return true;
        } else {
            return false;
        }
    }

    public static function print_relative_date($date)
    {
        $valid_date = (is_numeric($date) && strtotime($date) === false) ? $date : strtotime($date);
            $diff = time() - $valid_date;
        if ($diff > 0) {
            if ($diff < 60) {
                return $diff . " second" . self::plural($diff) . " ago";
            }
            $diff = round($diff / 60);

            if ($diff < 60) {
                return $diff . " minute" . self::plural($diff) . " ago";
            }
            $diff = round($diff / 60);

            if ($diff < 24) {
                return $diff . " hour" . self::plural($diff) . " ago";
            }
            $diff = round($diff / 24);

            if ($diff < 7) {
                return "about " . $diff . " day" . self::plural($diff) . " ago";
            }
            $diff = round($diff / 7);

            if ($diff < 4) {
                return "about " . $diff . " week" . self::plural($diff) . " ago";
            }

            return "on " . date("F j, Y", $valid_date);
        } else {
            if ($diff > -60) {
                return "in " . -$diff . " second" . self::plural($diff);
            }
            $diff = round($diff / 60);

            if ($diff > -60) {
                return "in " . -$diff . " minute" . self::plural($diff);
            }
            $diff = round($diff / 60);

            if ($diff > -24) {
                return "in " . -$diff . " hour" . self::plural($diff);
            }
            $diff = round($diff / 24);

            if ($diff > -7) {
                return "in " . -$diff . " day" . self::plural($diff);
            }
            $diff = round($diff / 7);

            if ($diff > -4) {
                return "in " . -$diff . " week" . self::plural($diff);
            }

            return "on " . date("F j, Y", $valid_date);
        }
    }


    /* end of Date and Time functions */
///////////////////////////////////////////////////////////////////
    /*  Error handling functions in this section */

    function fatal_handler()
    {
        $error = error_get_last();
        if ($error !== null && $error['type'] == E_ERROR) {
            $errno   = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr  = $error["message"];
            Log::info("Error ($errno) in $errfile on line $errline: $errstr");
            header("HTTP/1.1 500 Internal Server Error");
        }
    }

    /* dump and die, like with laravel. */
    public static function dd($var)
    {
        echo('<pre>');
        echo (var_export($var, true));
        echo('</pre>');
        die();
    }

    /* end of error handling functions */
///////////////////////////////////////////////////////////////////
    /* Array manipulation functions in this section */
    
    
    /*
        Like array_unique(), but works with multi-dimensional arrays.
    */
    public static function array_unique_multi($arr)
    {
        $sanitized_arr = array_map("unserialize", array_unique(array_map("serialize", $arr)));
        return $sanitized_arr;
    }

    public static function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    public static function object_to_array($object)
    {
        return json_decode(json_encode($object), true);
    }

    /*
      Utility function to build a tree structure from a flat array where the
      elements may (or may not) have a parent.
      Stolen from here: https://stackoverflow.com/questions/4196157/create-array-tree-from-array-list

      syntax: $tree = buildTree($flat_array, 'parentID', 'id');
              print_r($tree);
      returns an array.
    */
    public static function build_tree($flat, $pidKey, $idKey = null)
    {
        $grouped = array();
        foreach ($flat as $sub) {
            $grouped[$sub[$pidKey]][] = $sub;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];
                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };
        $tree = $fnBuilder($grouped[0]);
        return $tree;
    } // end of build_tree.

    /**
    * csv_to_array reads a CSV file, and returns a nicely formatted array.
    * @link http://gist.github.com/385876
    */
    public static function csv_to_array($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    } // end of csv_to_array().
    
    /* end of Array manipulation functions */
///////////////////////////////////////////////////////////////////
    /* String manipulation functions in this section */

    public static function plural($quantity)
    {
        return ( intval($quantity) != 1 && intval($quantity) != -1  ) ? 's' : '';
    }

    public static function word_limit($haystack, $ubound)
    {
        $return_val = explode(" ", $haystack);
        return implode(" ", array_splice($return_val, 0, $ubound));
    }// end function word_limit

    public static function convert_to_utf($input)
    {
        // Fairly aggressive, but works.
        return strip_tags(iconv(mb_detect_encoding($text), "UTF-8//IGNORE", $input));
    }

    public static function plain_text($input)
    {
        // takes a string, strips out tags, HTML entities, etc.  Returns plain UTF-8 string.
        $output = strip_tags($input);
        $output = str_replace("&nbsp;", " ", $output);
        $output = html_entity_decode($output, ENT_COMPAT, 'UTF-8');
        return $output;
    }

    public static function trim_string_to_length($str, $len)
    {
        // limits to a length; doesn't pretty it up at all.
        return mb_strimwidth($str, 0, $len);
    }

    /*
    *  useAorAn($text)
    *
    *  determines whether the article for the text should be 'a' or 'an'
    *  @IN:  $text
    *  @RET  $string - ["a" | "an"]
    */
    public static function use_a_or_an($text)
    {
        return (in_array(strtolower(substr($text, 0, 1)), array('a', 'e', 'i', 'o', 'u')) ? "an": "a" );
    }

    /* end of string manipulation functions */
///////////////////////////////////////////////////////////////////
    /* Numeric manipulation functions go here */

    public static function even_or_odd($number)
    {
        $int_number = (int)$number;
        $return_val = '';
        if ($int_number < 0) {
            // normalize negative numbers.
            $int_number = $int_number * -1;
        }
        if ($int_number == 0) {
            $return_val = false;
        } elseif ($int_number == 1) {
            $return_val = 'odd';
        } elseif ($int_number > 1) {
            if (($int_number % 2) <> 0) {
                $return_val = 'odd';
            } else {
                $return_val = 'even';
            }
        } else {
            return 'even_odd error';
        }
        return $return_val;
    } // end even_or_odd

    public static function random_number($lowbound = 1, $highbound = 100)
    {
        // returns a random integer between the low bound and the high bound.
        // default range is 1-100
        // Should be suitible for cryptographically secure random number generation.
        // see: http://php.net/manual/en/function.random-int.php
        return random_int($lowbound, $highbound);
    }

    public static function random_hex($bytes = 8)
    {
        // returns a hex value corresponding with the given number of random bytes.
        // cryptographically secure.  Good for seeds and salts, etc.
        $r_bytes = random_bytes($bytes);
        return bin2hex($r_bytes);
    }

    /* end numeric manipulation functions */
///////////////////////////////////////////////////////////////////
    /* Uncategorized functions in this section */

    // SWIPED FROM: https://gist.github.com/tylerhall/521810
    // Generates a strong password of N length containing at least one lower case letter,
    // one uppercase letter, one digit, and one special character. The remaining characters
    // in the password are chosen at random from those four sets.
    //
    // The available characters in each set are user friendly - there are no ambiguous
    // characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
    // makes it much easier for users to manually type or speak their passwords.
    //
    // Note: the $add_dashes option will increase the length of the password by
    // floor(sqrt(N)) characters.
    public static function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if (strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        if (strpos($available_sets, 's') !== false) {
            $sets[] = '!@#$%&*?';
        }
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }
        $password = str_shuffle($password);
        if (!$add_dashes) {
            return $password;
        }
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    public static function create_nonce()
    {
        $seed_timestamp = date("Y-m-d H:i:s");
        $seed_salt = openssl_random_pseudo_bytes(128);
        $seed = $seed_salt . $seed_timestamp;
        return sha1($seed);
    }

    public static function current_page_url()
    {
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        return $protocol . $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }

    public static function get_url_segment($number)
    {
        $output = false;
        $page_url = $_SERVER['REQUEST_URI'];
        if (strpos($page_url, '?')) {
            $page_url = strtok($page_url, '?');
        }
        $url_array = explode("/", $page_url);
        $arr_len = count($url_array);
        if ($number <= ($arr_len-1)) {
            $output = mysql_real_escape_string($url_array[$number]);
        }
        return $output;
    }

    public static function is_valid_link($link)
    {
        // Feed it a URL, returns an HTTP status code.
        // swiped from here: http://www.codezuzu.com/2015/03/how-to-validate-linkurl-in-php/
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true); // Include the headers
        curl_setopt($ch, CURLOPT_NOBODY, true); // Make HEAD request
        $response = curl_exec($ch);
        if ($response === false) {
            // something went wrong, assume not valid
            return false;
        }
        $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (in_array($http_code, array(200, 301, 302, 303, 307)) === false) {
            // not a valid http code to asume success, link is not valid
            return false;
        }
        curl_close($ch);
        return $http_code;
    }

    public static function quick_curl($link)
    {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if ($response === false) {
            // something went wrong, assume not valid
            return false;
        }
        $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (in_array($http_code, array(200, 301, 302, 303, 307)) === false) {
            // not a valid http code to asume success, link is not valid
            return false;
        }
        curl_close($ch);
        return $response;
    }

    public static function get_ip()
    {
        // swiped from here: https://www.chriswiegman.com/2014/05/getting-correct-ip-address-php/
        /*
			     The goal here is to get the actual IP address of the requester, even behind a reverse proxy, etc.
		    */
        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        return $the_ip;
    }

    public static function submit_post_request($url, $data)
    {
        $fields_string = '';
        foreach ($data as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($ch);

        return $result;
    }

    public static function loading_spinner()
    {
        // only works with FontAwesome included.
        return '<i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>';
    }

    /* end of uncategorized functions*/
///////////////////////////////////////////////////////////////////
    /* Metaprogramming functions*/

    public static function code_safe_name($string)
    {
      // we want to take a string like "Custom aspect test"
      // and turn it into "CustomAspectTest"
      // so we can predictably use class names in later code.
        $output = ucwords($string);
        $output = preg_replace("/[^A-Za-z0-9 ]/", '', $output);
        $output = preg_replace('/\s+/', '', $output);
        return $output;
    }

    /**
    * Class casting
    * found here: https://stackoverflow.com/questions/2226103/how-to-cast-objects-in-php#2232065
    *
    * @param string|object $destination
    * @param object $sourceObject
    * @return object
    */
    function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }

    /* end of Metaprogramming functions */
///////////////////////////////////////////////////////////////////

    /* Command-line functions */

    /*
   	* prompt a user for information
	* @param string $prompt
  	* @return string
   	*/
    static function ask_user($prompt)
    {
        echo PHP_EOL.$prompt.' ';
        $input = trim(fgets(STDIN));
        return $input;
    }

    /**
     * Says something to the user.
     * @param string $msg
     * @return none
     */
    static function say($msg)
    {
        echo($msg . PHP_EOL);
    }

    /*
	* Generates an error message and exits the program.
	* @param string $msg
  	* @return none
	*/
    static function error_out($msg)
    {
        echo $msg.PHP_EOL;
        exit();
    }

    /*
   	* Create a directory
	* @param string $directory_path
	* @param int $perms
  	* @return none
   	*/
    static function create_directory($directory_path, $perms = 0777)
    {
        echo ("Creating the directory: ".$directory_path.PHP_EOL);
        if (!file_exists($directory_path)) {
            mkdir($directory_path, $perms, true);
        } else {
            self::error_out('Error: directory already exists or cannot be written.');
        }
    }

    /**
     * Remove a directory if it exists.
     *
     * Swiped from: https://github.com/imonroe/laravel-packager/blob/master/src/PackagerHelper.php
     *
     * @param  string $path Path of the directory to remove.
     *
     * @return void
     */
    static function remove_directory($path)
    {
        if ($path == '/') {
            return false;
        }
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir("$path/$file")) {
                self::removeDir("$path/$file");
            } else {
                @chmod("$path/$file", 0777);
                @unlink("$path/$file");
            }
        }
        return rmdir($path);
    }

    /*
   	* create a text file
   	*/
    static function create_file($file_path_and_name, $file_content, $overwrite = false)
    {
        echo ("Setting up the file: ".$file_path_and_name.PHP_EOL);
        if (!$overwrite) {
            if (!file_exists($file_path_and_name)) {
                file_put_contents($file_path_and_name, $file_content);
            } else {
                self::error_out('Error: file already exists or cannot be written.');
            }
        } else {
            file_put_contents($file_path_and_name, $file_content);
        }
    }

    /*
   	* append a text file
   	*/
    static function append_file($file_path_and_name, $file_content, $overwrite = false)
    {
        echo ("Appending the file: ".$file_path_and_name.PHP_EOL);
        if ($overwrite) {
            file_put_contents($file_path_and_name, $file_content);
        } else {
            file_put_contents($file_path_and_name, $file_content, FILE_APPEND);
        }
    }

    /*
   	* Uses CURL to fetch a URL, and saves it to the file specified by $filename
  	 */
    static function get_url_and_save($fully_qualified_url, $filename)
    {
        // strange 400 errors can occur if we don't check to make sure our URL is trimmed up.
        $fully_qualified_url = trim($fully_qualified_url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fully_qualified_url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpdebug = curl_getinfo($ch);
        if ($httpcode == 200) {
            self::create_file($filename, $output);
            echo("Saved file: ".$filename.PHP_EOL);
        } else {
            echo ('error.  http debug: '.var_export($httpdebug, true).PHP_EOL);
        }
        curl_close($ch);
    }

    /*
   	* Wrapper and error control for file_get_contents().  Returns the file content as a string.
   	*/
    static function read_file_to_string($filename)
    {
        $output = false;
        try {
            $output = file_get_contents($filename);
        } catch (Exception $e) {
            echo ('Error reading file: '.var_export($e, true).PHP_EOL);
            die();
        }
        return $output;
    }

    /*
   	* Wrapper and error control for scandir(). Returns an array of files in the directory specified.
   	*/
    static function get_directory_list($directory_path)
    {
        $output = false;
        try {
            $output = scandir($directory_path);
            foreach ($output as $key => $file) {
                if (!is_file($directory_path.'/'.$file)) {
                    // just return files please.
                    unset($output[$key]);
                }
            }
        } catch (Exception $e) {
            echo ('Error reading directory: '.var_export($e, true).PHP_EOL);
            die();
        }
        return $output;
    }

    /*
   	* Execute a command-line command, and echo it's output.
	* The command is executed by the user who has invoked this script.
   	*/
    static function execute($cmd)
    {
        $result = '';
        echo 'Executing ' . $cmd . PHP_EOL;
        $result = shell_exec($cmd);
        echo($result);
        return $result;
    }

    /**
     * Execute a command-line command with interaction.
     * This can be used to string together php scripts
     * or other shell commands that require user input.
     * 
     * Returns the return value of the command executed.
     */
    static function run_interactive($cmd)
    {
        $descriptors = array(
          0 => array ("file", "php://stdin", "r"),
          1 => array ("file", "php://stdout", "w"),
          2 => array ("file", "php://stdout", "w")
        );
        $process = proc_open($cmd, $descriptors, $pipes);
        if (is_resource($process)) {
          $return_value = proc_close($process);
          return $return_value;
        }
    }


    /*
  	* we're essentially aliasing php's getopt() functionality.
  	* @return array
  	*/
    static function get_arguments()
    {
        global $argv;
        $_ARG = array();
        foreach ($argv as $arg) {
            $temp_arg = explode('=', $arg);
            $_ARG[$temp_arg[0]] = null;
            if (isset($temp_arg[1])) {
                $_ARG[$temp_arg[0]] = $temp_arg[1];
            }
        }
        return $_ARG;
    }

    /**
     * Replace a line in a text file.
     *
     * Use this function to replace an entire single line in a text file.
     *
     * The $filename parameter specifies the file you are working with.
     * The $line_to_change is the text pattern to search for in the file.
     *   Be careful how you specify this string and make sure it matches ONLY in the lines you are interested in.
     * The $change_to string specifies what to replace the line with.
     *
     * @param string $filename
     * @param string $line_to_change
     * @param string $change_to
     * @return bool
     */
    static function replace_line_in_file(String $filename = '', String $line_to_change = '', String $change_to = '')
    {
        $replacement_made = false;
        if (is_writable($filename)) {
            self::say('Reading file: '.$filename);
            $file = file($filename);
        } else {
            self::say('Could not find the file, or it was not writable: '.$filename);
            return false;
        }
        
        self::say('Making replacement.');
        foreach ($file as $line_number => $line) {
            if (!(strrpos($line, $line_to_change)===false)) {
                $file[$line_number] = $change_to . PHP_EOL;
                $replacement_made = true;
            }
        }

        if ($replacement_made) {
            self::say('Replacement made successfully.');
            $f = implode('', $file);
            file_put_contents($filename, $f);
            self::say('Saving file.');
        } else {
            self::say('Could not find anything to replace.');
        }

        return $replacement_made;
    }


    /**
     * Open haystack, find and replace needles, save haystack.
     *
     *
     * @param  string $oldFile The haystack
     * @param  mixed  $search  String or array to look for (the needles)
     * @param  mixed  $replace What to replace the needles for?
     * @param  string $newFile Where to save, defaults to $oldFile
     *
     * @return void
     */
    static function replace_and_save($oldFile, $search, $replace, $newFile = null)
    {
        $newFile = ($newFile == null) ? $oldFile : $newFile;
        $file = self::read_file_to_string($oldFile);
        $replacing = str_replace($search, $replace, $file);
        self::create_file($newFile, $replacing, $overwrite = true);
    }

    /* end command-line functions*/
///////////////////////////////////////////////////////////////////
    /* Handy Reference functions for common stuff. */

    /*
    *  In the United States, you often need a list of all the states.
    *  @return array
    */
    static function us_states()
    {
        return array(
          "AL" => "Alabama",
          "AK" => "Alaska",
          "AZ" => "Arizona",
          "AR" => "Arkansas",
          "CA" => "California",
          "CO" => "Colorado",
          "CT" => "Connecticut",
          "DE" => "Delaware",
          "DC" => "District Of Columbia",
          "FL" => "Florida",
          "GA" => "Georgia",
          "HI" => "Hawaii",
          "ID" => "Idaho",
          "IL" => "Illinois",
          "IN" => "Indiana",
          "IA" => "Iowa",
          "KS" => "Kansas",
          "KY" => "Kentucky",
          "LA" => "Louisiana",
          "ME" => "Maine",
          "MD" => "Maryland",
          "MA" => "Massachusetts",
          "MI" => "Michigan",
          "MN" => "Minnesota",
          "MS" => "Mississippi",
          "MO" => "Missouri",
          "MT" => "Montana",
          "NE" => "Nebraska",
          "NV" => "Nevada",
          "NH" => "New Hampshire",
          "NJ" => "New Jersey",
          "NM" => "New Mexico",
          "NY" => "New York",
          "NC" => "North Carolina",
          "ND" => "North Dakota",
          "OH" => "Ohio",
          "OK" => "Oklahoma",
          "OR" => "Oregon",
          "PA" => "Pennsylvania",
          "RI" => "Rhode Island",
          "SC" => "South Carolina",
          "SD" => "South Dakota",
          "TN" => "Tennessee",
          "TX" => "Texas",
          "UT" => "Utah",
          "VT" => "Vermont",
          "VA" => "Virginia",
          "WA" => "Washington",
          "WV" => "West Virginia",
          "WI" => "Wisconsin",
          "WY" => "Wyoming"
        );
    }
}
