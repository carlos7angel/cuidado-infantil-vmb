<?php

namespace App\Containers\Monitoring\Child\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ChildrenImport implements ToCollection, WithCalculatedFormulas
{
    public function collection(Collection $rows)
    {
        return $rows;
    }
}
