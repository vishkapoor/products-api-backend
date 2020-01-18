<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{

	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json([
			'error' => $message,
			'code' => $code,
		], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{

		if($collection->isEmpty()) {
			return $this->successResponse([
				'data' => $collection
			], $code);
		}

		$resource = $collection->first()->resourceCollection;
		$collection = $this->filterData($collection, $resource);
		$collection = $this->sortData($collection, $resource);
		$collection = $this->paginate($collection);
		$collection = $this->cacheResponse($collection);
		$collection = $this->transformData($collection, $resource);

    	return $this->successResponse([
			'data' => $collection
		], $code);
	}

	protected function transformData($data, $resource)
	{
		return (new $resource($data));
	}

	protected function showOne(Model $instance, $code = 200)
	{
		$resource = $instance->resource;

		$instance = $this->transformData($instance, $resource);

		return $this->successResponse([
			'data' => $instance
		], $code);
	}


	protected function showMessage($message, $code = 200)
	{
		return $this->successResponse([
			'data' => $message
		], $code);
	}

	protected function sortData(Collection $collection, $resource)
	{
		if(request()->has('sort_by')
			&& !empty(request()->sort_by)) {

			$attribute = request()->sort_by;

			$collection = $collection->sortBy($resource::originalAttribute($attribute));
		}

		return $collection;
	}

	protected function filterData(Collection $collection, $resource)
	{
		foreach(request()->query() as $query => $value) {
			$attribute = $resource::originalAttribute($query);
			if(isset($attribute, $value)){
				$collection = $collection->where($attribute, $value);
			}
		}

		return $collection;
	}

	public function paginate(Collection $collection)
	{
		$rules = [
			'per_page' => 'integer|min:2|max:50',

		];

		Validator::validate(request()->all(), $rules);

		$page = LengthAwarePaginator::resolveCurrentPage();
		$perPage = isset(request()->per_page) ? request()->per_page : 15;
		$results = $collection->slice(($page-1) * $perPage, $perPage )->values();
		$paginated = new LengthAwarePaginator(
			$results,
			$collection->count(),
			$perPage,
			$page, [
				'path' => LengthAwarePaginator::resolveCurrentPath()
			]);

		$paginated->appends(request()->all());

		return $paginated;
	}

	protected function cacheResponse($data)
	{
		$queryParams = request()->query();
		ksort($queryParams);
		$queryString = http_build_query($queryParams);
		$url = request()->url() . '?' . $queryString;

		return Cache::remember($url, 30, function() use($data) {
			return $data;
		});
	}

}