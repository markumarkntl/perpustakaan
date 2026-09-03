<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriBukuModel extends Model
{
    protected $table            = 'kategori_buku';
    protected $primaryKey       = 'id_kategori';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'nama_kategori',
    ];

    // Timestamps
    protected $useTimestamps = false;

    // Validation
    //
    // Dipisah menjadi dua set rule:
    //   - INSERT : is_unique tanpa pengecualian baris
    //   - UPDATE : is_unique dengan pengecualian baris berdasarkan id_kategori
    //
    // Untuk UPDATE, panggil method validateForUpdate() dari controller,
    // bukan validate() langsung — supaya is_unique tidak memblokir
    // nama yang sama disimpan ke baris yang sama.
    protected $validationRules = [
        'nama_kategori' => 'required|max_length[100]|is_unique[kategori_buku.nama_kategori]',
    ];
    protected $validationMessages = [
        'nama_kategori' => [
            'required'   => 'Nama kategori wajib diisi.',
            'max_length' => 'Nama kategori maksimal 100 karakter.',
            'is_unique'  => 'Nama kategori sudah ada, gunakan nama lain.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = false; // false agar field id_kategori tidak dibuang saat validasi update

    /**
     * Validasi khusus untuk proses UPDATE.
     * Rule is_unique dikecualikan untuk baris dengan id_kategori = $id,
     * sehingga nama yang sama boleh disimpan kembali ke baris yang sama.
     */
    public function validateForUpdate(array $data, int $id): bool
    {
        $rules = [
            'nama_kategori' => "required|max_length[100]|is_unique[kategori_buku.nama_kategori,id_kategori,{$id}]",
        ];

        return $this->validate($data, $rules);
    }

    /**
     * Hitung berapa banyak buku yang masih memakai kategori ini.
     * Dipakai sebelum proses hapus, karena FK buku -> kategori_buku
     * bersifat ON DELETE RESTRICT (lihat db_perpustakaan.sql).
     */
    public function countBukuByKategori(int $idKategori): int
    {
        return $this->db->table('buku')
            ->where('id_kategori', $idKategori)
            ->countAllResults();
    }
}