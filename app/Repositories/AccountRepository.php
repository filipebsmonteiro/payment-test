<?php


namespace App\Repositories;


use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountRepository extends Repository
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $filters
     * @return Collection|LengthAwarePaginator
     */
    public function findByRequestFilters(array $filters = [])
    {
        $filters = issetAndNotNullNotEmpty($this->request->filters) ? $this->request->filters : $filters;

        foreach ($filters as $index => $filter) {

            $filter = is_array($filter) ? $filter : json_decode($filter);
            $this->Query->when($filter[0] === 'user_id', function ($query) use ($filter) {

                return $query->join('users_has_accounts', function ($join) use ($filter) {
                    $join->on('users_has_accounts.account_id', '=', 'accounts.id')
                        ->where('users_has_accounts.user_id', '=', $filter[2]);
                });

            });

        }

        $this->executeQuery();
        return $this->Results;
    }
}
