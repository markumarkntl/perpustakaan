<?php

namespace App\Models;

use CodeIgniter\Model;

class PetugasModel extends Model
{
    protected $table            = 'petugas';
    protected $primaryKey       = 'id_petugas';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'nama_petugas',
        'username',
        'password',
    ];

    // Timestamps
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'nama_petugas' => 'required|max_length[120]',
        'username'     => 'required|max_length[50]|is_unique[petugas.username,id_petugas,{id_petugas}]',
        'password'     => 'required|max_length[255]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Cari petugas berdasarkan username.
     * Digunakan pada proses login.
     */
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}
