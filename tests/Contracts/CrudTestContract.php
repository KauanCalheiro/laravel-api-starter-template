<?php

namespace Tests\Contracts;

interface CrudTestContract
{
    public function test_listagem_com_paginacao();
    public function test_listagem_com_paginacao_e_filtros();
    public function test_erro_listagem_com_filtros_incorretos();
    public function test_exibe_registro_existente();
    public function test_exibe_registro_inexistente();
    public function test_cria_registro();
    public function test_erro_cria_registro_com_campos_incorretos();
    public function test_atualiza_registro();
    public function test_erro_atualiza_registro_com_campos_incorretos();
    public function test_deleta_registro();
    public function test_erro_deleta_registro_inexistente();
}
