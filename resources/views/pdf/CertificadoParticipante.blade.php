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
            border-style: solid;
            border-color: grey;
            width: 100%;
            margin-top: 5%;
            font-family: courier, Arial, Helvetica, sans-serif;
        }

        .alinhamento {
            text-align-last: right;
            font-size: 12pt;
            font-weight: bold;
            line-height: 1.8;
            align-items: flex-end;

        }

        .borda1 {
            width: 100%;
            height: 2.5%;
            background-color: rgb(222, 225, 226);
            display: flex;
            align-items: center;
            text-align: center;
        }

        body {
            border: 10px solid rgb(72, 168, 93);
            padding: 10px;
        }

        .textoCentral {
            text-align: center;
            font-size: 14pt;
            font-family: courier, arial, helvetica;
            margin-top: 8%;
        }
        .assinaturas {
            margin:0!important;
            padding:0!important;
            border:0!important;
           
            }
    </style>
</head>

<body>
    <table style="width: 100%">
        <tr>
            <td><img src="{{ asset('img/logo-ifpe.png') }}" alt="Logo" width="150" height="200"></td>
            <td>
                <p style="width: 100%; font-size:46px; font-family: 'courier,arial,helvetica font-weight: bold;"
                    align="center"><b>CERTIFICADO</b></p>
        </tr>
    </table>

    <div>
        <p class="textoCentral">O Instituto Federal de Educação, Ciência e Tecnologia de Pernambuco, Campus Garanhuns,
            certifica que
            <b>{{ $nomeInscrito }}</b><br>
            CPF: <b>{{ $inscricao->cpf }}</b>
            participou do(a) Curso/Evento:<br>
            <b>{{ $inscricao->acao->titulo }}</b><br> no período de
            <b>{{ \Carbon\Carbon::parse($inscricao->acao->data_inicio)->format('d/m/Y') }}</b> a
            <b>{{ \Carbon\Carbon::parse($inscricao->acao->data_encerramento)->format('d/m/Y') }}</b>
            contabilizando carga horária de <b>{{ $inscricao->acao->carga_hr_total }}</b> horas.
            
        </p>
    </div>
   

    <table style="margin-top: 165px; width: 100%;  font-family: courier,arial,helvetica; ">
        <tr style="padding-block: 0px">
            <td style="text-align: center; " >
              <img class="assinaturas"  src="{{ asset('img/assinatura_diex.png') }}" width="250px" height="40px">
                _______________________________________<br>
                <label>HALDA SIMÕES DA SILVA</label><br>
                <label><b>Chefe da Divisão de Extensão</b></label>

            </td>
            <td style="text-align: center; ">
                <img src="{{ asset('img/assinatura_dg.png') }}" alt="Logo"  width="250px" height="40px">
                _______________________________________<br>
                <label>JOSÉ ROBERTO AMARAL NASCIMENTO</label><br>
                <label><b>Diretor-Geral Campus Garanhuns</b></label>




            </td>
        </tr>
    </table>

    <!-- PÁGINA 2 -->

    <style>
        .break {
            page-break-before: always;
        }

    </style>

    <div>

        <table style="width: 100%; height: 15%;" >
            <tr>
                <td><img src="{{ asset('img/logo-ifpe.png') }}" alt="Logo" width="150" height="200"></td>
                <td>
                    <h1 style="width: 100%; font-size:20px; font-family: 'courier,arial,helvetica font-weight: bold;"
                        align="center"><b>CONTEÚDO PROGRAMÁTICO</b>
                    </h1>
               
            </tr>
        </table>
    </div>

    <div style="padding-top: 0%">
        <table style="width: 100%; height: 300px; font-family: courier,arial,helvetica; border: 1px solid black;">
            <tr>
                <td><b>DESCRIÇÃO DA AÇÃO - Módulo/Disciplina/Assunto</b></td>
                <td><b>Carga Horária</b></td>
            </tr>
            @foreach ($ContProg as $cp)
                <tr>
                    <td>
                       {{ $cp->ementa }}
                    </td>
                    <td>
                    <div style="margin-left: 30px;">{{ $cp->carga_horaria }}</div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div style="padding-top: 10%">
        <table style=" width: 100%;  font-family: courier,arial,helvetica;  text-align: center;">
           
                <tr>
                    <td>
                        <label><b>Nota de Aprovação: </b></label>{{ $inscricao->nota }}
                    </td>
                    <td>
                        <label><b>Registro do Certificado: </b>{{ $inscricao->id }}
                    </td>
                    <td>
                        <label><b>Código de Validação: </b>{{ $inscricao->certificado_cod}}
                    </td>
                </tr>
           
        </table>
    </div>
    










</body>

</html>
