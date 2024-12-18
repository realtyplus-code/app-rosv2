<?php

use Illuminate\Support\Facades\Log;

function renderDataTable($query, $request, $with = [], $select = false, $appends = [])
{
    # Ordenamiento
    if ($request->has('sort') && !empty($request->input('sort')) && !empty($request->input('sort')[0])) {
        $sort = $request->input('sort');
        $field = $sort[0];
        $type = $sort[1];
        $query->orderBy($field, ($type == 1) ? 'desc' : 'asc');
    }

    # Filtros
    if ($request->has('filters') && !empty($request->input('filters'))) {
        $filters = $request->input('filters');
        for ($i = 0; $i < count($filters); $i++) {
            $operator = getSqlOperator($filters[$i][1]);
            $value = getQueryValue($filters[$i][1], $filters[$i][2]);
            $query->where($filters[$i][0], $operator, $value);
        }
    }

    # Condicionales
    if ($request->has('conditions') && !empty($request->input('conditions'))) {
        $conditions = $request->input('conditions');
        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }
    }

    # Filtros select
    if ($request->has('select') && !empty($request->input('select'))) {
        $filters = $request->input('select');
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }
    }

    //Log::debug($query->toSql());
    $perPage = $request->input('perPage');
    $result = $query->with($with)->select($select)->paginate($perPage);


    # Campos virtuales
    if (!empty($appends)) {
        $result->getCollection()->transform(function ($model) use ($appends) {
            return $model->append($appends);
        });
    }

    return $result;
}

function getSqlOperator($operator)
{
    switch ($operator) {
        case 'contains':
            return 'LIKE';
        case 'notContains':
            return 'NOT LIKE';
        case 'startsWith':
            return 'LIKE';
        case 'endsWith':
            return 'LIKE';
        case 'equals':
            return 'LIKE';
        case 'notEquals':
            return '<>';
        default:
            return null;
    }
}

function getQueryValue($operator, $value)
{
    switch ($operator) {
        case 'contains':
            return '%' . $value . '%';
        case 'notContains':
            return '%' . $value . '%';
        case 'startsWith':
            return $value . '%';
        case 'endsWith':
            return '%' . $value;
        case 'equals':
            return '%' . $value . '%';
        default:
            return $value;
    }
}

function cleanStorageUrl($url, $path = '/storage_property/')
{
    $url = trim($url);
    $basePath = $path;
    $position = strpos($url, $basePath);
    if ($position === false) {
        return $url;
    }
    $cleanedUrl = substr($url, $position + strlen($basePath));
    return $cleanedUrl;
}
