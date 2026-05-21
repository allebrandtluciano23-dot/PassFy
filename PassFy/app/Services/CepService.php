<?php

namespace App\Services;

use App\Models\Cidade;
use Illuminate\Support\Facades\Http;

class CepService
{
    private const VIACEP_URL = 'https://viacep.com.br/ws';

    /**
     * Busca cidade por CEP
     * Consulta a API ViaCEP e cruza com o banco local de cidades
     */
    public function buscarPorCep(string $cep): ?array
    {
        $cepLimpo = preg_replace('/\D/', '', $cep);

        if (strlen($cepLimpo) !== 8) {
            throw new \Exception('CEP deve conter 8 dígitos');
        }

        // Consulta a API ViaCEP
        try {
            $response = Http::timeout(5)->get(self::VIACEP_URL . "/{$cepLimpo}/json");

            if ($response->failed() || isset($response['erro'])) {
                return [
                    'success' => false,
                    'message' => 'CEP não encontrado na API',
                ];
            }

            $dados = $response->json();

            // Busca a cidade no banco local
            $cidade = Cidade::where('nomeCidade', $dados['localidade'])
                ->where('ufCidade', $dados['uf'])
                ->first();

            if (!$cidade) {
                return [
                    'success' => false,
                    'message' => "Cidade {$dados['localidade']}/{$dados['uf']} não cadastrada no banco",
                ];
            }

            return [
                'success' => true,
                'source' => 'api',
                'cep' => $cepLimpo,
                'idCidade' => $cidade->idCidade,
                'nomeCidade' => $cidade->nomeCidade,
                'ufCidade' => $cidade->ufCidade,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao consultar API de CEP: ' . $e->getMessage(),
            ];
        }
    }
}
