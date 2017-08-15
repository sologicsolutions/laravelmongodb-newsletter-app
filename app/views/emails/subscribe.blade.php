<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Welcome {{ $name }}</h2>

		<div>
			To activate your account, follow this link: 
			<a href="{{ URL::to('contacts/verify', array($token)) }}" target="_blank">{{ URL::to('contacts/verify', array($token)) }}</a>.
			<p>If you are unable to follow the above link, please copy paste the link in your URL bar.</p>
		</div>

		<p>Test App</p>
	</body>
</html>