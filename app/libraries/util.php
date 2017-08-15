<?php
use \DB;
/**
 * Util Helpers
 *
 * @package Laravel
 * @subpackage TestApp
 */
class Util {

	/**
     * display messages set in session
     *
     * <div class="box box-success closeable">%s</div>
     * <div class="box box-error closeable">%s</div>
     * <div class="box box-info closeable">%s</div>
     * <div class="box box-tip closeable">%s</div>
     *
     * @param array $errors
     * @return void 	
     */
    public static function displayMessages($errors=null) {
    	// capture
    	if( ! $errors ) $errors = Session::get('errors');

        if( $errors )
        {
			printf('<div style="margin-top:10px" id="msg-container"><div class="box box-%s closeable">%s</div></div>', $errors['status'], $errors['message']);
        }
    }

    /**
     * display notice set in session
     * 
	 * <div class="alert alert-success alert-dismissable"> <strong>Success!</strong> This is a success message. </div>
	 * <div class="alert alert-danger alert-dismissable"> <strong>Error!</strong> This is a error message. </div>
	 * <div class="alert alert-warning alert-dismissable"> <strong>Warning!</strong> This is a warning message. </div>
	 * <div class="alert alert-info alert-dismissable"> <strong>Information:</strong> This is a informative message. </div>
	 * <div class="notification tip alert-dismissable"> <strong>Tip:</strong> This is a tip message. </div>
	 *
     * @param array $errors
     * @return void 	
     */
    public static function displayNotices($errors=null) {
    	// capture
    	if( ! $errors ) $errors = Session::get('errors');
    	// check
        if( $errors )
        {
        	// labels
			$labels = array('success'=>'Success!','danger'=>'Error!','warning'=>'Warning!');	
			// status
			if( $errors['status'] == 'error') {
				$errors['status'] = 'danger';				
			}	
			// label
			$label = (isset($labels[$errors['status']])) ? $labels[$errors['status']] : ucfirst($errors['status']);
			// print
			printf('
			<div style="margin-top:10px" id="ntc-container"><div class="alert alert-%s"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>%s</strong> %s </div></div>', $errors['status'], $label, $errors['message']
			);
        }
    }

    /**
	 * split field and alias on AS 
	 *
	 * @param string $alias
	 * @return string $alias
	 */
    public static function aliasSplit($alias){
    	// CONCAT(firstname,lastname) AS name => returns name
		if(preg_match("/(\s+)AS(\s+)/i",$alias)){
			list($discard,$alias)=preg_split("/(\s+)AS(\s+)/i",$alias);
		}
		
		// A.id => return id
		if(preg_match("/[a-zA-Z]\.(.*)/",$alias)){		
			list($discard,$alias)=explode('.',$alias);		
		}
		
		// return
		return $alias;
    }    

    /**
	 * format date using timezone aware
	 *
	 * @param string $date
	 * @param string $format
	 * @param string $dest_tz destination timezone
	 * @param string $source_tz source timezone
	 * @return string $date
	 */
    public static function formatDate($date='now', $format='Y-m-d H:i:s', $source_tz='UTC', $dest_tz=null){
    	// now
    	if( $date == 'now' ) {
    		$date = Date::forge( $date )->format('datetime');
    	}else{
    		// check   	
	    	if ( is_null($date) || empty($date) || $date == '0000-00-00 00:00:00') {
	    		return 'n/a';
	    	}	    		
    	}   	
    	// dest
    	if( ! $dest_tz ) $dest_tz = Config::get('site.date.timezone', 'IST');
    	// format
    	try{
    		$dtObj = DateTime::createFromFormat( 'Y-m-d H:i:s', $date, new DateTimeZone( $source_tz ) );
    		$dtFmt = $dtObj->setTimezone( new DateTimeZone($dest_tz) )->format( $format );    		
    	}catch( Exception $e){
    		$dtFmt = Date::forge( $date )->format( $format );
    	}

    	return $dtFmt ;    	
    }

    /**
	 * is debug check by IP
	 *
	 * @param void
	 * @return bool
	 */
	public static function isDebugIp(){
		// ip
		if( ! defined('DEBUG_IP') )	define('DEBUG_IP', '116.202.215.127');
		// check
		if(Request::ip() == DEBUG_IP){
			return true;
		}
		// return
		return false;
	} 

	/**
	 * recursive dump
	 *
	 * @param mixed $var
	 * @param bool $return
	 * @return string
	 */
	public static function pr($var, $return=false){	
		// output
		$output = sprintf( '<pre>%s</pre>', print_r($var, 1) );
		// return 
		if($return) return $output;
		// print
		echo $output;
	}

