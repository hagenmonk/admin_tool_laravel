<!DOCTYPE html>
<html>
    <head>
        <title>Player data</title>

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
        <div class="title">Player Data</div>
        <hr>
	    
	    <?php 
	       echo Form::open(array('url' => 'admin/playerdata'));
	       echo "Player id:".Form::text('player_id'); 
	       echo Form::submit('submit');
	    ?>
	    
	    <table class = "table table-striped">
	    <?php 
		    if(is_array($results))
		    {
		      foreach( $results as $num_index => $result)
			  {
			      $column_name = '';
			      $column_value='';
				  foreach( $result as $column_index => $column)
				  {
				     if($num_index ==0)
						$column_name .= "<th>".$column_index.'</th>';
					 $column_value.= '<td>'.$column.'</td>';
				  }
				  if($num_index==0)
				    echo '<thead><tr>'.$column_name.'</tr></thead>';
				  echo '<tr>'.$column_value.'</tr>';
			  }
		    }
		?>
		</table>
		</div>
        </div>
    </body>
</html>
