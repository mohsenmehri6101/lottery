<?php

namespace App\Http\Repositories;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use function app;
use function request;

abstract class BaseRepository
{
    protected Model $model;
    protected static int $static_default_per_page = 15;
    protected static bool $static_default_paginate = true;
    public static string $static_default_order_by_column = 'id';
    public static string $static_default_direction_order = 'desc';

    protected int $default_per_page;
    protected bool $default_paginate;

    public function __construct()
    {
        $this->model = app($this->model());

        $this->default_paginate = self::$static_default_paginate;
        $this->default_per_page = self::$static_default_per_page;
    }

    abstract public function model(): string;

    abstract public function relations(): array;

    public function setDefaultPerPage($per_page = null): void
    {
        if ($per_page && is_int($per_page) && $per_page < 10000) {
            $this->default_paginate = $per_page;
        }
    }

    public function setDefaultPaginate($paginate = null): void
    {
        if (isset($paginate) && is_bool($paginate)) {
            $this->default_paginate = $paginate;
        }
    }

    #[Pure]
    public function getFillable(): array
    {
        return $this->model->getFillable() ?? [];
    }

    public function queryByInputs(Builder $query = null, $inputs = [], array $fillable = [], string $primaryKey = '', array $casts = []): Builder
    {
        $fillable = count($fillable) > 0 ? $fillable : $this->getFillable();
        $casts = count($casts) > 0 ? $casts : $this->model->getCasts();
        $primaryKey = filled($primaryKey) ? $primaryKey : $this->model->getKeyName();
        $query = $query ?? $this->model->query();

        if (!empty($inputs)) {
            foreach ($inputs as $keyInput => $valueInput) {
                if ((in_array($keyInput, $fillable) || $keyInput == $primaryKey) && (is_string($valueInput) || is_int($valueInput) || is_array($valueInput) || is_object($valueInput))) {
                    if (method_exists($this->model, 'getCasts') && count($casts) > 0) {
                        if (is_object($valueInput)) {
                            if (isset($valueInput->operator)) {
                                if (is_array($valueInput->value)) {
                                    if ($valueInput->operator == "Not" || $valueInput->operator == "not") {
                                        $query = $this->byArray($query, $keyInput, $valueInput->value, "Not");
                                    } elseif ($valueInput->operator == "Between" || $valueInput->operator == "between") {
                                        $query = $this->byBetween($query, $keyInput, $valueInput->value);
                                    } else {
                                        $query = $this->byArray($query, $keyInput, $valueInput->value);
                                    }
                                } else {
                                    $query = $this->byOperator($query, $keyInput, $valueInput->value, $valueInput->operator);
                                }
                            } else {
                                if (is_array($valueInput->value)) {
                                    $query = $this->byArray($query, $keyInput, $valueInput->value);
                                } else {
                                    $query = $this->by($query, $keyInput, $valueInput);
                                }
                            }

                        } else if (is_array($valueInput)) {
                            $query = $this->byArray($query, $keyInput, $valueInput);
                        } else {
                            $valueCast = $casts[$keyInput] ?? null;
                            if ($valueCast == 'string') {
                                $query = $this->byLike($query, $keyInput, $valueInput);
                                # todo $query = $this->by($query, $keyInput, $valueInput);
                            } elseif ($valueCast == 'date') {
                                $query = $this->byDate($query, $keyInput, $valueInput);
                            } else {
                                $query = $this->by($query, $keyInput, $valueInput);
                            }
                        }
                    } else {
                        $query = $this->by($query, $keyInput, $valueInput);
                    }
                }
            }
        }
        return $query;
    }

