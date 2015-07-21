<?php 

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Storage;
use DB;
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
	        $results = DB::table('player')->where('player_id',$player_id)->get();
	    else
	    	$results = DB::table('player')->get();
		
	    $html_table = self::setHtmlTable($results);
		
		return view('player.index' , ['results'=>$html_table]);
	}

	public function login_index()
	{
		$results = DB::connection('mysql2')->table('login_log')->get();
		$html_table = self::setHtmlTable($results);
		
		return view('player.login' , ['results'=>$html_table]);
	}
	
	public function logout_index()
	{
		$results = DB::connection('mysql2')->table('login_log')->get();
		$html_table = self::setHtmlTable($results);
		
		return view('player.logout' , ['results'=>$html_table]);
	}

	public function insert_data_index()
	{
	    $results = "";
	    $log_date = Input::get('log_date');
	    $log_path =  "/home/log";
	    $cur_date = date("Ymd",strtotime($log_date));
	    
	    //convert all log to db
	    if($log_date=='')
	    {
	    	$file_dates = scandir($log_path);
			
			//get all dates folder
	    	foreach($file_dates as $id => $date)
	    	{
	    		//if id >1 then it won't be '.' ot '..'
	    	    if($id>1)
	    	    {
		    		//get all filess
					$files = scandir($log_path.'/'.$date);
					foreach($files as $file)
					{
			    	    $logfile = fopen($log_path.'/'.$date.'/'.$file, "r") or die("Unable to open file!");
						
		    		    $tablename = explode('.',$file);
						while(!feof($logfile)) 
						{
						  	$column_string = json_decode(fgets($logfile));
							if(!empty($column_string))
							{	
								DB::connection('mysql2')->table($tablename[0])->insert((array)$column_string[0]);
							}
			    		}
					}
		    	}
	    	}
	    }
	    else
	    {
	    	$file_dates = scandir($log_path);
			
			//get all dates folder
	    	foreach($file_dates as $id => $date)
	    	{

	    		if($date == $cur_date)
	    			break;

	    		//if id >1 then it won't be '.' or '..'
	    	    if($id>1)
		    	{
		    		//get all filess
					$files = scandir($log_path.'/'.$date);
					foreach($files as $file)
					{
			    	    $logfile = fopen($log_path.'/'.$date.'/'.$file, "r") or die("Unable to open file!");
						
		    		    $tablename = explode('.',$file);
						while(!feof($logfile)) 
						{
						  	$column_string = json_decode(fgets($logfile));
							if(!empty($column_string))
							{	
								DB::connection('mysql2')->table($tablename[0])->insert((array)$column_string[0]);
							}
			    		}
					}
		    	}
	    	}
	    }
	    
	    return view('insertdata' , ['results'=>$file_years]);
	}
	
	private function setHtmlTable($datas)
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
