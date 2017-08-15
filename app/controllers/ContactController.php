<?php
use \BaseController, \Input, \Session, \View, \Redirect, \Auth, \App, \User, \Contact, \DB;

/**
 * Contacts Controller
 *
 * @package Laravel
 * @subpackage TestApp
 */
class ContactController extends BaseController {	
	/**
	 * Subscribe Post Action
	 */	
	public function anySubscribe(){		
		// process			
		if ( Input::has('act') && 'save' == Input::get('act') ) {
			// posts
			$firstname = Input::get('firstname');
			$lastname  = Input::get('lastname');
			$email     = Input::get('email');
			
			// duplicate email
			$contact_exists = Contact::where('email', '=', $email)->first();	
			// check
			if( isset($contact_exists['_id']) ) {
				// error
				return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'error', 'message'=>'Email address is already signed up.') )->withInput();	
			}		
						
			// token
			$token = str_random(40);
			$now = date('Y-m-d H:i:s');
			// add a record
			$contact_data = array( 'firstname' => $firstname, 'lastname' => $lastname , 'email' => $email, 'created_at' => $now, 'token'=>$token );
			// try			
			try{				
				$contact_id = DB::collection('contacts')->insertGetId($contact_data);
			}catch(Exception $e ){				
				return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'error', 'message'=> sprintf('User subscribe failed, %s, try again!', $e->getMessage()) ) )->withInput();
			}
			// save
			if( isset($contact_id) ){
				// post
				$post = Input::get();
				// mail
				Emailer::subscribeNotifyUser( $post, $token );
				// message
				$message = 'You have signed up successfully! Please follow the activation process to complete the signup in your email.<br>'.
					       'If you do not receive email in next five minutes, please check your spam box';
				// return with message
				return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'success', 'message'=>$message) );	
			}else{
				return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'error', 'message'=>'User subscribe failed, database error, try again!') )->withInput();	
			}			
		}	
				
		// view  data setup		
		$data = array();
		// return 
		return View::make('contacts.subscribe', $data);		
	}
	
	/**
	 * Subscribers List Action
	 */	
	public function anySubscribers(){				
		// view  data setup		
		$data = array();
		// set
		$data['contacts'] = DB::collection('contacts')->get();			
		// $contacts = Contact::paginate(5);			
		// return 
		return View::make('contacts.list', $data);	
	}
	
	/**
	 * Delete Action
	 * 
	 * @param int $id
	 */	
	public function anyDelete( $id = NULL){	
		// check
		if(!empty($id)){
			try{
				// delete
				DB::collection('contacts')->where('_id','=',$id)->delete();
				// return with message
				return Redirect::to('contacts/subscribers')->with( 'errors', array('status'=>'success', 'message'=>'Contact deleted successfully') );
			}catch(Exception $e){}		
		}
		// error
		return Redirect::to('contacts/subscribers')->with( 'errors', array('status'=>'error', 'message'=>'Contact subscdeleteribe failed, database error, try again!') );				
	}
	
	/**
	 * Verify Action
	 *
	 * @param string $token
	 */	
	public function anyVerify($token){	
		// check
		if( $token ) {				
			try{
				$contact = Contact::where('token', '=', $token)->first();			
				// check
				if( isset( $contact['_id']) ){
					// data
					$data = array('token'=>'', 'verify_at'=>date('Y-m-d H:i:s'));					
					// update
					$affected = DB::collection('contacts')->where('token', '=', $token)->update($data);
					// check
					if( $affected ){
						// return with message
						return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'success', 'message'=>'You have successfully verified your email') );	
					}else{
						return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'error', 'message'=>'Email verification failed') );	
					}	
				}
			}catch(Exception $e){}	
			// error
			return Redirect::to('contacts/subscribe')->with( 'errors', array('status'=>'error', 'message'=>'Email verification failed, no such token!') );
		}
	}	
}