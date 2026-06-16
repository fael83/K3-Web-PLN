<?php
return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string'   => 'Kolom :attribute harus berupa teks.',
    'email'    => 'Kolom :attribute harus berupa alamat email yang valid.',
    'max'      => [
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        'file'   => 'File :attribute tidak boleh lebih besar dari :max kilobytes.',
    ],
    'min'      => ['numeric' => 'Kolom :attribute minimal :min.'],
    'integer'  => 'Kolom :attribute harus berupa bilangan bulat.',
    'boolean'  => 'Kolom :attribute harus berupa true atau false.',
    'in'       => 'Nilai yang dipilih untuk :attribute tidak valid.',
    'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
    'image'    => 'Kolom :attribute harus berupa gambar.',
    'nullable' => '',
    'attributes' => [],
];
