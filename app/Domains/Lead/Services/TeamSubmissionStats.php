<?php

namespace App\Domains\Lead\Services;

use App\Domains\Lead\Models\Lead;
use App\Models\User;
use App\Support\Enums\LeadSource;
use App\Support\Enums\UserType;
use Illuminate\Http\Request;

class TeamSubmissionStats
{
    public function forTeam(UserType $type, LeadSource $source, Request $request): array
    {
        $members = User::where('user_type', $type)->orderBy('name')->paginate(20)->withQueryString();
        $selectedUser = $request->filled('member')
            ? User::where('user_type', $type)->find($request->string('member')->toString()) : null;
        $selectedUser ??= $members->first();
        $ids = $members->getCollection()->pluck('id');
        if ($selectedUser) {
            $ids->push($selectedUser->id);
        }
        $totals = Lead::where('source', $source)->whereIn('created_by', $ids->unique())
            ->selectRaw('created_by, COUNT(*) as total, SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today, SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as month',
                [now()->startOfDay(), now()->startOfMonth()])
            ->groupBy('created_by')->get()->keyBy('created_by');
        $row = function (User $user) use ($totals): array {
            $counts = $totals->get($user->id);

            return ['user' => $user, 'counts' => ['total' => (int) ($counts?->total ?? 0),
                'today' => (int) ($counts?->today ?? 0), 'month' => (int) ($counts?->month ?? 0)]];
        };
        $selected = $selectedUser ? $row($selectedUser) : null;
        if ($selected) {
            $chart = Lead::where('source', $source)->where('created_by', $selectedUser->id)
                ->whereBetween('created_at', [now()->subDays(29)->startOfDay(), now()->endOfDay()])
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->orderBy('day')->get();
            $selected['chartLabels'] = $chart->pluck('day')->all();
            $selected['chartValues'] = $chart->pluck('total')->map(fn ($value) => (int) $value)->all();
        }

        return ['members' => $members->through($row), 'selected' => $selected];
    }
}
