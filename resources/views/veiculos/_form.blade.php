@php($veiculo = $veiculo ?? null)

{{-- Formulario parcial compartilhado por cadastro e edicao de veiculos. --}}
<div class="field-row">
    <div class="field">
        <label for="marca">Marca</label>
        <input id="marca" name="marca" value="{{ old('marca', $veiculo->marca ?? '') }}" required>
        @error('marca') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="modelo">Modelo</label>
        <input id="modelo" name="modelo" value="{{ old('modelo', $veiculo->modelo ?? '') }}" required>
        @error('modelo') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field-row">
    <div class="field">
        <label for="ano">Ano</label>
        <input id="ano" type="number" name="ano" min="1990" max="{{ now()->addYear()->year }}" value="{{ old('ano', $veiculo->ano ?? '') }}" required>
        @error('ano') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="placa">Placa</label>
        <input id="placa" name="placa" value="{{ old('placa', $veiculo->placa ?? '') }}" maxlength="8" required>
        @error('placa') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field-row">
    <div class="field">
        <label for="categoria">Categoria</label>
        <select id="categoria" name="categoria" required>
            @foreach (\App\Models\Veiculo::CATEGORIAS as $valor => $label)
                <option value="{{ $valor }}" @selected(old('categoria', $veiculo->categoria ?? 'economico') === $valor)>{{ $label }}</option>
            @endforeach
        </select>
        @error('categoria') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="valor_diaria">Valor da diaria</label>
        <input id="valor_diaria" type="number" step="0.01" min="80" name="valor_diaria" value="{{ old('valor_diaria', $veiculo->valor_diaria ?? '') }}" required>
        @error('valor_diaria') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="status">Status</label>
    <select id="status" name="status" required>
        @foreach (\App\Models\Veiculo::STATUS_LABELS as $valor => $label)
            <option value="{{ $valor }}" @selected(old('status', $veiculo->status ?? \App\Models\Veiculo::STATUS_DISPONIVEL) === $valor)>{{ $label }}</option>
        @endforeach
    </select>
    @error('status') <div class="error-text">{{ $message }}</div> @enderror
</div>
