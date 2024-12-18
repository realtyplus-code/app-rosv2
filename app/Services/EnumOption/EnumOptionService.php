<?php

namespace App\Services\EnumOption;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Configuration\EnumOption;
use App\Interfaces\EnumOption\EnumOptionRepositoryInterface;

class EnumOptionService
{
    protected $enumRepository;

    public function __construct(EnumOptionRepositoryInterface $enumRepository)
    {
        $this->enumRepository = $enumRepository;
    }

    public function getEnumsQuery()
    {
        return EnumOption::query()
            ->where('is_father', '!=', true)
            ->where('status', '=', true)
            ->where('name', '!=', 'master padre')
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
            $enum = [
                'parent_id' => $data['parent_id'],
                'brother_relation_id' => $data['brother_relation_id'] ?? NULL,
                'name' => $data['name'],
                'status' => true,
                'value1' => $value1 ?? NULL,
            ];
            $enum = $this->enumRepository->create($enum);
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
            $enum = [
                'parent_id' => $data['parent_id'],
                'brother_relation_id' => $data['brother_relation_id'] ?? NULL,
                'name' => $data['name'],
                'status' => true,
                'value1' => $value1 ?? NULL,
            ];
            $enum = $this->enumRepository->update($id, $enum);
            DB::commit();
            return $enum;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteEnum($id)
    {
        try {
            $this->enumRepository->delete($id);
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
