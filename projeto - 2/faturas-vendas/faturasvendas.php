<?php
#####################################################################################################################################################
# Programa.: medicos.php
# Objetivo.: PA-Recursivo G2 que disponibiliza as funcionalidades [ICAEL] e o SAIR em um menu no topo da tela. Para cada funcionalidade existem um
#            marcador de passagem [$bloco] que controla a execução da funcionalidade.
# Autor....: GCB
# Descrição: PA-Recursivo com dois blocos. $acoes é o marcador de passagem de grau 1 controlando a execução de blocos de comandos para as
#            funcionalidades. $bloco é o marcador de passagem de grau 2 controlando a execução de blocos de comandos (dentro de  cada funcionalidade) 
#            Existe uma terceira variável com atribuição de valor recursiva que determina a quantidade de páginas avançadas durante o uso da
#            aplicação permitindo construir um sistema de navegação com botões interno NA aplicação.
# Criação..: 2024-11-01
# Alteração: 2024-11-03 - Estrutura fundamental do app escrita integralmente durante uma aula.
#            2024-11-05 - Incorporação da funcionalidade de Consulta
#            2024-11-08 - determinando e escrevendo funções sistemas e locais no PA
#            2024-11-18 - inclusão do trecho que trata a funcionalidade 'incluir' 
#            2024-11-20 - inclusão do trecho que trata a funcionalidade 'Excluir' 
#            2024-11-18 - inclusão do trecho que trata a funcionalidade 'Alterar' 
#            2024-11-28 - inclusão do trecho que trata a funcionalidade 'Listar' 
#####################################################################################################################################################
function picklist($acoes, $salto){

        # Globalizando a variavel  de conexão
    global $link;


    $cmdsql = "SELECT pkfaturavenda, vlfatura, dtvencimento FROM faturasvendas";
    $execcmd = mysqli_query($link, $cmdsql);

    printf("<div class='tabela'> ");
    
    printf("<form action='./faturasvendas.php' method='POST'>\n");
    printf("<input type='hidden' name='acoes' value='$acoes'>\n");
    printf("<input type='hidden' name='bloco' value='2'>\n");
    printf("<input type='hidden' name='salto' value='$salto'>\n");

    

    printf("<select name='pkfaturasvendas'>\n");
    while ($reg = mysqli_fetch_array($execcmd)) {
        printf("<option value='$reg[pkfaturavenda]'>Fatura: $reg[pkfaturavenda] - Valor: $reg[vlfatura] - Vencimento: ($reg[dtvencimento])</option>\n");
    }
    printf("</select>");

  #  botoes($acoes,true, true, $salto); # Quando a função botoes() estiver pronta vai substituir os trechos com as tags BUTTON.
    printf("<button type='submit' name='bt' value='Botao'>Escolher</button>\n");
    printf("<button class='limpar' type='reset' >Limpar</button>");  // Botão Limpar à direita
    printf("<button type='button' onclick='history.go(-1)'>Sair</button>\n");
    printf("</form>");

}


