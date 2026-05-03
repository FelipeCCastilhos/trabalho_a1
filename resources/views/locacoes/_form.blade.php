@php($locacao = $locacao ?? null)

<div class="field-row">
    <div class="field">
        <label for="cliente_id">Cliente</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Selecione</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}" @selected((string) old('cliente_id', $locacao->cliente_id ?? '') === (string) $cliente->id)>
                    {{ $cliente->nome }} - {{ $cliente->cpf }}
                </option>
            @endforeach
        </select>
        @error('cliente_id') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="veiculo_id">Veiculo</label>
        <select id="veiculo_id" name="veiculo_id" required>
            <option value="">Selecione</option>
            @foreach ($veiculos as $veiculo)
                <option value="{{ $veiculo->id }}" @selected((string) old('veiculo_id', $locacao->veiculo_id ?? '') === (string) $veiculo->id)>
                    {{ $veiculo->placa }} - {{ $veiculo->marca }} {{ $veiculo->modelo }} (R$ {{ number_format((float) $veiculo->valor_diaria, 2, ',', '.') }}/dia)
                </option>
            @endforeach
        </select>
        @error('veiculo_id') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field-row">
    <div class="field">
        <label for="data_retirada">Data de retirada</label>
        <input id="data_retirada" type="date" name="data_retirada" value="{{ old('data_retirada', isset($locacao) ? $locacao->data_retirada->format('Y-m-d') : now()->toDateString()) }}" required>
        @error('data_retirada') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="data_devolucao_prevista">Devolucao prevista</label>
        <input id="data_devolucao_prevista" type="date" name="data_devolucao_prevista" value="{{ old('data_devolucao_prevista', isset($locacao) ? $locacao->data_devolucao_prevista->format('Y-m-d') : now()->addDays(1)->toDateString()) }}" required>
        @error('data_devolucao_prevista') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field-row">
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            @foreach (\App\Models\Locacao::STATUS_LABELS as $valor => $label)
                <option value="{{ $valor }}" @selected(old('status', $locacao->status ?? \App\Models\Locacao::STATUS_RESERVADA) === $valor)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="data_devolucao_real">Devolucao real</label>
        <input id="data_devolucao_real" type="date" name="data_devolucao_real" value="{{ old('data_devolucao_real', isset($locacao) && $locacao->data_devolucao_real ? $locacao->data_devolucao_real->format('Y-m-d') : '') }}">
        @error('data_devolucao_real') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="observacoes">Observacoes</label>
    <textarea id="observacoes" name="observacoes">{{ old('observacoes', $locacao->observacoes ?? '') }}</textarea>
    @error('observacoes') <div class="error-text">{{ $message }}</div> @enderror
</div>
