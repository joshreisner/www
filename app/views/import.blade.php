<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title>Import</title>
        <link rel="stylesheet" href="/assets/css/main.min.css">
    </head>
    <body>
    	<div class="container">
    		<form>
    		<div class="row">
    			<div class="col-md-10" style="margin:20px 0">
    				{{ Form::select('service', $services, 'goodreads', ['class'=>'form-control']) }}
    			</div>
    			<div class="col-md-2" style="margin:20px 0">
    				<a class="btn btn-primary btn-block">Import</a>
    			</div>
    		</div>
	    	</form>
	    	<div class="row">
	    		<div class="col-md-12">
	    			<pre style="height:400px;overflow-y:scroll"></pre>
	    		</div>
	    	</div>
	    </div>
	    <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
	    <script>
	    	$(function(){
	    		$("a").click(function(){
	    			$("pre").text("Fetching...");
	    			$.get("/import/" + $("select").val(), function(data) {
	    				$("pre").text(data);
	    			});
	    		})
	    	})
	    </script>
    </body>
</html>