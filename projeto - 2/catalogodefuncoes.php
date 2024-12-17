<?php
    # Bloco de definição de Funções

    function iniciapagina($tabformal,$acoes)
    {# função que emite as TAG's iniciais de página HTML para os PA que gerenciam dados de tabelas do sistema.
      printf("<html>\n");
      printf("<head>\n");
      printf("  <title>Ger. $tabformal</title>\n");
      printf("  <style type='text/css'>
                 body           { background-color:#FFDEAD;
                                  margin:0px;
                                  font-family: TAHOMA, Arial, sans-serif; }
                 body.Menu      { background-color:#FFDEAD; }
                 body.Abertura  { background-color:#FFDEAD; }
                 body.Incluir   { background-color:#E5B18B; }
                 body.Consultar { background-color:#9CAB91; }
                 body.Alterar   { background-color:#DD9A69; }
                 body.Excluir   { background-color:#D29588; }
                 body.Listar    { background-color:#BDC7B5; }
                 body.Imprimir  { background-color:#FFFFFF; }
                 button     { background-color: transparent;
                              font-family: TAHOMA, Arial, sans-serif;
                              font-size: 14px;
                              margin: 7px -2px -3px -2px;
                              color: black;
                              text-align: center;
                              border: none;
                              border-radius: 15px 15px 0px 0px;
                              width: 85px;
                              height: 23px;
                              cursor: pointer; }
                 button.ins { background-color:#E5B18B; }
                 button.con { background-color:#9CAB91;  }
                 button.alt { background-color:#DD9A69; }
                 button.del { background-color:#D29588; }
                 button.lst { background-color:#BDC7B5; }
                 button.imp { background-color:transparent; }
                 button.out { background-color:transparent; }
                 div.menu   { background-color:#FFDEAD;
                              position:static;
                              top:0;
                              left:0;
                              width:100%%;
                              height:30px;
                              border:none;
                               }
                   negrito { font-size: 14px; 
                            font-weight: bold; }
                              \n");
      printf("  </style>\n");
      # Na Tarefa 3 este comando foi comentado e escrevemos toda a especificação CSS 'dentro' do HTML [modo INTERNO]
      #  printf("  <link rel='stylesheet' href='./$tabformal.css'>\n");
      printf("</head>\n");
      printf("<body class='$acoes'>\n");
    
    }



   function botoes($acoes,$limpar,$voltar,$salto){
        #note esta finção está em construção e portanto ainda nao usada
        # Função : botoes
        #categorai : SISTEMICA -  vai para catalogo de funçoes DEPOOIS CONCLUIDA
        # Objetivo : MONTAR a barra de botoes de cada bloco dos PA tomando a referebcia dos argumentos dos parametros.
        # Descrição : USANDO operadorers trnários determina a constrtução das tags que apresemtam os botoes de desenvolvimento das funcionalidadde,

        $barra=($acoes=="Gerar Cópia para impressão") ? "<button style='width:220px;' type='submit'>$acoes</button>\n" : "<button type='submit'>$acoes</button>";
        $barra= $barra. (($limpar) ? "<button type='reset'>Limpar</button>\n" :"");
        $barra= $barra. (($voltar) ? "<button type='button' onclick='history.go(-1)'>< Voltar</button>\n" :"");
        $barra= $barra.  "<button type='button' onclick='history.go(-($salto-1))'>< inicio</button>\n";
        printf("$barra\n");
  }

  
    function conectamariadb($server, $username, $senha, $dbname )
    {   # Conexão com banco de dados
        global $link;
        $link = mysqli_connect($server, $username, $senha, $dbname);

    }

    function escolhefk($tabformal, $pk, $txOpe, $fk)
    {
        global $link;
    
        $cmdsql="SELECT $pk, $txOpe FROM $tabformal";
        printf("$cmdsql<br>\n");
        $execcmd=mysqli_query($link, $cmdsql);
        
        printf("<select name='$fk'");
    
      while(  $reglido=mysqli_fetch_array($execcmd))
      {
        printf("<option value='$reglido[$pk]'> $reglido[$txOpe] - $reglido[$pk]  </option>");
    
      }
      printf("</select>");
    }    

    function terminapagina($acoes,$tabformal)
    {# emissão das tags de 'finalização' da pagina [HTML]
        printf("<hr style='border: 1px solid #737373;'>");
        printf(" Faturas Vendas-$acoes - | &copy; 2024-2 - GCB+FATEC 4&ordm;ADS-TARDE | $tabformal\n");
        printf("</body>");
        printf("</html>");


    }

    # Fim do Bloco de Definiçôes de Funções
    # executando a função de conexão
    conectamariadb("localhost", "root","", "ilp540");

?>