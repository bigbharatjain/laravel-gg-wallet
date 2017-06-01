<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<br>
	<br>
	<center><h1>Your transaction is being processed!!!</h1></center>
	<center><h2>Please do not refresh this page...</h2></center>
		<form method="post" action="{{$txn_url}}" name="gg">
		<table border="1">
			<tbody>
				@foreach ($params as $key => $value)
					<input type="hidden" name="{{$key}}"  value="{{$value}}" />
				@endforeach
				<input type="hidden" name="SIGNATURE" value="{{$checkSum}}">
			</tbody>
		</table>
		<script type="text/javascript">
			document.gg.submit();
		</script>
	</form>
</body>
</html>