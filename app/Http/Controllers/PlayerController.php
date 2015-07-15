<?php 

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use View;
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
	    {
	        $results = DB::table('player')->where('player_id',$player_id)->get();
	    }
	    else
		  $results = DB::table('player')->get();
		
		
		return view('player.index' , ['results'=>$results]);
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
	    
	    
	}

}