    public function queryByRelations(Builder $query = null, array|bool $relations = [], $inputs = []): Builder
    {
        /** @var Builder $query */
        $query = $query ?? $this->model->query();
        $withs = $inputs['withs'] ?? [];
        # $withs = array_key_exists('withs',$inputs) ? $inputs['withs'] : [];
        $relations = count($relations) > 0 ? $relations : $withs;
        if (isset($relations) && is_array($relations)) {
            foreach ($relations as $relation) {
                $relationsItem = explode(".", $relation);
                $firstRelation = $relationsItem[0];
                $secondRelations = $relationsItem[1] ?? null;

                if ($secondRelations) {
                    $subRelations = explode(",", $secondRelations);
                    $query = $query->with($firstRelation, function ($subQuery) use ($subRelations) {
                        return $subQuery->with($subRelations);
                    });
                } else {
                    $query = $this->withRelation($query, $firstRelation);
                }

            }

            return $query;

        } elseif (isset($relations) && is_bool($relations) && $relations) {
            return $this->withRelations(query: $query, relations: $this->relations());
        }
        return $query;
    }

    public function queryBySearch(Builder $query = null, array|bool $relations = [], $inputs = [], $search = null)
    {
        if (($search && filled($search)) || (isset($inputs['search']) && filled($inputs['search']))) {
            $search = $search ?? $inputs['search'];
            $query = $query ?? $this->model->query();
            $fillable = $this->getFillable();
            $searchFields = $this?->fillable_search() ?? [];
            foreach ($searchFields as $key => $field) {
                if (in_array($field, $fillable)) {
                    if ($key === 0) {
                        $query = $query->where($field, 'like', '%' . $search . '%');
                    } else {
                        $query = $query->orWhere($field, 'like', '%' . $search . '%');
                    }
                }
            }
        }

        return $query;
    }

    public function querySelects(Builder $query, $selects = []): Builder
    {
        if (!empty($selects)) {
            $query = $query->select($selects);
        }

        return $query;
    }

    public function queryFull($inputs = [], $relations = [], $selects = [], $orderByColumn = 'id', $directionOrderBy = 'desc', $query = null, $my_col = null, $my_value = null, $my_auth = false): Builder
    {
        /** @var Builder $query */
        $query = $query ?? $this->model->query();
        $query = $this->queryByInputs(query: $query, inputs: $inputs);
//        $query = $this->queryMy(query: $query, col: $my_col, value: $my_value);
//        if ($my_auth) {
//            $query = $this->queryMyAuth(query: $query, col: $my_col, value: $my_value);
//        }
        $query = $this->queryBySearch(query: $query, inputs: $inputs);
        $query = $this->queryByRelations(query: $query, relations: $relations, inputs: $inputs);
        $query = $this->querySelects($query, $selects);
        return $this->orderBy($query, $orderByColumn, $directionOrderBy);
    }

    public function all($inputs = [], $relations = [], $selects = [], $orderByColumn = 'id', $directionOrderBy = 'desc'): Collection
    {
        return $this->queryFull($inputs, $relations, $selects, $orderByColumn, $directionOrderBy)->get();
    }

    public function orderBy(Builder $query, $orderByColumn = 'id', $directionOrderBy = 'desc'): Builder
    {
        return $query->orderBy($orderByColumn, $directionOrderBy);
    }

    public function paginate($perPage = null, $inputs = [], $relations = [], $selects = [], $orderByColumn = 'id', $directionOrderBy = 'desc', Builder $query = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? request('per_page') ?? request('perPage') ?? $this->default_per_page;
        if (!isset($query) && !$query) {
            $query = $this->queryFull(inputs: $inputs, relations: $relations, selects: $selects, orderByColumn: $orderByColumn, directionOrderBy: $directionOrderBy);
        }
        return $query->paginate($perPage);
    }

