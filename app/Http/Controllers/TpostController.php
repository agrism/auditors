<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tcategory;
use App\Tpost;

use App\Http\Requests\Request;
use Input;
use Debugbar;

class TpostController extends Controller
{

	/**
	 * Show the profile for the given user.
	 *
	 * @param  int  $id
	 *
	 * @return Response
	 */
	public function index()
	{
		$selectedCatIds = [];
		if (Input::has('cat')) {
			$selectedCatIds = Input::get('cat');
			$category = Tcategory::with(
				[
					'tpost' => function ($q) use ($selectedCatIds) {
						$q->whereIn('tcategory_id', $selectedCatIds);
					},
				]
			)->get();
		} else {

			$category = Tcategory::with(['tpost'])->get();
		}
		//Debugbar::warning('Watch out…');
		//Debugbar::info( json_decode($category) );
		//Debugbar::info( get_defined_vars());
		//Debugbar::info( json_decode(get_defined_vars() ) );

		//Debugbar::info( get_defined_vars() ) ;

		// return get_defined_vars() ;

		// foreach(get_defined_vars()  as $vars){
		// 	if(is_object($vars)){
		// 		Debugbar::info( $vars->toArray() );
		// 	} else{
		// 		Debugbar::info( $vars );
		// 	}
		// }


		return view('tposts')->with('category', $category)->with(
			'selectedCategories', $selectedCatIds
		);
	}


}