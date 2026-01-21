<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentStatus;
use App\Containers\Monitoring\IncidentReport\Tasks\FindIncidentReportByIdTask;
use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;

final class UpdateIncidentStatusRequest extends ParentRequest
{
    protected array $urlParameters = [
        'incident_id',
    ];

    protected array $decode = [
        // 'incident_id',
    ];

    public function rules(): array
    {
        return [
            'incident_id' => ['string', 'exists:incident_reports,id'],
            'status' => ['required', 'string', Rule::in(array_column(IncidentStatus::cases(), 'value'))],
        ];
    }

    public function authorize(): bool
    {
        $incidentId = $this->route('incident_id') ?? $this->input('incident_id');
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
