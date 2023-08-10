<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .tabela {
            border: 1px;
            border-style: solid ;
            border-color: grey;
            width: 100%;
            margin-top: 5%;
            font-family: courier,Arial, Helvetica, sans-serif;
        }

        .alinhamento {
            text-align-last: right;
            font-size: 12pt;
            font-weight: bold;
            line-height: 1.8;
            align-items: flex-end;
            
        }
        
    </style>
</head>
<body>
    <table style="width: 100%">
        <tr>
          <td><img src="{{ asset('img/logo-ifpe.png') }}" alt="Logo" width="150" height="200"></td>
          <td> <p style="width: 100%; font-size:24px; font-family: 'courier,arial,helvetica font-weight: bold;" align="center">Divisão de Extensão do IFPE - Campus Garanhuns</p>
            <p style="width: 100%; font-size:18px; font-family: 'courier,arial,helvetica font-weight: bold;" align="center">COMPROVANTE DE INSCRIÇÃO</p></td>
       </tr>  
      </table>
      <table class="tabela">
        <tr>
            <td>
                <label class="alinhamento">Evento/Ação:</label> 
                {{$inscricao->acao->titulo}}
            </td>
            
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Nome:</label>
                {{ $nomeInscrito }}         
  
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Local:</label>
                {{ $inscricao->acao->local }} 
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Data de Início:</label>
                {{\Carbon\Carbon::parse($inscricao->acao->data_inicio)->format('d/m/Y')}}

            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Data de Encerramento:</label>
                {{\Carbon\Carbon::parse($inscricao->acao->data_encerramento)->format('d/m/Y')}}
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Hora de Início:</label>
                {{ $inscricao->acao->hora_inicio }} 
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Hora de Encerramento:</label>
                {{ $inscricao->acao->hora_encerramento }} 
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Turno:</label>
                {{ $turnoNome }} 
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento">Status:</label>
                {{ $nomeStatus }} 
            </td>
        </tr>
        <tr>
            <td>
                <label class="alinhamento"> Doação:</label>
                {{ $inscricao->acao->tipo_doacao }} 

            </td>
        </tr>
    </table>
</body>
</html>