    public function resolve_paginate($perPage = null, $inputs = [], $relations = [], $selects = [], $orderByColumn = null, $directionOrderBy = null, $paginate = null, Builder|Collection $query = null, $my_col = null, $my_value = null, $my_auth = false)
    {
        $paginate = isset($paginate) && !is_null($paginate) && is_bool($paginate) ? $paginate : request(key: 'paginate', default: self::$static_default_paginate);
        $perPage = $perPage ?? request('per_page') ?? request(key: 'perPage', default: $this->default_per_page);
        $orderByColumn = $orderByColumn ?? request('order_by', self::$static_default_order_by_column);
        $directionOrderBy = $directionOrderBy ?? request('direction_by', self::$static_default_direction_order);
        # ########## ########## ##########
        if (!isset($query)) {
            $query = $this->queryFull(
                inputs: $inputs,
                relations: $relations,
                selects: $selects,
                orderByColumn: $orderByColumn,
                directionOrderBy: $directionOrderBy,
                my_col: $my_col,
                my_value: $my_value,
                my_auth: $my_auth
            );
        }

        return
            $paginate
                ?
                $query->paginate(perPage: $perPage)
                :
                $query->get();
    }

    public static function static_resolve_paginate($perPage = null, $inputs = [], $relations = [], $selects = [], $orderByColumn = 'id', $directionOrderBy = 'desc', $paginate = null, Builder $query = null): LengthAwarePaginator|Collection
    {
        $paginate = isset($paginate) && !is_null($paginate) && is_bool($paginate) ? $paginate : request(key: 'paginate', default: self::$static_default_paginate);
        $perPage = $perPage ?? request('per_page') ?? request(key: 'perPage', default: self::$static_default_per_page);

        return
            $paginate
                ?
                $query->paginate(perPage: $perPage)
                :
                $query->get();
    }

    public function getBy($col, $value, $limit = 15, $relations = [], $inputs = []): \Illuminate\Database\Eloquent\Collection|array
    {
        if (!empty($relations)) {
            $query = $this->model;
            $query = $this->queryByRelations($query, $relations, $inputs);
            return $this->model->with($relations)->where($col, $value)->limit($limit)->get();
        }
        return $this->model->where($col, $value)->limit($limit)->get();
    }

    public function byArray(Builder $query, $col, array $values, $operator = null): Builder
    {
        if ($operator == "Not") {
            return $query->whereNotIn($col, $values);
        }
        return $query->whereIn($col, $values);
    }

    public function by(Builder $query, $col, $value): Builder
    {
        return $query->where($col, $value);
    }

    public function byOperator(Builder $query, $col, $value, $operator): Builder
    {
        return $query->where($col, $operator, $value);
    }

    public function byDate(Builder $query, $col, $value): Builder
    {
        return $query->whereDate($col, $value);
    }

    public function byBetween(Builder $query, $col, $values): Builder
    {
        return $query->whereBetween($col, $values);
    }

    public function byLike(Builder $query, $col, $value): Builder
    {
        return $query->where($col, 'like', '%' . $value . '%');
    }

    public function byFirstLike(Builder $query, $col, $value): Builder
    {
        return $query->whereLike($col, 'like', $value . '%');
    }

