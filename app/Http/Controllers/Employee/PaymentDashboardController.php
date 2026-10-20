<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\RazorpayPayment;
use App\Models\User;
use App\Support\Enums\UserPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage', User::class);

        $period = $request->string('period', 'month')->toString();
        if (! in_array($period, ['month', 'year', 'custom'], true)) {
            $period = 'month';
        }

        $dateFromInput = $request->string('date_from')->toString();
        $dateToInput = $request->string('date_to')->toString();

        if ($period === 'year') {
            $from = now()->startOfYear();
            $to = now()->endOfYear();
        } elseif ($period === 'custom' && $dateFromInput !== '' && $dateToInput !== '') {
            $from = \Illuminate\Support\Carbon::parse($dateFromInput)->startOfDay();
            $to = \Illuminate\Support\Carbon::parse($dateToInput)->endOfDay();
            if ($to->lt($from)) {
                [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }
        } else {
            $period = $period === 'custom' ? 'month' : $period;
            $from = now()->startOfMonth();
            $to = now()->endOfMonth();
        }

        $search = $request->string('q')->toString() ?: null;
        $status = $request->string('status')->toString() ?: null;
        if ($status !== null && ! in_array($status, ['created', 'paid', 'failed'], true)) {
            $status = null;
        }

        $base = RazorpayPayment::query()
            ->with('user')
            ->whereBetween('created_at', [$from, $to])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search): void {
                $q->whereHas('user', function ($u) use ($search): void {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            });

        $revenue = (clone $base)
            ->where('status', 'paid')
            ->selectRaw('currency, SUM(amount_minor) as total_minor, COUNT(*) as total_count')
            ->groupBy('currency')
            ->get()
            ->keyBy('currency');

        $summary = [
            'revenue_inr' => (int) ($revenue->get('INR')->total_minor ?? 0),
            'revenue_usd' => (int) ($revenue->get('USD')->total_minor ?? 0),
            'paid_count' => (clone $base)->where('status', 'paid')->count(),
            'total_count' => (clone $base)->count(),
            'active_gold' => User::query()
                ->where('plan', UserPlan::Gold)
                ->where(fn ($q) => $q->whereNull('gold_expires_at')->orWhere('gold_expires_at', '>', now()))
                ->count(),
            'expiring_this_month' => User::query()
                ->where('plan', UserPlan::Gold)
                ->whereBetween('gold_expires_at', [now(), now()->endOfMonth()])
                ->count(),
        ];

        $payments = $base->latest()->paginate(20)->withQueryString();

        return view('employee.payments.index', [
            'payments' => $payments,
            'summary' => $summary,
            'period' => $period,
            'dateFrom' => $period === 'custom' ? $from->toDateString() : '',
            'dateTo' => $period === 'custom' ? $to->toDateString() : '',
            'search' => $search,
            'status' => $status,
            'rangeLabel' => $from->format('d M Y').' – '.$to->format('d M Y'),
        ]);
    }
}
