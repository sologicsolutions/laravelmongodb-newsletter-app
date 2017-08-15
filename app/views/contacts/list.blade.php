@extends('layouts.default')
  
  @section('content')	
  	<!-- container -->
    <div class="container" style="width:100%">	
		<h2 class="form-signin-heading">Subscribers List</h2>
		{{ Util::displayNotices() }}  
		<table class="table table-striped" width="100%">
			<thead>
				<tr>
					<th>ID#</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Username/Email</th>
					<th>Token</th>
					<th>Subscribe Date</th>
					<th>Verify Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			@foreach( $contacts as $contact )
				<tr>
					<td>{{ $contact['_id'] }}</td>
					<td>{{ $contact['firstname'] }}</td>
					<td>{{ $contact['lastname'] }}</td>
					<td>{{ $contact['email'] }}</td>
					<td>{{ $contact['token'] }}</td>
					<td>{{ $contact['created_at'] }}</td>
					<td>
						@if( isset($contact['verify_at']) )
							{{ $contact['verify_at'] }}
						@else
							N/A	
						@endif	
					</td>
					<td><a href="javascript:delete_contact('{{ $contact['_id'] }}')" ><img src="{{ asset('assets/images/delete.png') }}" /></a></td>
				</tr>
			@endforeach                
			</tbody>
		</table> 
		<div class="clearfix"></div>
	</div> <!-- /container -->		
    <script language="javascript">
		 delete_contact=function(id){			
			if ( confirm("Are you sure you want to delete the contact?") ){
				window.location = '{{ URL::to('contacts/delete') }}/' + id;
			}	
		}
	</script>
  @endsection
