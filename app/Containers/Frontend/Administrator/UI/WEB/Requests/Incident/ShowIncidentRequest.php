<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\IncidentReport\Tasks\FindIncidentReportByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ShowIncidentRequest extends ParentRequest
{
    /**
     * @var array<string>
     */
    protected array $urlParameters = [
        'incident_id',
    ];

    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        $incidentId = $this->route('incident_id');
        if (!$incidentId) {
            return false;
        }

        $user = $this->user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            try {
                $incident = app(FindIncidentReportByIdTask::class)->run($incidentId);
                if ($incident->childcare_center_id != $user->childcare_center_id) {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        
        return true;
    }
}
