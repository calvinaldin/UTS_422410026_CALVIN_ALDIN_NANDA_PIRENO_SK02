<?php

return [
    'after'       => ':attribute harus berisi tanggal setelah :date.',
    'array'       => ':attribute harus berupa array.',
    'between'     => [
        'array'   => ':attribute harus memiliki :min sampai :max item.',
        'file'    => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string'  => ':attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'date'        => ':attribute bukan tanggal yang valid.',
    'date_format' => ':attribute tidak sesuai format :format.',
    'distinct'    => ':attribute memiliki nilai yang duplikat.',
    'exists'      => ':attribute yang dipilih tidak valid.',
    'in'          => ':attribute yang dipilih tidak valid.',
    'integer'     => ':attribute harus berupa bilangan bulat.',
    'max'         => [
        'array'   => ':attribute maksimal memiliki :max item.',
        'file'    => ':attribute maksimal berukuran :max kilobita.',
        'numeric' => ':attribute maksimal bernilai :max.',
        'string'  => ':attribute maksimal terdiri dari :max karakter.',
    ],
    'min'         => [
        'array'   => ':attribute minimal memiliki :min item.',
        'file'    => ':attribute minimal berukuran :min kilobita.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string'  => ':attribute minimal terdiri dari :min karakter.',
    ],
    'numeric'     => ':attribute harus berupa angka.',
    'required'    => ':attribute wajib diisi.',
    'string'      => ':attribute harus berupa teks.',
    'unique'      => ':attribute sudah digunakan.',

    'custom'     => [],
    'attributes' => [],
];
