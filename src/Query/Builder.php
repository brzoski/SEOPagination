<?php namespace AgelxNash\SEOPagination\Query;

use AgelxNash\SEOPagination\Paginator\Paginator;
use AgelxNash\SEOPagination\Paginator\LengthAwarePaginator;

class Builder extends \Illuminate\Database\Query\Builder{
	/**
	 * Paginate the given query into a simple paginator.
	 *
	 * @param  int  $perPage
	 * @param  array  $columns
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function paginate($perPage = 15, $columns = ['*'])
	{
		$page = Paginator::resolveCurrentPage();

		$total = $this->getCountForPagination($columns);

		$results = $this->forPage($page, $perPage)->get($columns);

		return new LengthAwarePaginator($results, $total, $perPage, $page, [
			'path' => Paginator::resolveCurrentPath(),
		]);
	}

	/**
	 * Get a paginator only supporting simple next and previous links.
	 *
	 * This is more efficient on larger data-sets, etc.
	 *
	 * @param  int  $perPage
	 * @param  array  $columns
	 * @return \Illuminate\Contracts\Pagination\Paginator
	 */
	public function simplePaginate($perPage = 15, $columns = ['*'])
	{
		$page = Paginator::resolveCurrentPage();

		$this->skip(($page - 1) * $perPage)->take($perPage + 1);

		return new Paginator($this->get($columns), $perPage, $page, [
			'path' => Paginator::resolveCurrentPath(),
		]);
	}
}