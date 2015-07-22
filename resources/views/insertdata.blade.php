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
	       echo Form::open(array('url' => 'admin/', 'isSend'));
	       echo trans('messages.text_insert_log_to');
	    ?>
	    <input type="date" name="log_date" style="height:30px">
	    <?php 
	    	echo trans('messages.text_insert_command');
		echo Form::text('log_command');
		echo Form::submit(trans('messages.submit')); 
	    ?>
	    <table class = "table table-striped">
	    <tr><td>
	    <?php
	    	echo $results;
	    ?>
	    </td></tr>
	    	</table>
        </div>
    </body>
</html>
