<?php

namespace App\Data\ActivityLog;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Models\Activity;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class ActivityLogData extends Data
{
    public function __construct(
        public int $id,
        public ?string $logName,
        public ?string $event,
        public ?string $description,
        public ?string $subjectType,
        public ?int $subjectId,
        public ?string $causerType,
        public ?int $causerId,
        public array $oldValue,
        public array $newValue,
        public DateTimeInterface $createdAt,

        #[Computed] public ?string $createdHuman = null,
        #[Computed] public ?array $subject = null,
        #[Computed] public ?array $causer = null,
    ) {}

    public static function fromModel(Activity $activity): self
    {
        $props = $activity->properties->toArray();
        $old = Arr::get($props, 'old', []);
        $new = Arr::get($props, 'attributes', []);

        $subject = $activity->subject ? [
            'id' => $activity->subject_id,
            'type' => class_basename($activity->subject_type),
            'name' => $activity->subject->name
                ?? $activity->subject->title
                    ?? "#{$activity->subject_id}",
        ] : null;

        $causer = $activity->causer ? [
            'id' => $activity->causer_id,
            'type' => class_basename($activity->causer_type),
            'name' => $activity->causer->name
                ?? $activity->causer->email
                    ?? "#{$activity->causer_id}",
        ] : null;

        return new self(
            id: $activity->id,
            logName: $activity->log_name,
            event: $activity->event,
            description: $activity->description,
            subjectType: $activity->subject_type,
            subjectId: $activity->subject_id,
            causerType: $activity->causer_type,
            causerId: $activity->causer_id,
            oldValue: $old,
            newValue: $new,
            createdAt: $activity->created_at,
            createdHuman: $activity->created_at?->diffForHumans(),
            subject: $subject,
            causer: $causer,
        );
    }
}