function mostraregistro($CP , $acao, $salto){

    // Globalizando a variável de conexão
    global $link;

    $cmdsql = "SELECT f.*, n.txnaturezaoperacao, n.vltotalnfvenda, n.dtvenda, n.dtemissao
    FROM faturasvendas f
    LEFT JOIN nfvendas n ON f.fknunfvenda = n.pknunfvenda
    WHERE f.pkfaturavenda = '$CP'";

    $execcmd = mysqli_query($link, $cmdsql);
    $reg = mysqli_fetch_array($execcmd);
    
    // Início da tabela
    printf("<table>\n");

    // Faturas
    printf("<tr><td colspan='2' class='titulo'>Faturas:</td></tr>");
    printf("<tr><td>Fatura:</td>               <td>%s</td></tr>", $reg['pkfaturavenda']);
    printf("<tr><td>Nota Fiscal:</td>          <td>%s</td></tr>", $reg['fknunfvenda']);
    printf("<tr><td>Data de Vencimento:</td>   <td>%s</td></tr>", $reg['dtvencimento']);
    printf("<tr><td>Valor Bruto:</td>          <td>%s</td></tr>", $reg['vlfatura']);
    printf("<tr><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");

    // Valores
    printf("<tr><td colspan='2' class='titulo'>Valores:</td></tr>");
    printf("<tr><td>Desconto:</td>             <td>%s</td></tr>", $reg['vldesconto']);
    printf("<tr><td>Valor Líquido:</td>        <td>%s</td></tr>", $reg['vlliquido']);
    printf("<tr><td>Multa:</td>                <td>%s</td></tr>", $reg['vlmulta']);
    printf("<tr><td>Juros:</td>                <td>%s</td></tr>", $reg['vljuros']);
    printf("<tr><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");

    // Cadastro
    printf("<tr><td colspan='2' class='titulo'>Cadastro:</td></tr>");
    printf("<tr><td>Data Criação:</td>         <td>%s</td></tr>", $reg['dtcriacaofatura']);
    printf("<tr><td>Data Cadastro:</td>        <td>%s</td></tr>", $reg['dtcadfatura']);

    printf("<tr><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
    printf("<tr><td>Nota Fiscal:</td>        <td>%s</td></tr>", $reg['fknunfvenda']);


    printf("<tr><td>&nbsp;</td>               <td>");
    // Botões
    if ($acao == "Excluir") {
        botoes($acao, false, true, $salto);
    } elseif ($acao == "Incluir" || $acao == "Consultar" || $acao == "Alterar") {
        botoes("", false, true, $salto);
    }

    printf("</td></tr>");
     // Fechando a tabela
     printf("</table>\n");
}


# Variáveis de controle recursivo
$acoes = (isset($_REQUEST['acoes'])) ? $_REQUEST['acoes'] : "Abertura";
$bloco = (isset($_REQUEST['bloco'])) ? $_REQUEST['bloco'] : 1;
$salto = (isset($_REQUEST['salto'])) ? $_REQUEST['salto'] + 1 : 1;



# Acessando o ('catalogo de funçoes'):  
# INCLUDE("Caminho e nome do arquivo"):  - Se o 2 form interrompido a 1 continua a execução
# REQUIRE("Caminho e nome do arquivo");  - Se o 1 form interrompido a 2 continua a execução
# TAG's de inicio da pagina [HTML]
#_ONCE() -  Fazem a leitura do Arq. ExternoSOMENTE UMA VEZ

require_once("../catalogodefuncoes.php");

iniciapagina("faturasvendas",$acoes);

 if( $acoes!=="imprimir")
 {
    # Menu de funcionalidades
    printf("<div class='$acoes'>");
    printf("<div class='menu'>");
    printf("<form action='./faturasvendas.php' method='POST'>\n");
    printf("<input type='hidden' name='salto' value='$salto'>\n");
    printf("<titulo>Faturas Vendas:</titulo>&nbsp;&nbsp;");

    printf("<button class='ins' type='submit' name='acoes' value='Incluir'>Incluir</button>\n");
    printf("<button class='con' type='submit' name='acoes' value='Consultar'>Consultar</button>\n");
    printf("<button class='alt' type='submit' name='acoes' value='Alterar'>Alterar</button>\n");
    printf("<button class='del' type='submit' name='acoes' value='Excluir'>Excluir</button>\n");
    printf("<button class='lst' type='submit' name='acoes' value='Listar'>Listar</button>\n");
    printf("<button class='nav' type='button' onclick='history.go(-$salto)'>Sair</button>\n");

    printf("</form>");
    printf("</div>");
    printf("<titulo>$acoes</titulo>");
    printf("</div><hr style='border: 1px solid #737373;'>");
 }

global $link;




# Processamento das ações
switch (true) {
    case ($acoes == "Abertura"):
        printf("<div class='inicio'>");
        printf("Este sistema faz o Gerenciamento da tabela Faturas Vendas. <br> ");
        printf("O menu apresentado acima indica as funcionalidades do sistema. <br><br>");
        printf("Em cada tela do Sistema são apresentados acessos para:");

        printf("<ul>");
        printf("<li> <span class='sublinhado'>Voltar</span> uma tela na navegação das funcionalidades;</li>");
        printf("<li> <span class='sublinhado'>Abertura</span> (Esta página);</li>");
        printf("<li> <span class='sublinhado'>Limpar</span> os campos do Formulário (se preciso);</li>");
        printf("<li> <span class='sublinhado'>Ação</span> de completar a funcionalidade escolhida.</li>");
        printf("</ul> <br>");

        printf("A escolha de <span class='sublinhado'>'Sair'</span> do Sistema no lado direito da barra de abas do menu. <br><br>");
        printf("Nesta tela DEVEM SER exibidos o nome e matrícula (RA) do aluno. <br> <br>");
        printf("Nome: Gean Corrêa Bacinello  RA: 0210482312037 <br><br>");
        printf("</div>");
        break;

    case ($acoes == "Incluir"):
            switch (TRUE) {
                case ($bloco == 1):
                    { 
                        # monta o form para digitação dos dados de faturasvendas
                        printf("<form action='./faturasvendas.php' method='POST'>\n");
                        printf("<input type='hidden' name='acoes' value='$acoes'>\n");
                        printf("<input type='hidden' name='bloco' value='2'>\n");
                        printf("<input type='hidden' name='salto' value='$salto'>\n");
        
                        printf("<table>\n");
                        printf("<tr><td>Código:</td>                   <td> O código será gerado pelo Sistema</td></tr>\n");
                     
                        printf("<tr><td>Vencimento:</td>               <td><input type='date' name='dtvencimento'  size=12></td></tr>\n"); 
                        printf("<tr><td>Valor:</td>                    <td><input type='number' name='vlfatura' placeholder='Números' size=12></td></tr>\n");
        
                        printf("<tr><td></td><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
                        printf("<tr><td>Desconto:</td>                 <td><input type='number' name='vldesconto' placeholder='Números' required></td></tr>");

                        printf("<tr><td>Valor Líquido:</td>            <td><input type='number' name='vlliquido'  placeholder='Números' required></td></tr>");
                        printf("<tr><td>Multa:</td><td>                <input type='number' name='vlmulta'  placeholder='Números' required></td></tr>");
                        printf("<tr><td>Juros:</td>                    <td><input type='number' name='vljuros' placeholder='Números' required></td></tr>");
        
                        printf("<tr><td></td><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
                        printf("<tr><td>Data de Criação:</td>          <td><input type='date' name='dtcriacaofatura' required></td></tr>");
                        printf("<tr><td>Data de Cadastro:</td>         <td><input type='date' name='dtcadfatura' required></td></tr>");
                      
                        printf("<tr><td></td><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
                        printf("<tr><td>Nota Fiscal:</td>               <td><input type='number' name='fknunfvenda' size=12 </td></tr>");

                        printf("<tr><td></td><td>");
                        botoes($acoes, TRUE, TRUE, $salto);  
                        printf("</td></tr>\n");
                        printf("</table>\n");
                        printf("</form>\n");
                        break;
                    }
        
                case ($bloco == 2):
                    # Tratamento da Transação de INCLUSÃO
                    $tenta = TRUE;
                    while ($tenta) {
                        # Laço de controle de execução da transação 
                        mysqli_query($link, "START TRANSACTION");
        
                        # Recuperação do último valor gravado na pk da tabela
                        $ultimacp = mysqli_fetch_array(mysqli_query($link, "SELECT MAX(pkfaturavenda) AS CpMAX FROM faturasvendas"));
                        $CP = $ultimacp['CpMAX'] + 1;
        
                        # Construção do comando de inclusão
                        $cmdsql = "INSERT INTO faturasvendas (pkfaturavenda, dtvencimento, vlfatura, vldesconto, vlliquido, vlmulta, vljuros, dtcriacaofatura, dtcadfatura, fknunfvenda)
                                   VALUES (
                                       '$CP',
                                       '$_REQUEST[dtvencimento]',
                                       '$_REQUEST[vlfatura]',
                                       '$_REQUEST[vldesconto]',
                                       '$_REQUEST[vlliquido]',
                                       '$_REQUEST[vlmulta]',
                                       '$_REQUEST[vljuros]',
                                       '$_REQUEST[dtcriacaofatura]',
                                       '$_REQUEST[dtcadfatura]',
                                       '$_REQUEST[fknunfvenda]'
                                   
                                   )";
        
                        # Execução do comando de inclusão
                        mysqli_query($link, $cmdsql);
        
                        if (mysqli_errno($link) == 0) {
                            # Se não houver erro, confirma a transação
                            mysqli_query($link, "COMMIT");
                            $tenta = FALSE;
                            $mostrar = TRUE;
                            $mens = "Registro Incluído com sucesso!";
                        } else {
                            if (mysqli_errno($link) == 1213) {
                                # Se o erro for 1213 (deadlock), reinicia a transação
                                $tenta = TRUE;
                            } else {
                                # Se for outro erro, aborta a transação
                                $tenta = FALSE;
                                $mens = mysqli_errno($link) . " - " . mysqli_error($link);
                            }
                            # Se houve erro, desfaz a transação
                            mysqli_query($link, "ROLLBACK");
                            $mostrar = FALSE;
                        }
        
                        # Exibe a mensagem e o registro
                        printf("%s<br>", $mens);
                        ($mostrar) ? mostraregistro($CP,$acoes,$salto) : printf("");
                        break;
                    }
                    
            }
            break;
        

    case ($acoes == "Consultar"):
        switch (true) {
            case ($bloco == 1): // Monta o form com a picklist para escolher um registro
                picklist($acoes, $salto);
                break;

            case ($bloco == 2): // Recebe o valor da PK para selecionar o registro
                mostraregistro($_REQUEST['pkfaturasvendas'] , $acoes, $salto);
                break;
        }
        break;

    case ($acoes == "Alterar"):
        // Escolha do registro a "Alterar"
        {
            switch(TRUE)
            {
                case ( $bloco==1):
                {# escolha do registro a  alterar
                    picklist($acoes, $salto);
                    break;
                }

                case ($bloco==2):
                {# construção do fromulario com osdados do registro escolhido
                    # lendo o registro da tabela e "vetorizando" os dados,   value='$reg[]' 22:22
                    $reg=mysqli_fetch_array(mysqli_query($link, "SELECT * FROM faturasvendas WHERE pkfaturavenda='$_REQUEST[pkfaturasvendas]'"));
                    printf("<form action='./faturasvendas.php' method='POST'>\n");  
                    printf("<input type='hidden' name='acoes' value='$acoes'>\n");
                    printf("<input type='hidden' name='bloco' value='3'>\n");
                    printf("<input type='hidden' name='salto' value='$salto'>\n");
                    printf("<input type='hidden' name='pkfaturasvendas' value='%s'>\n", $_REQUEST['pkfaturasvendas']);

                    printf("<table>");
                    printf("<tr><td>Código:</td>                  <td> O código será gerado pelo Sistema</td></tr>\n");
                     
                    printf("<tr><td>Vencimento:</td>              <td><input type='date' name='dtvencimento' value='$reg[dtvencimento]'></td></tr>\n"); 
                    printf("<tr><td>Valor:</td>                   <td><input type='number' name='vlfatura' value='$reg[vlfatura]' placeholder='Números' size=12></td></tr>\n");
    
                    printf("<tr><td></td><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
                    printf("<tr><td>Desconto:</td>          <td><input type='number' name='vldesconto' value='$reg[vldesconto]' placeholder='Números'></td></tr>");

                    printf("<tr><td>Valor Líquido:</td>           <td><input type='number' name='vlliquido'  value='$reg[vlliquido]' placeholder='Números' ></td></tr>");
                    printf("<tr><td>Multa:</td>                   <td><input type='number' name='vlmulta' value='$reg[vlmulta]' placeholder='Números'></td></tr>");
                    printf("<tr><td>Juros:</td>                   <td><input type='number' name='vljuros' value='$reg[vljuros]' placeholder='Números'></td></tr>");
    
                    printf("<tr><td></td><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
                    printf("<tr><td>Data de Criação:</td>         <td><input type='date' name='dtcriacaofatura'  value='$reg[dtcriacaofatura]' ></td></tr>");
                    printf("<tr><td>Data de Cadastro:</td>        <td><input type='date' name='dtcadfatura'  value='$reg[dtcadfatura]'></td></tr>");
                    
                    printf("<tr><td></td><td colspan='2'><hr style='border: 1px solid #737373;'></td></tr>");
                    printf("<tr><td>Nota Fiscal:</td>               <td><input type='number' name='fknunfvenda' value='$reg[fknunfvenda]' size=12 </td></tr>");

                    printf("<tr><td></td><td>");
                    botoes($acoes, TRUE, TRUE, $salto);  
                    printf("</td></tr>\n");
                    printf("</table>\n");
                    printf("</form>\n");

                    break;
                }

                case ($bloco==3):
                    { # tratamento da transação
                     
                        # COnstrução do camando de atualização.
                        // Corrigindo o comando SQL para excluir o registro com a chave primária correta
                        $cmdsql = "UPDATE faturasvendas  SET    dtvencimento    = '$_REQUEST[dtvencimento]',
                                                                vlfatura        = '$_REQUEST[vlfatura]',
                                                                vldesconto      = '$_REQUEST[vldesconto]',
                                                                vlliquido       = '$_REQUEST[vlliquido]',
                                                                vlmulta         = '$_REQUEST[vlmulta]', 
                                                                vljuros         = '$_REQUEST[vljuros]',
                                                                dtcriacaofatura = '$_REQUEST[dtcriacaofatura]',
                                                                dtcadfatura     = '$_REQUEST[dtcadfatura]',
                                                                fknunfvenda     = '$_REQUEST[fknunfvenda]',
                                              WHERE pkfaturavenda = '$_REQUEST[pkfaturasvendas]'";
        
                        // Executando a transação de exclusão
                        $tenta = TRUE;
                        while ($tenta) {
                            // Inicia a transação
                            mysqli_query($link, "START TRANSACTION");
        
                            // Executa o comando de exclusão
                            mysqli_query($link, $cmdsql);
        
                            if (mysqli_errno($link) == 0) {
                                // Se não houver erro, confirma a transação
                                mysqli_query($link, "COMMIT");
                                $tenta = FALSE;
                                $mostrar=TRUE;
                                $mens = "Registro com código $_REQUEST[pkfaturasvendas] Alterado com sucesso!";
                            } else {
                                if (mysqli_errno($link) == 1213) {
                                    // Se o erro for 1213 (deadlock), reinicia a transação
                                    $tenta = TRUE;
                                } else {
                                    // Se for outro erro, aborta a transação
                                    $tenta = FALSE;
                                    $mens = mysqli_errno($link) . " - " . mysqli_error($link);
                                }
                                // Se houve erro, desfaz a transação
                                $mostrar=FALSE;
                                mysqli_query($link, "ROLLBACK");
                            }
                        }
        
                        // Exibe a mensagem de sucesso ou erro
                        printf("$mens<br>\n");
                        ($mostrar) ?  mostraregistro($_REQUEST['pkfaturasvendas'] , $acoes, $salto) : printf("") ;
                        break;
                    }
            }

        break;
      }
    case ($acoes == "Excluir"):
        switch (true) {
            case ($bloco == 1): // Monta o form com a picklist para escolher um registro
                picklist($acoes, $salto);
                break;

            case ($bloco == 2): // Recebe o valor da PK para selecionar o registro
                printf("<form action='./faturasvendas.php' method='POST'>\n");
                printf("<input type='hidden' name='acoes' value='$acoes'>\n");
                printf("<input type='hidden' name='bloco' value='3'>\n");
                printf("<input type='hidden' name='salto' value='$salto'>\n");
                printf("<input type='hidden' name='pkfaturasvendas' value='%s'>\n", $_REQUEST['pkfaturasvendas']);

                mostraregistro($_REQUEST['pkfaturasvendas'], $acoes, $salto);
                printf("</form>");
                printf("\n");
               
                break;

            case ($bloco == 3):
                { 
                printf("Fatura para Exclusão $_REQUEST[pkfaturasvendas]");
                # COnstrução do camando de atualização.
                // Corrigindo o comando SQL para excluir o registro com a chave primária correta
                $cmdsql = "DELETE FROM faturasvendas WHERE pkfaturavenda = '$_REQUEST[pkfaturasvendas]'";

                // Executando a transação de exclusão
                $tenta = TRUE;
                while ($tenta) {
                    // Inicia a transação
                    mysqli_query($link, "START TRANSACTION");

                    // Executa o comando de exclusão
                    mysqli_query($link, $cmdsql);

                    if (mysqli_errno($link) == 0) {
                        // Se não houver erro, confirma a transação
                        mysqli_query($link, "COMMIT");
                        $tenta = FALSE;
                        $mens = "Registro com código $_REQUEST[pkfaturasvendas] excluído com sucesso!";
                    } else {
                        if (mysqli_errno($link) == 1213) {
                            // Se o erro for 1213 (deadlock), reinicia a transação
                            $tenta = TRUE;
                        } else {
                            // Se for outro erro, aborta a transação
                            $tenta = FALSE;
                            $mens = mysqli_errno($link) . " - " . mysqli_error($link);
                        }
                        // Se houve erro, desfaz a transação
                        mysqli_query($link, "ROLLBACK");
                    }
                }

                // Exibe a mensagem de sucesso ou erro
                printf("$mens<br>\n");
                
            }
        }
        
        break;

        case ($acoes == "Listar" or $acoes == "Imprimir"):
            {
                switch (TRUE)
                {
                    case ($bloco == 1):
                    { 
                        # Formulário para escolha de ordem e critérios de seleção
                        printf("<form action='./faturasvendas.php' method='post'>\n");
                        printf("<input type='hidden' name='acoes' value='$acoes'>\n");
                        printf("<input type='hidden' name='bloco' value='2'>\n");
                        printf("<input type='hidden' name='salto' value='$salto'>\n");
                        printf("<table>\n");
                        printf("<tr><td colspan=2>Escolha a <strong>ordem</strong> como os dados serão exibidos na listagem:</td></tr>\n");
                        printf("<tr><td>Código da Fatura:</td><td>(<input type='radio' name='ordem' value='pkfaturavenda' checked>)</td></tr>\n");
                        printf("<tr><td>Valor da Fatura:</td><td>(<input type='radio' name='ordem' value='vlfatura'>)</td></tr>\n");
            
                        printf("<tr><td colspan=2>Escolha valores para seleção de <strong>dados</strong> do relatório:</td></tr>\n");
                        printf("<tr><td>Escolha uma Fatura:</td><td>\n");
                        $cmdsql = "SELECT pkfaturavenda, vlfatura FROM faturasvendas ORDER BY vlfatura";
                        $execcmd = mysqli_query($link, $cmdsql);
                        printf("<select name='pkfaturavenda'>\n");
                        printf("<option value='TODAS'>Todas</option>\n");
                        while ($reg = mysqli_fetch_array($execcmd)) {
                            printf("<option value='$reg[pkfaturavenda]'>Fatura $reg[pkfaturavenda] - R$ $reg[vlfatura]</option>\n");
                        }
                        printf("</select>\n");
                        printf("</td></tr>\n");
            
                        $dtini = "1901-01-01";
                        $dtfim = date("Y-m-d");
                        printf("<tr><td>Datas de cadastro:</td><td>De <input type='date' name='dtcadini' value='$dtini'> até <input type='date' name='dtcadfim' value='$dtfim'></td></tr>");
                        printf("<tr><td></td><td>"); botoes($acoes, TRUE, TRUE, $salto); printf("</td></tr>\n");
                        printf("</table>\n");
                        printf("</form>\n");
                        break;
                    }
                    case ($bloco == 2 or $bloco == 3):
                    {
                        $cmdsql = "SELECT * FROM faturasvendas 
                                   WHERE (dtcadfatura BETWEEN '$_REQUEST[dtcadini]' AND '$_REQUEST[dtcadfim]')";
                        $cmdsql = ($_REQUEST['pkfaturavenda'] != 'TODAS') ? $cmdsql . " AND pkfaturavenda = '$_REQUEST[pkfaturavenda]'" : $cmdsql;
                        $cmdsql = $cmdsql . " ORDER BY $_REQUEST[ordem]";
                        $execsql = mysqli_query($link, $cmdsql);
            
                        # Tabela com os dados selecionados
                        printf("<table border=1 style='border-collapse: collapse;'>\n");
                        printf("<tr bgcolor='lightblue' >\n");
                        printf("<td > Código</td>\n");
                        printf("<td > Valor Bruto</td>\n");
                        printf("<td > Desconto</td>\n");
                        printf("<td > Valor Líquido</td>\n");
                        printf("<td > Data de Vencimento</td>\n");
                        printf("<td > Data de Cadastro</td>\n");
                        printf("<td > Nota Fiscal</td>\n");
                        printf("</tr>\n");
            
                        $cordalinha = "White";
                        while ($le = mysqli_fetch_array($execsql)) {
                            printf("<tr bgcolor=$cordalinha>\n");
                            printf("<td >$le[pkfaturavenda]</td>\n");
                            printf("<td >R$ $le[vlfatura]</td>\n");
                            printf("<td >R$ $le[vldesconto]</td>\n");
                            printf("<td >R$ $le[vlliquido]</td>\n");
                            printf("<td >$le[dtvencimento]</td>\n");
                            printf("<td >$le[dtcadfatura]</td>\n");
                            printf("<td >$le[fknunfvenda]</td>\n");
                            printf("</tr>\n");
                            $cordalinha = ($cordalinha == "White") ? "Navajowhite" : "White";
                        }
                        printf("</table>\n");
            
                        if ($bloco == 2) {
                            printf("<form action='./faturasvendas.php' method='POST' target='_NEW'>\n");
                            printf("<input type='hidden' name='acoes' value='Imprimir'>\n");
                            printf("<input type='hidden' name='bloco' value=3>\n");
                            printf("<input type='hidden' name='salto' value='$salto'>\n");
                            printf("<input type='hidden' name='pkfaturavenda' value='$_REQUEST[pkfaturavenda]'>\n");
                            printf("<input type='hidden' name='dtcadini' value='$_REQUEST[dtcadini]'>\n");
                            printf("<input type='hidden' name='dtcadfim' value='$_REQUEST[dtcadfim]'>\n");
                            printf("<input type='hidden' name='ordem' value='$_REQUEST[ordem]'>\n");
                            

                           # botoes($acoes,$limpar,$voltar,$salto)
                            botoes("Gerar Cópia para impressão", FALSE, TRUE, $salto);
                            
                            printf("</form>\n");
                        } else {
                            printf("<button type='submit' onclick='window.print();'>Imprimir</button> - Corte a folha abaixo da linha no final da página<br>\n<hr style='border: 1px solid #737373;'>\n");
                        }
                        break;
                    }
                }
                break;
            }
            

        break;
}

terminapagina($acoes,"faturasvendas.php");
?>
