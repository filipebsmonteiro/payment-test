<?php


namespace App\Repositories;

use App\Http\Traits\AuthTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Repository
{
    use AuthTrait;

    /** @var Model */
    protected Model $Model;

    /** @var Builder */
    protected Builder $Query;

    /** @var Collection|LengthAwarePaginator */
    protected $Results;

    public function __construct(Model $model)
    {
        $this->Model = $model;
        $this->Query = $this->Model->newQuery();
        $this->loadRequest();
    }

    public function setRequestFilters(array $filters)
    {
        $this->request->filters = $filters;
    }

    public function addFilters(array $filters): void
    {
        foreach ($filters as $index => $filter) {
            $filter = is_array($filter) ? $filter : json_decode($filter);
            if ($filter[1] === 'IN' || $filter[1] === 'in') {
                $this->Query = $this->Query->whereIn($filter[0], $filter[2]);
                continue;
            }
            $this->Query = $this->Query->where([$filter]);
        }
    }

    protected function prepareQuery(): void
    {
        if (issetAndNotNullNotEmpty($this->request->filters)) {
            $this->addFilters($this->request->filters);
        }
        if (issetAndNotNullNotEmpty($this->request->orderBy)) {
            $this->Query = $this->Query->orderBy($this->request->orderBy);
        }
    }

    protected function executeQuery(): void
    {
        if (issetAndNotNullNotEmpty($this->request->per_page)) {
            $this->Results = $this->Query->paginate($this->request->per_page);
            return;
        }
        $this->Results = $this->Query->get();
    }

    /**
     * @return Collection|LengthAwarePaginator
     */
    public function findByRequestFilters()
    {
        $this->prepareQuery();
        $this->executeQuery();
        return $this->Results;
    }

    /**
     * @param array $filters
     * @return Collection|LengthAwarePaginator
     */
    public function findByFilters(array $filters)
    {
        $this->request->filters = $filters;
        $this->prepareQuery();
        $this->executeQuery();
        return $this->Results;
    }

    public function store(array $attributes): Model
    {
        return $this->Query->create($attributes);
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findById($id)
    {
        if (is_array($id)) {
            return $this->Query->whereIn('id', $id)->get();
        }
        return $this->Query->find($id);
    }

    public function update(array $attributes, $id): Model
    {
        $entity = $this->Query->find($id);
        $entity->update($attributes);

        return $this->Query->find($id);
    }

    public function destroy($id): ?bool
    {
        return $this->Query->find($id)->delete();
    }
}
