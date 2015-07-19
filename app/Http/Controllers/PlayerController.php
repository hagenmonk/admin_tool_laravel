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
	
	public function insert_data_index()
	{
	    $results = "";
	    $log_date = Input::get('log_date');
	    
	    
	    //convert all log to db
	    if($log_date=='')
	    {
	    	$log_path =  "/home/log";
	    	$file_years = scandir($log_path);
		$sql = "";
		
	    	foreach($file_years as $id => $year)
	    	{
	    	    if($id>1)
		    {
		    	$months = scandir($log_path.'/'.$year);
		    	foreach($months as $m_id => $month)
	    		{
			    if($m_id>1)
			    {
			    	$files = scandir($log_path.'/'.$year.'/'.$month);
			    	foreach($files as $file)
				{
		    	    		$logfile = fopen($log_path.'/'.$year.'/'.$month.'/'.$file, "r") or die("Unable to open file!");
					
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
	    	}
	    }
	    else
	    {
	    	$root =  "/home/log";
	    	$file_years = scandir($root);
	    
	    	foreach($file_years as $year)
	    	{
	    	    if(strpos($year,'.')==false)
		    {
		    	
		    }
	    	}
	    }
	    
	    if(DB::table('player')->first()=='')
	    {
	    	
	    }
	    
	  /*    
	    $logfile = fopen("login_log_20150706.log", "r") or die("Unable to open file!");
	    while(!feof($logfile)) {
		  $results .= fgets($logfile) ;
	    }
	    */
	    
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
