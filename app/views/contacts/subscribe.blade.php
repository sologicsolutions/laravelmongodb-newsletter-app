  @extends('layouts.default')
  
  @section('content')	  
  	<!-- container -->
    <div class="container" style="width:40%">	
		  {{ Form::open( array('url'=> 'contacts/subscribe','method'=>'POST','name'=>'signupform','id'=>'signupform')) }}
			<h2 class="form-signin-heading">Please Subscribe to Our Newsletter</h2>
			{{ Util::displayNotices() }}
			<p>
				<label>First name:</label>
				{{ Form::text('firstname', '', array('placeholder'=>'Enter first name','maxlength'=>'50','class'=>'form-control')) }}
			</p>
			<p>
				<label>Last name:</label>
				{{ Form::text('lastname', '', array('placeholder'=>'Enter last name','maxlength'=>'50','class'=>'form-control')) }}
			</p>
			<p>
				<label>Email:</label>
				{{ Form::text('email', '', array('placeholder'=>'Enter email address','maxlength'=>'100','class'=>'form-control')) }}
			</p>
			<br>
			{{ Form::submit('Sign Up', array('class'=>'btn btn-lg btn-primary btn-block')) }}			
			
		 {{ Form::hidden('act', 'save') }}	
		 {{ Form::close() }}
	</div> <!-- /container -->	
	<script type="text/javascript">	
	 <!--		
		jQuery(document).ready(function(){
		// validate signup form on keyup and submit
			var validator = jQuery("#signupform").validate({
				rules: {				
					firstname: "required",
					//lastname: "required",				
					email: {required: true, email: true}				
				},
				messages: {			  	
					firstname: "Enter first name",							
					email: {required: "Enter email address", email: "Enter a valid email address"}				
				},
				errorPlacement: function(error, element) {		       
					error.insertAfter(element.parent().find('label:first'));    
				},
				errorElement: 'em'
			});
		});
	 //-->		
	</script>
 	
 @endsection
