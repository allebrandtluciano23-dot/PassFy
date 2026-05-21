<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CidadeSeeder extends Seeder
{
    public function run()
    {
        $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');
        $municipios = $response->json();

        foreach ($municipios as $municipio) {
            $uf = $municipio['microrregiao']['mesorregiao']['UF']['sigla'] ?? null;
        if (!$uf) {
            $uf = '??';
        }
    
    DB::table('cidade')->insert([
        'nomeCidade' => $municipio['nome'],
        'ufCidade' => $uf,
        'idCidade' => $municipio['id'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
    }
}