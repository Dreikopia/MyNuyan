<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ComplaintPriority;
use App\Enums\ComplaintStatus;
use App\Models\Admin;
use App\Models\Complaint;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateComplaintStatus
{
    public function handle(Complaint $complaint, array $attributes): void
    {
        $admin = $this->currentAdmin();

        $newStatus = ComplaintStatus::from($attributes['status']);
        $newPriority = ComplaintPriority::from($attributes['priority']);

        $statusIsChanging = $newStatus !== $complaint->status;

        if ($statusIsChanging && ! $complaint->status->canTransitionTo($newStatus)) {
            throw new DomainException('That status change is not allowed.');
        }

        DB::transaction(function () use ($complaint, $newStatus, $newPriority, $attributes, $admin, $statusIsChanging) {
            $complaint->update([
                'status' => $newStatus,
                'priority' => $newPriority,
            ]);

            if ($statusIsChanging) {
                $complaint->statusHistories()->create([
                    'changed_by' => $admin?->id,
                    'status' => $newStatus->value,
                    'remarks' => $attributes['remarks'] ?? null,
                ]);
            }
        });
    }

    protected function currentAdmin(): ?Admin
    {
        return Auth::guard('admin')->user();
    }
}
