<!DOCTYPE html>
<html>
    <head>
        <title>Insert Data</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
            }

            .title {
                font-size: 40px;
            	 display: table-cell;
            }
        </style>
    </head>
    <body>
        <div class="title">Insert Data</div>
        <hr>
	    
	    <?php 
	       echo Form::open(array('url' => 'admin/'));
	    ?>
	    匯入log檔到<input type="date" name="log_date">之前
	    <?php echo Form::submit('匯入'); ?>
	    <table class = "table table-striped">
	    <?php //echo $results; ?>
		</table>
		</div>
        </div>
    </body>
</html>
