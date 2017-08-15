<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SLS Newsletter Subscribe</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/dist/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/dist/css/bootstrap-theme.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/dist/css/bootstrap-theme.min.css') }}" rel="stylesheet">
	
	 <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery-1.10.2.min.js') }}"></script>	
    <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.waitforimages.min.js') }}"></script>

    <!-- Custom styles for this template -->   
	<link href="{{ asset('assets/dist/css/custom.css') }}" rel="stylesheet">
  </head>
  <body>
	
	<!--center content-->
	@yield('content')		

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>