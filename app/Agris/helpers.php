<?php

function sort_employees_by($column, $body, $params =[]){

	// dd($params);
	if(! isset($params['sort'])) {
		$params['sort']=[];
	}

	$key = array_search($column, array_column($params['sort'], 'orderBy' ));

	$currentColumnDirection = false;
	if(is_int($key)){
		$currentColumnDirection = isset($params['sort'][$key]['direction']) ? $params['sort'][$key]['direction'] : false;
	}



	if($currentColumnDirection == 'asc'){
		$faSort = ' fa-sort-down';
		$newColumnDirection = 'desc';
	} elseif($currentColumnDirection == 'desc'){
		$faSort = ' fa-sort-up';
		$newColumnDirection = 'asc';
	} else{
		$faSort = ' fa-sort';
		$newColumnDirection = 'asc';
	}


	array_unshift($params['sort'], ['orderBy'=>$column, 'direction'=>$newColumnDirection] ); // push first current column

	$params = removeColumnDuplicates($params);


	return link_to_route1('client.invoices.index', $body.'&nbsp;<div class="fa '.$faSort.'"></div>', $params  );
}


function link_to_route1($route, $body, $params =[])
{
	
	// dd($params);
	$m = '<a href="'.url(route($route, $params)).'">'. $body. '</a>'; 
	return $m;
}

function removeColumnDuplicates($params){
	$array = [];
	foreach($params['sort'] as $key =>$val){
		if(in_array($val['orderBy'], $array  )  ){
			unset($params['sort'][$key]);
		}
		$array[] = $val['orderBy'];
	}
	$params['sort'] =  array_merge($params['sort']);
	return $params;
}