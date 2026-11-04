@extends('layouts.marketplace')

@section('title', 'Lead view limit | Trade4Deal')

@section('content')
<div class="container py-5" style="max-width:640px;">
    <div class="card card-t4d border-0 shadow-sm">
        <div class="card-body p-4 text-center">
            @if ($denyReason === 'daily')
                <div class="alert alert-warning border-0 mb-3">
                    <strong>Limit is reached for today.</strong>
                </div>
                <p class="text-muted mb-4">
                    Gold members can open {{ $limit }} leads per day.
                    You have used today’s allowance. Please come back tomorrow to review more leads.
                </p>
                <a href="{{ route('home') }}#leads" class="btn btn-primary-t4d text-white btn-sm">
                    Back to leads
                </a>
            @else
                <div class="alert alert-warning border-0 mb-3">
                    <strong>Free lead views used.</strong>
                </div>
                <p class="text-muted mb-4">
                    Free accounts can open {{ $limit }} leads in total.
                    Upgrade to Gold for daily lead access and instant visibility on new opportunities.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('plans.index') }}" class="btn btn-primary-t4d text-white btn-sm">
                        Purchase Gold plan
                    </a>
                    <a href="{{ route('home') }}#leads" class="btn btn-outline-secondary btn-sm">
                        Back to leads
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
