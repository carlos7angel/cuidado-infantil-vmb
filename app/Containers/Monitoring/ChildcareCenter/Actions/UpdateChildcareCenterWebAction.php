<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildcareCenter\Tasks\UpdateChildcareCenterTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class UpdateChildcareCenterWebAction extends ParentAction
{
    public function __construct(
        private readonly UpdateChildcareCenterTask $updateChildcareCenterTask,
    ) {
    }

    public function run(Request $request, int $id): ChildcareCenter
    {
        $data = $this->prepareData($request, $id);

        return $this->updateChildcareCenterTask->run($data, $id);
    }

    private function prepareData(Request $request, int $id): array
    {
        $data = $request->only([
            'name',
            'description',
            'type',
            'date_founded',
            'address',
            'phone',
            'email',
            'social_media',
            'state',
            'city',
            'municipality',
            'contact_name',
            'contact_phone',
            'contact_email',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                $logo = $request->file('logo');
                
                if ($logo->isValid() && $logo->getRealPath()) {
                    $uploadPath = public_path('uploads/childcare_centers');
                    
                    // Ensure directory exists
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $logoName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $logo->getClientOriginalName());
                    $destinationPath = $uploadPath . '/' . $logoName;
                    
                    // Delete old logo if exists
                    $oldCenter = \App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter::find($id);
                    if ($oldCenter && $oldCenter->logo) {
                        $oldLogoPath = public_path($oldCenter->logo);
                        if (file_exists($oldLogoPath)) {
                            @unlink($oldLogoPath);
                        }
                    }
                    
                    // Use move_uploaded_file which is safer for uploaded files
                    if (move_uploaded_file($logo->getRealPath(), $destinationPath)) {
                        $data['logo'] = 'uploads/childcare_centers/' . $logoName;
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request if logo upload fails
                \Log::error('Error uploading logo: ' . $e->getMessage());
            }
        }

        // Format date_founded if provided (convert from d/m/Y to Y-m-d)
        if (isset($data['date_founded']) && !empty($data['date_founded'])) {
            try {
                // Try to parse d/m/Y format first
                $date = \DateTime::createFromFormat('d/m/Y', $data['date_founded']);
                if ($date) {
                    $data['date_founded'] = $date->format('Y-m-d');
                } else {
                    // Fallback to standard date parsing
                    $data['date_founded'] = date('Y-m-d', strtotime($data['date_founded']));
                }
            } catch (\Exception $e) {
                // If parsing fails, try standard format
                $data['date_founded'] = date('Y-m-d', strtotime($data['date_founded']));
            }
        }

        return $data;
    }
}

