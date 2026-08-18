<?php

use App\Actions\UpdateComplaintStatus;
use App\Enums\ComplaintPriority;
use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;

it('updates status when the transition is allowed', function () {
    $category = ComplaintCategory::factory()->create();
    $complaint = Complaint::factory()->create([
        'user_id' => User::factory(),
        'complaint_category_id' => $category->id,
        'status' => ComplaintStatus::SUBMITTED,
        'priority' => ComplaintPriority::LOW,
    ]);

    (new UpdateComplaintStatus)->handle($complaint, [
        'status' => ComplaintStatus::UNDER_REVIEW->value,
        'priority' => ComplaintPriority::LOW->value,
    ]);

    expect($complaint->fresh()->status)->toBe(ComplaintStatus::UNDER_REVIEW);
});
