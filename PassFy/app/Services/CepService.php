<?php

namespace App\Services;

use App\Models\Cidade;
use Illuminate\Support\Facades\Http;

class CepService
{
    private const VIACEP_URL = 'https://viacep.com.br/ws';

    /**
     * Busca cidade por CEP
     * Primeiro tenta no banco de dados, depois na API ViaCEP
     */
    public function buscarPorCep(string $cep): ?array
    {
        // Limpar CEP (remover formatação)
        $cepLimpo = preg_replace('/\D/', '', $cep);

        // Validar CEP (deve ter 8 dígitos)
        if (strlen($cepLimpo) !== 8) {
            throw new \Exception('CEP deve conter 8 dígitos');
        }

        // Buscar no banco de dados
        $cidade = Cidade::where('cepCidade', $cepLimpo)->first();

        if ($cidade) {
            return [
                'success' => true,
                'source' => 'database',
                'cep' => $cepLimpo,
                'nomeCidade' => $cidade->nomeCidade,
                'ufCidade' => $cidade->ufCidade,
            ];
        }

        // Buscar na API ViaCEP
        try {
            $response = Http::timeout(5)->get(self::VIACEP_URL . "/{$cepLimpo}/json");

            if ($response->failed() || isset($response['erro'])) {
                return [
                    'success' => false,
                    'message' => 'CEP não encontrado',
                ];
            }

            $dados = $response->json();

            // Salvar no banco para futuras buscas
            $cidade = Cidade::where('nomeCidade', $dados['localidade'])
                ->where('ufCidade', $dados['uf'])
                ->first();

            if (!$cidade) {
                $cidade = Cidade::create([
                    'nomeCidade' => $dados['localidade'],
                    'ufCidade' => $dados['uf'],
                    'cepCidade' => $cepLimpo,
                ]);
            }

            return [
                'success' => true,
                'source' => 'api',
                'cep' => $cepLimpo,
                'nomeCidade' => $dados['localidade'],
                'ufCidade' => $dados['uf'],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao buscar CEP: ' . $e->getMessage(),
            ];
        }
    }
}
