<?php

namespace App\Services\CountryRelation;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Configuration\CountryRelation;
use App\Interfaces\CountryRelation\CountryRelationRepositoryInterface;

class CountryRelationService
{
    protected $countryRelationRepository;

    public function __construct(CountryRelationRepositoryInterface $countryRelationRepository)
    {
        $this->countryRelationRepository = $countryRelationRepository;
    }

    public function getCountryRelationsQuery()
    {
        return CountryRelation::query()
            ->where('type', '!=', 'master')
            ->whereHas('country', function ($query) {
                $query->where('status', true);
            })
            ->distinct();
    }

    public function listRelations($types)
    {
        try {
            $types = explode(',', $types);
            $responses = CountryRelation::whereIn('type', $types)
                ->where('status', true)
                ->orderBy('type', 'asc')
                ->get()
                ->groupBy('type');

            $comboResponses = [];
            foreach ($responses as $index => $value) {
                $comboResponses[$index] = $value->toArray();
            }
            return $comboResponses;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function getRelationByIdAndCode($id, $code)
    {
        return CountryRelation::where('country_id', $id)
            ->where('type', $code)
            ->first();
    }

    public function storeCountryRelation(array $data)
    {
        DB::beginTransaction();
        try {
            $data['status'] = $data['status'] ?? true;
            $relation = $this->countryRelationRepository->create($data);
            DB::commit();
            return $relation;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateCountryRelation(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $data['status'] = $data['status'] ?? true;
            $relation = $this->countryRelationRepository->update($id, $data);
            DB::commit();
            return $relation;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteCountryRelation($id)
    {
        try {
            $this->countryRelationRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public static function getByWhere($where)
    {
        return CountryRelation::where($where)->get();
    }

    public static function getRelationById($id)
    {
        return CountryRelation::find($id);
    }

    public static function getRelationsByType($type)
    {
        return CountryRelation::where('type', $type)
            ->where('status', true)
            ->orderBy('type', 'asc')
            ->get();
    }
}
