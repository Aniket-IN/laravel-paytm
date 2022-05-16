<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<br>
	<br>
	<center><h1>Your transaction is being processed!!!</h1></center>
	<center><h2>Please do not refresh this page...</h2></center>
	
    <form name="f1" hidden action="{{ $txn_url }}">
        @foreach ($params as $key => $value)
            <input name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>

    <script type="text/javascript">
        document.f1.submit();
    </script>
</body>
</html>