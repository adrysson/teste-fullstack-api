<?php

namespace App\Imports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContractsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contract([
            'client' => $row['cliente'],
            'term' => date('Y-m-d', strtotime($row['entrega'])),
            'pages' => $row['paginas'],
        ]);
    }

    public function rules(): array
    {
        return [
            'cliente' => ['required', 'string'],
            'entrega' => ['required', 'date'],
            'paginas' => ['required', 'integer'],
        ];
    }
}
