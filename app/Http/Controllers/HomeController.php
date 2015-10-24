<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Classes\Pio;

class HomeController extends BaseController
{


	public function index(Pio $pio){

		$user_id = uniqid();

		session(array('user_id' => $user_id, 'movies_viewed' => 0));

		$pio_eventclient = $pio->eventClient();
		$pio_eventclient->setUser($user_id);

		return view('index');
	}


	public function randomMovie(Request $request, Pio $pio){
	
		if(session('user_id')){

			$movies_viewed = session('movies_viewed');

			$es_client = new \Elasticsearch\Client();

			$search_params['index'] = 'movierecommendation_app';
			$search_params['type']  = 'movie';
			$search_params['id'] = mt_rand(1, 1000);
			$movie = $es_client->get($search_params);
			
			if(!empty($request->input('movie_id'))){				

				$user_id = session('user_id');
				$movie_id = $request->input('movie_id');
				$action = $request->input('action');

				$pio_eventclient = $pio->eventClient();
				$pio_eventclient->recordUserActionOnItem($action, $user_id, $movie_id);

				$movies_viewed += 1;
				if($movies_viewed == 20){
					$movie['has_recommended'] = true;			
				}
				$movie['movies_viewed'] = $movies_viewed;

				session(array('movies_viewed' => $movies_viewed));
			}
			
			return $movie;
		}

	}



	public function recommendedMovies(Pio $pio){

		$recommended_movies = array();

		try{
			$user_id = session('user_id');
			
			$pio_predictionclient = $pio->predictionClient();
			$recommended_movies_raw = $pio_predictionclient->sendQuery(array('user' => $user_id, 'num' => 9));
					   
			$movie_ids = array_map(function($item){
				return $item['item'];
			}, $recommended_movies_raw['itemScores']);   
			
			$es_client = new \Elasticsearch\Client();
			
			$search_params['index'] = 'movierecommendation_app';
			$search_params['type']  = 'movie';
			$search_params['body']['query']['bool']['must']['terms']['_id'] = $movie_ids;

			$es_response = $es_client->search($search_params);
			$recommended_movies = $es_response['hits']['hits'];
			
		}catch(Exception $e){
		    echo 'Caught exception: ', $e->getMessage(), "\n";
		}

		session(array('movies_viewed' => 0, 'user_id' => null));

		return view('recommended_movies', array('recommended_movies' => $recommended_movies));

	}

}
