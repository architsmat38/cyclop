<html>
<head>
	<title>Cyclop - Indian Cycling Community</title>
	<link rel='stylesheet' href='css/app.css' />
	<link rel='stylesheet' href='css/semantic.css' />
	<link rel='stylesheet' href='css/app/all.css' />
	<link rel='icon' href='{{ asset("images/favicon.png") }}' />

	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/semantic.js"></script>
	<script type="text/javascript" src="js/app/all.js"></script>
</head>
<body>
	@include('includes.header')
	@yield('content')
	@include('includes.footer')
</body>
</html>