	/**
	 * debug log
	 */
	public static function debugLog($content, $name='debug', $ext='log'){
		// convert
		if(is_array($content) || is_object($content)){
			$content = static::pr($content, true);
		}
		// content
		$content .= "\n\n";
		// difine time
		if( ! defined('LOG_TIMESTAMP')) define('LOG_TIMESTAMP', date('Ymd_His'));
		// write
		return File::append(storage_path(sprintf('logs/%s_%s.%s', $name, LOG_TIMESTAMP, $ext)), $content);	
	}

	/**
	 * log data or print
	 *
	 * @param string $content
	 * @param string $group 
	 * @param boolean $log
	 * @return void
	 */
	public static function logger($content, $group='debug', $log=false){	
		// web 
		if(defined('CRON') || /*Request::cli() || */ $log == TRUE) {
			// in file
			return static::debugLog($content, $group);
		}
		// print on browser
		printf('<br> [%s]: %s', $group, static::pr($content,true));			
	}	

	/**
	 * decode to object/array if json string
	 *
	 * @param string $original
	 * @param boolean $assoc
	 * @return mixed
	 */
	public static function jsonDecoded($original, $assoc=true){
    	// return as it is if not string
		if(!is_string($original)) return $original;	
		// decoded	
		$decoded = json_decode($original, $assoc);	
		// check last error	
		return (json_last_error() == JSON_ERROR_NONE) ? $decoded : $original;
    }
	
	/**
	 * get current logged in user
	 *
	 * @todo implement Authority
	 */
	public static function getCurrentUser(){
		return Auth::user();
	}

	/**
	 * auth redirect
	 *
	 * @todo implement Authority
	 */
	public static function authRedirect(){

		if( ! Auth::check() )
			return Redirect::to('users/signin');
	}
	
	/**
	 * get formatted filesize
	 *
	 * @param int $filesize
	 * @return string $filesize_fmt 
	 * @since 1.8.0
	 */
	public static function getFilesizeFormatted($filesize, $bytes=false){
		// check
		if ((int)$filesize > 0) {
			// bytes array
			if( ! $bytes ) $bytes = array(' bytes',' KB',' MB',' GB',' TB');
			
			// loop
			foreach($bytes as $byte) {
				// check
				if($filesize > 1024){
					$filesize = $filesize / 1024;
				} else {
					break;
				}
			}
			// return	
			return round($filesize, 2) . $byte;
		}
		// return 
		return $filesize;
	}

	/**
	 * last query 
	 *
	 * @param void
	 * @return string $last_query 
	 * @since 1.0.0
	 */
	public static function lastQuery( $print=false ){
		$queries = DB::getQueryLog();
		$last = end( $queries );

		if( $print )
			return static::pr($last);

		return $last;		
	}

	/**
	 * get formatted currency
	 *
	 * @param decimal $number
	 * @param bool $rounded
	 * @return string $number_fmt 
	 * @since 1.0.0
	 */
	public static function formatCurrency( $number, $rounded=true ){
		// rounded
		if( $rounded )
			return ceil($number);
		
		return number_format($number,2,'.','');
	}

	/**
	 * get ip to location
	 *
	 * @param string $ip_address
	 * @return object $location 
	 * @since 1.0.0
	 */
	public static function ipLocation( $ip_address ){
		$geocoder = new \Geocoder\Geocoder();
		$geocoder->registerProviders(array(
		    new \Geocoder\Provider\FreeGeoIpProvider( new \Geocoder\HttpAdapter\BuzzHttpAdapter() ))
		);
		$result = $geocoder->geocode( $ip_address );

		return $result;
	}	

	/**
	 * Print with new line
	 *
	 * @param mixed
	 * @return string
	 */
	public static function println(){
		// lines
		$lines = func_get_args();
		// print
    	print implode(PHP_EOL, $lines) . PHP_EOL;
    }

    /**
	 * Get Currency rates
	 *
	 * @param mixed
	 * @return string
	 */
    public static function getCurrencyRates(){
    	// cache
		if ( Cache::has('currency_rates') )	{
			return Cache::get('currency_rates');
		}	
		// app_id
		if( App::environment() == 'production' ){
			$app_id = Config::get('site.openexchangerates.api_id_production');
			$cache_timeout = (60*6);// 360 min, 4 times a day
		}else{
			$app_id = Config::get('site.openexchangerates.api_id_sandbox');
			$cache_timeout = (60*24*1);// once a day
		}
		// url
		$url = 'http://openexchangerates.org/api/latest.json?app_id={app_id}';
		// fetch
		$request = new Client($url, array( 'app_id' => $app_id ));
		// rates
		$currency_rates = $request->get()->send()->json();
		// set in cache
		Cache::put('currency_rates', $currency_rates, $cache_timeout);
		
		// return
		return $currency_rates;
    }  

	/**
	 * Stripslashes
	 *
	 * @param string $str
	 * @return string
	 */
	public static function stripslashes($str='')
	{
		// clean till found '\'
		do{
			$str = stripslashes($str);
		}while(strpos($str, '\\') !== false);	
		// return
		return $str;
	}
	
}