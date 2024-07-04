<?php

namespace App\Imports;

use App\Card;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class cardsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new card([
            'name'          => $row['name'],
            'alamat'        => $row['alamat'],
            'email'         => $row['email'],
            'telepon'       => $row['telepon']
        ]);
    }
}
