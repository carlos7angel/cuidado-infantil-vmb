<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Containers\Monitoring\IncidentReport\Tasks\UpdateIncidentReportTask;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\UpdateIncidentReportRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateIncidentReportAction extends ParentAction
{
    public function __construct(
        private readonly UpdateIncidentReportTask $updateIncidentReportTask,
    ) {
    }

    public function run(UpdateIncidentReportRequest $request): IncidentReport
    {
        $data = $request->sanitize([
            'description',
            'actions_taken',
            'additional_comments',
            'follow_up_notes',
            'authority_notification_details',
        ]);

        return $this->updateIncidentReportTask->run($data, $request->id);
    }
}
