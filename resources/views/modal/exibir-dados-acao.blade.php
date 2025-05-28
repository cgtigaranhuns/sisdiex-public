<style>
    .acao-modal-label {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.2rem;
    }
    .acao-modal-value {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        margin-bottom: 1rem;
        color: #34495e;
        font-size: 1.05rem;
    }
    .acao-modal .form-group {
        margin-bottom: 1.2rem;
    }
    .acao-modal .text-center {
        text-align: center;
    }
</style>

<div class="row acao-modal">
    <div class="col-md-6">
        <div class="form-group">
            <label class="acao-modal-label">Título:</label>
            <div class="acao-modal-value">{{ $acao->titulo }}</div>
        </div>
        <div class="form-group">
            <label class="acao-modal-label">Data Início:</label>
            <div class="acao-modal-value">{{ date('d/m/y', strtotime($acao->data_inicio)) }}</div>
        </div>
        <div class="form-group">
            <label class="acao-modal-label">Hora Início:</label>
            <div class="acao-modal-value">{{ date('H:i', strtotime($acao->hora_inicio)) }}</div>
        </div>
        <div class="form-group">
            <label class="acao-modal-label">Local:</label>
            <div class="acao-modal-value">{{ $acao->local }}</div>
        </div>
        <div class="form-group">
            <label class="acao-modal-label">Doação:</label>
            <div class="acao-modal-value text-center">{{ $acao->tipo_doacao }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="acao-modal-label">Data Encerramento:</label>
            <div class="acao-modal-value">{{ date('d/m/y', strtotime($acao->data_encerramento)) }}</div>
        </div>
        <div class="form-group">
            <label class="acao-modal-label">Hora Encerramento:</label>
            <div class="acao-modal-value">{{ date('H:i', strtotime($acao->hora_encerramento)) }}</div>
        </div>
        <div class="form-group">
            <label class="acao-modal-label">Requisitos:</label>
            <div class="acao-modal-value text-left">{{ $acao->requisitos }}</div>
        </div>
    </div>
</div>
