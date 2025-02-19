<?php

namespace App\Services\EnumOption;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Configuration\EnumOption;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\EnumOption\EnumOptionRepositoryInterface;
use App\Interfaces\CountryRelation\CountryRelationRepositoryInterface;

class EnumOptionService
{
    protected $enumRepository;
    protected $countryRepository;

    public function __construct(EnumOptionRepositoryInterface $enumRepository, CountryRelationRepositoryInterface $countryRepository)
    {
        $this->enumRepository = $enumRepository;
        $this->countryRepository = $countryRepository;
    }

    public function getEnumsQuery()
    {
        return EnumOption::query()
            ->where('is_father', '!=', true)
            ->where('name', '!=', 'master father')
            ->whereHas('parent', function ($query) {
                $query->where('status', true);
            })
            ->distinct();
    }

    public function listChildrens($names)
    {
        try {
            $names = explode(',', $names);
            $responses = EnumOption::whereIn('parent_id', function ($query) use ($names) {
                $query->select('id')
                    ->from('enum_options')
                    ->whereIn('code', $names);
            })
                ->where('status', true)
                ->orderBy('name', 'asc')
                ->get()
                ->groupBy('parent_id');

            $comboResponses = [];
            foreach ($responses as $index => $value) {
                $parent = EnumOption::find($index)->code;
                $comboResponses[$parent] = $value->toArray();
            }
            return $comboResponses;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function storeEnum(array $data)
    {
        DB::beginTransaction();
        try {
            $data['status'] = $data['status'] ?? true;
            $enum = $this->enumRepository->create($data);
            if (isset($data['relations']) && !empty($data['relations'])) {
                $this->handleRelations($data['relations'], $enum->id);
            }
            DB::commit();
            return $enum;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateEnum(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $data['status'] = $data['status'] ?? true;
            $enum = $this->enumRepository->update($id, $data);
            $this->countryRepository->deleteByCountryId($id);
            if (isset($data['relations']) && !empty($data['relations'])) {
                $this->handleRelations($data['relations'], $id);
            }
            DB::commit();
            return $enum;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    private function handleRelations(array $relations, $id)
    {
        if (isset($relations['currency'])) {
            foreach ($relations['currency'] as $currency) {
                $this->countryRepository->create([
                    'country_id' => $id,
                    'related_id' => $currency,
                    'type' => 'currency'
                ]);
            }
        }
        if (isset($relations['language'])) {
            foreach ($relations['language'] as $language) {
                $this->countryRepository->create([
                    'country_id' => $id,
                    'related_id' => $language,
                    'type' => 'language'
                ]);
            }
        }
    }

    public function deleteEnum($id)
    {
        try {
            $this->enumRepository->delete($id);
            $this->countryRepository->deleteByCountryId($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }


    public static function getByWhere($where)
    {
        return EnumOption::where($where)->get();
    }

    public static function getOptionById($id)
    {
        return EnumOption::find($id);
    }

    public static function getBrotherById($id)
    {
        return EnumOption::where([
            'brother_relation_id' => $id
        ])->orderBy('name', 'asc')
            ->get();
    }

    public static function getBrotherByIdAndCode($id, $code)
    {
        $subQuery = EnumOption::where('status', true)
            ->where('brother_relation_id', $id)
            ->pluck('id');

        return EnumOption::select('id', 'name', 'valor1', 'parent_id', 'brother_relation_id')
            ->whereIn('id', $subQuery)
            ->whereHas('parent', function ($query) use ($code) {
                $query->where('code', $code);
            })
            ->get();
    }

    public static function getBrotherByIdRelation($id)
    {
        return EnumOption::where('brother_relation_id', $id)
            ->where(function ($query) {
                $query->whereHas('ownerships_country', function (Builder $query) {
                    $query->whereNotNull('country_id');
                })->orWhereHas('ownerships_state', function (Builder $query) {
                    $query->whereNotNull('state_id');
                })->orWhereHas('ownerships_city', function (Builder $query) {
                    $query->whereNotNull('city_id');
                });
            })
            ->where('status', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function getBrotherByIdRelationUser($id)
    {
        return EnumOption::where('brother_relation_id', $id)
            ->where(function ($query) {
                $query->whereHas('user_country', function (Builder $query) {
                    $query->whereNotNull('country');
                })->orWhereHas('user_state', function (Builder $query) {
                    $query->whereNotNull('state');
                })->orWhereHas('user_city', function (Builder $query) {
                    $query->whereNotNull('city');
                });
            })
            ->where('status', true)
            ->orderBy('name', 'asc')
            ->get();
    }
}
