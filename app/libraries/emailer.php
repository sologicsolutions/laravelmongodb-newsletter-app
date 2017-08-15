<?php
use \DB;
/**
 * Emailer Helpers
 *
 * @package Laravel
 * @subpackage TestApp
 */
class Emailer {
	/**
	 * send subscribe notification to user
	 *
	 * @param array $post
	 * @param string $token
	 * @return string $success
	 */
	public static function subscribeNotifyUser( $post, $token ){
		// name
		$name = implode(' ', array($post['firstname'], $post['lastname']));
		// email data
		$email_data = array('email'=>$post['email'], 'name'=>$name,'token'=>$token);
		

		// email
		try{
			Mail::send('emails.subscribe', $email_data, function($message) use ($email_data) {
				// set to
				$message->to( $email_data['email'], $email_data['name'] )->subject('Welcome to SLS Newsletter!');
				// bcc
				$message->bcc( 'priyabrata@sologicsolutions.com', 'Priyabrata Sarkar' );				
			});

			return True;
		}catch ( Exception $e ){
			print $e->getMessage();
		}	

		return False;
	}	
}	