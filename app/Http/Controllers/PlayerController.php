<?php 

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Storage;
use DB;
use Schema;
use Illuminate\Routing\Route;

class PlayerController extends Controller {


	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
	    $player_id = Input::get('player_id');
	    
	    if($player_id!='')
	        $results = DB::connection('mysql_player')->table('player')->where('player_id',$player_id)->get();
	    else
	    	$results = DB::connection('mysql_player')->table('player')->get();
		
	    $html_table = self::set_htmltable($results);
		
		return view('player.index' , ['results'=>$html_table]);
	}

	public function login_index()
	{
		$results = DB::connection('mysql_log')->table('login_log')->get();
		$html_table = self::set_htmltable($results);
		
		return view('player.login' , ['results'=>$html_table]);
	}
	
	public function logout_index()
	{
		$results = DB::connection('mysql_log')->table('logout_log')->get();
		$html_table = self::set_htmltable($results);
		
		return view('player.logout' , ['results'=>$html_table]);
	}

	public function insert_data_index()
	{
	    $log_date = Input::get('log_date');
	    $log_command = Input::get('log_command');
	    $log_path =  "/home/log";
	    $result = 0;
	    
	    //if post form then insert data
	    if(Input::get('_token'))
	    {
	    	//convert all log to db
	    	$result = self::insert_log_data($log_path,$log_date,$log_command);
	    }
	    
	    return view('insertdata' , ['results'=> self::convert_result_word($result) ]);
	}
	
	private function convert_result_word($result)
	{	
		switch($result){
			case 0:
				return '';
			break;
			case 1:
				return trans('messages.false');
			break;
			case 2:
				return trans('messages.success');
			break;
			case 3:
				return trans('messages.error_not_found_command');
			break;
		}
		
		return $result;
	}
	
	private function insert_log_data($log_path ,$log_date, $log_command)
	{
	
		$cur_date = date("Ymd",strtotime($log_date));
		$file_dates = scandir($log_path);
		
		if($log_command!='')
			$result = 3;
		else
			$result = 1;
			
			//get all dates folder
	    	foreach($file_dates as $id => $date)
	    	{
			//if have log_date just insert until this folder
			if($date == $cur_date)
	    			break;
		
	    		//if id >1 then it won't be '.' ot '..'
	    	    if($id>1)
	    	    {
		    		//get all filess
					$files = scandir($log_path.'/'.$date);
					foreach($files as $file)
					{
			    	    		$logfile = fopen($log_path.'/'.$date.'/'.$file, "r") or die("Unable to open file!");
				 		$insert_data = array();	
		    		    		$tablename = explode('.',$file);
				    
				    
						while(!feof($logfile)) 
						{
							//delete
					
						  	$column_string = json_decode(fgets($logfile));
							$column_string = (array)$column_string[0];
							
							if(!empty($column_string) && ($log_command=='' || $log_command == $column_string['command']))
							{
								array_push($insert_data,$column_string);
							}
			    			}
						
						if(!empty($insert_data))
						{
							if (!Schema::connection('mysql_log')->hasTable($tablename[0]))
							{
								Schema::connection('mysql_log')->create($tablename[0], function($table) use($insert_data)
								{
								    $table->increments('id');
								    foreach($insert_data[0] as $column_name => $column_value)
								    {
								    
								    	if($column_name == 'date')
								        	$table->dateTime('date');
									else if(is_int($column_value))
										$table->int($column_name);
									else
										$table->string($column_name);
								    }
								});
							}
							else
							{
								
								if($log_date!='')
								{
									DB::connection('mysql_log')->delete("delete from $tablename[0] where date >='$cur_date'");
								}
								else
								{
									DB::connection('mysql_log')->delete("delete from $tablename[0] "); 
								}
							}
							
							DB::connection('mysql_log')->table($tablename[0])->insert($insert_data);
							$result = 2;
						}
					}
		    	}
	    	}
		
		return $result;
	}
	
	private function set_htmltable($datas)
	{
	    $htmlTable = "";
	    
	    if(is_array($datas))
	    {
	        $column_title = '';
	        
	        foreach( $datas as $num_index => $data)
	        {
	            $column_value='';
	            if($num_index==0)
	            {    
    	            foreach( $data as $column_index => $column)
    	            {
    	                $column_title .= "<th>".$column_index.'</th>';
    	                $column_value.= '<td>'.$column.'</td>';
    	            }
    	            
    	            $htmlTable .= '<thead>'.$column_title.'</thead>'.'<tr>'.$column_value.'</tr>';
	            }
	            else 
	            {
	                foreach( $data as $column_index => $column)
	                    $column_value.= '<td>'.$column.'</td>';
	                 
	                $htmlTable .= '<tr>'.$column_value.'</tr>';
	            }
	       }
	    }
	    
	    return $htmlTable;
	}

}