    public function byEndLike(Builder $query, $col, $value): Builder
    {
        return $query->whereLike($col, 'like', '%' . $value);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function firstOrCreate(array $attributes = [], array $values = [])
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWithRelations($id, $relations = []): mixed
    {
        if (empty($relations)) {
            $relations = $this->relations();
        }

        return $this->model->with($relations)->find($id);
    }

    public function withRelations($query = null, string|array $relations = null): mixed
    {
        if (is_null($query)) {
            $query = $this->model->query();
        }
        if (isset($relations) && !empty($relations)) {
            if (is_string($relations)) {
                $relations = [$relations];
            }
            $relations_available = $this->relations() ?? [];
            $relations = array_intersect($relations_available, $relations);
            return $query->with($relations);
        }
        return $query;
    }

    public function withRelation($query = null, string $relation = null, $callback = null): mixed
    {
        if (is_null($query)) {
            $query = $this->model->query();
        }
        if (isset($relation) && filled($relation)) {
            $relations_available = $this->relations() ?? [];
            if (in_array($relation, $relations_available)) {
                return $callback ? $query->with($relation, $callback) : $query->with($relation);
            }
        }
        return $query;
    }

    public function findBy($col, $value, $relations = []): mixed
    {
        if (!empty($relations)) {
            return $this->model->with($relations)->where($col, $value)->first();
        }

        return $this->model->where($col, $value)->first();
    }

    public function queryMyAuth(Builder $query, $col = null, $value = null)
    {
        $value = $value ?? get_user_id_login();
        $col = $col ?? 'user_id';
        return $this->queryMy(query: $query, col: $col, value: $value);
    }

    public function queryMy(Builder $query = null, $col = null, $value = null): mixed
    {
        $query = $query ?? $this->model->query();
        if (!is_null($col) || !is_null($value)) {
            return $query->where($col, $value)->first();
        }
        return $query;
    }

    public function findWithInputs($inputs, $relations = [], $query = null): null|object
    {
        /** @var Builder $query */
        $query = $query ?? $this->model->query();
        foreach ($inputs as $keyInput => $valueInput) {
            if ((in_array($keyInput, $this->getFillable()) || $keyInput == $this->model->getKeyName())) {
                $query = $query->where($keyInput, $valueInput);
            }
        }
        if (!empty($relations)) {
            $query = $this->queryByRelations($query, $relations, $inputs);
        }

        return $query->first();
    }

    public function findFull($inputs, $relations = [], $selects = []): null|object
    {
        /** @var Builder $query */
        $query = $this->model->query();
        foreach ($inputs as $keyInput => $valueInput) {
            if ((in_array($keyInput, $this->getFillable()) || $keyInput == $this->model->getKeyName())) {
                $query = $query->where($keyInput, $valueInput);
            }
        }
        $query = $this->queryByRelations($query, $relations);
        $query = $this->querySelects($query, $selects);
        return $query->first();
    }

    public function update($model, array $data)
    {
        /** @var Model $model */
        return $model->update($data);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @param $model
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [], $model = null): mixed
    {
        /** @var Model $model */
        $model = $model ?? $this->model->query();

        return $model->updateOrCreate($attributes, $values);
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function forceDelete($model)
    {
        return $model->forceDelete();
    }

    public function exists($id)
    {
        return $this->model->where($this->model->getKeyName(), $id)->exists();
    }

    public function getByInput($filters = [], $perPage = 30000, $pageNumber = 1, $relations = [], $orderByColumn = 'id', $directionOrderBy = 'desc', $query = null)
    {
        $query = $query ?? $this->model->query();
        $perPage = $perPage ?? self::$static_default_per_page;
        if (is_null($query)) {
            $query = $this->model;
        }
        foreach ($filters as $filter) {
            if (property_exists($filter, 'like') && $filter->like) {
                $query = $query->where($filter->col, 'like', '%' . $filter->value . '%');
            } else {
                $query = $query->where($filter->col, $filter->value);
            }
        }
        if (count($relations) > 0) {
            $query = $this->queryByRelations($query, $relations);
        }
        $query->orderBy($orderByColumn, $directionOrderBy);
        return $query->paginate($perPage, ['*'], 'page', $pageNumber)->getCollection();
    }

    public function createQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->query();
    }

    public function limit($limit = 15, $orderByColumn = 'id', $directionOrderBy = 'desc')
    {
        return $this->model->orderBy($orderByColumn, $directionOrderBy)->limit($limit)->get();
    }

    public function getNull($col, $limit = 15, $operator = null, $orderByColumn = 'id', $directionOrderBy = 'desc')
    {
        if ($operator == "Not") {
            return $this->model->whereNotNull($col)->limit($limit)->get();

        }
        return $this->model->whereNull($col)->limit($limit)->get();
    }

    public function getTable(): string
    {
        return $this->model->getTable();
    }
}
