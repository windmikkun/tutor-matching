<?php

namespace App\Services;

class ListSearchService
{
    /**
     * 都道府県・市区町村によるクエリフィルタ
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyPrefectureAddressFilter($query, $request)
    {
        $hasPrefecture = $request->filled('prefecture') || $request->filled('prefecture_display');
        $hasAddress1 = $request->filled('address1') || $request->filled('city_display');
        if (!$hasPrefecture && !$hasAddress1) {
            return $query;
        }
        // Employerリストの場合（userリレーション経由のみでフィルタ）
        if (method_exists($query->getModel(), 'user')) {
            $query->whereHas('user', function($q) use ($request) {
                if ($request->filled('prefecture')) {
                    $q->where('prefecture', $request->input('prefecture'));
                } elseif ($request->filled('prefecture_display')) {
                    $q->where('prefecture', $request->input('prefecture_display'));
                }
                if ($request->filled('address1')) {
                    $q->where('address1', 'like', '%' . $request->input('address1') . '%');
                } elseif ($request->filled('city_display')) {
                    $q->where('address1', 'like', '%' . $request->input('city_display') . '%');
                }
            });
        }
        return $query;
    }
}
