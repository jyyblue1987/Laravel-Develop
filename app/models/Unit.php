<?php 

use Carbon\Carbon;

class Unit extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_unit';
	
	protected $fillable = array('floor', 'room', 'floorplan', 'info', 'reservestate_id', 'reserve_id', 'project_id');	
	
	public function project()
    {
		return $this->belongsTo('Project');        
    }
	
	public function reserve()
    {
        return $this->belongsTo('Reserve');
    }
	
	public function reservestate()
    {
        return $this->belongsTo('ReserveState');
    }


	public static function boot()
    {
        parent::boot();

        // Setup event bindings...
		Unit::created(function($unit)
		{
			
			return true;
		});		
		
		Unit::deleted(function($unit)
		{
			Unit::onDeleteUnit($unit);
			return true;
		});	
    }
	
	public static function onCreateUnit($unit)
	{
		$start = (new Carbon('now'))->hour(0)->minute(0)->second(0);
		$end = (new Carbon('now'))->hour(23)->minute(59)->second(59);
					
		$unithistory = UnitHistory::where('project_id', '=', $unit->project_id)
							->whereBetween('created_at', [$start , $end])->first();
		$prevhistory = UnitHistory::where('project_id', '=', $unit->project_id)
							->orderBy('created_at', 'DESC')->first();
		
		if( empty($unithistory) )
			$unithistory = new UnitHistory();			
		
		$unithistory->project_id = $unit->project_id;
		
		
		if( empty($prevhistory) )
		{
			switch($unit->reservestate_id)
			{
				case '1';	// Available
					$unithistory->available_count = 1;
					$unithistory->reserved_count = 0;
					$unithistory->sold_count = 0;
					break;
				case '2';	// Reserve
					$unithistory->available_count = 0;
					$unithistory->reserved_count = 1;
					$unithistory->sold_count = 0;
					break;
				case '3';	// Sold
					$unithistory->available_count = 0;
					$unithistory->reserved_count = 0;
					$unithistory->sold_count = 1;
					break;
			}			
		}
		else
		{
			switch($unit->reservestate_id)
			{
				case '1';	// Available
					$unithistory->available_count = $prevhistory->available_count + 1;
					$unithistory->reserved_count = $prevhistory->reserved_count;
					$unithistory->sold_count = $prevhistory->sold_count;
					break;
				case '2';	// Reserve
					$unithistory->available_count = $prevhistory->available_count;
					$unithistory->reserved_count = $prevhistory->reserved_count + 1;
					$unithistory->sold_count = $prevhistory->sold_count;
					break;
				case '3';	// Sold
					$unithistory->available_count = $prevhistory->available_count;
					$unithistory->reserved_count = $prevhistory->reserved_count;
					$unithistory->sold_count = $prevhistory->sold_count + 1;
					break;
			}			
		}				
		
		$unithistory->save();
	}
	
	
	public static function onUpdateUnit($unit, $prevstate)
	{
		$start = (new Carbon('now'))->hour(0)->minute(0)->second(0);
		$end = (new Carbon('now'))->hour(23)->minute(59)->second(59);
					
		$unithistory = UnitHistory::where('project_id', '=', $unit->project_id)
							->whereBetween('created_at', [$start , $end])->first();
		$prevhistory = UnitHistory::where('project_id', '=', $unit->project_id)
							->orderBy('created_at', 'DESC')->first();
		
		if( empty($unithistory) )
			$unithistory = new UnitHistory();			
		
		$unithistory->project_id = $unit->project_id;
		
		
		if( empty($prevhistory) )
		{
			switch($unit->reservestate_id)
			{
				case '1';	// Available
					$unithistory->available_count = 1;
					$unithistory->reserved_count = 0;
					$unithistory->sold_count = 0;
					break;
				case '2';	// Reserve
					$unithistory->available_count = 0;
					$unithistory->reserved_count = 1;
					$unithistory->sold_count = 0;
					break;
				case '3';	// Sold
					$unithistory->available_count = 0;
					$unithistory->reserved_count = 0;
					$unithistory->sold_count = 1;
					break;
			}			
		}
		else
		{
			$available_count = $prevhistory->available_count;
			$reserved_count = $prevhistory->reserved_count;
			$sold_count = $prevhistory->sold_count;
			
			switch($unit->reservestate_id)
			{
				case '1';	// Available
					$available_count = $available_count + 1;					
					break;
				case '2';	// Reserve
					$reserved_count = $reserved_count + 1;
					break;
				case '3';	// Sold
					$sold_count = $sold_count + 1;
					break;
			}			
			
			switch($prevstate)
			{
				case '1';	// Available
					$available_count = $available_count - 1;					
					break;
				case '2';	// Reserve
					$reserved_count = $reserved_count - 1;
					break;
				case '3';	// Sold
					$sold_count = $sold_count - 1;
					break;
			}		
			
			$unithistory->available_count = $available_count;
			$unithistory->reserved_count = $reserved_count;
			$unithistory->sold_count = $sold_count;
		}				
		
		$unithistory->save();
	}
	
	public static function onDeleteUnit($unit)
	{
		$start = (new Carbon('now'))->hour(0)->minute(0)->second(0);
		$end = (new Carbon('now'))->hour(23)->minute(59)->second(59);
					
		$unithistory = UnitHistory::where('project_id', '=', $unit->project_id)
							->whereBetween('created_at', [$start , $end])->first();
		$prevhistory = UnitHistory::where('project_id', '=', $unit->project_id)
							->orderBy('created_at', 'DESC')->first();
		
		if( empty($unithistory) )
			$unithistory = new UnitHistory();			
		
		$unithistory->project_id = $unit->project_id;
		
		
		if( empty($prevhistory) )
		{
			$unithistory->available_count = 0;
			$unithistory->reserved_count = 0;
			$unithistory->sold_count = 0;				
		}
		else
		{
			$available_count = $prevhistory->available_count;
			$reserved_count = $prevhistory->reserved_count;
			$sold_count = $prevhistory->sold_count;
			
			switch($unit->reservestate_id)
			{
				case '1';	// Available
					$available_count = $available_count - 1;					
					break;
				case '2';	// Reserve
					$reserved_count = $reserved_count - 1;
					break;
				case '3';	// Sold
					$sold_count = $sold_count - 1;
					break;
			}			
			
			$unithistory->available_count = $available_count;
			$unithistory->reserved_count = $reserved_count;
			$unithistory->sold_count = $sold_count;
		}				
		
		$unithistory->save();
	}
	
	
	
}