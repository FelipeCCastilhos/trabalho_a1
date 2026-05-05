@php($cliente = $cliente ?? null)

{{-- Formulario parcial evita duplicacao entre cadastro e edicao de clientes. --}}
<div class="field-row">
    <div class="field">
        <label for="nome">Nome</label>
        <input id="nome" name="nome" value="{{ old('nome', $cliente->nome ?? '') }}" required>
        @error('nome') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" value="{{ old('cpf', $cliente->cpf ?? '') }}" maxlength="14" required>
        @error('cpf') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field-row">
    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $cliente->email ?? '') }}" pattern="^[^@\s]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" required>
        @error('email') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="telefone">Telefone</label>
        <input id="telefone" name="telefone" value="{{ old('telefone', $cliente->telefone ?? '') }}" required>
        @error('telefone') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field-row">
    <div class="field">
        <label for="cnh">CNH</label>
        <input id="cnh" name="cnh" value="{{ old('cnh', $cliente->cnh ?? '') }}" required>
        @error('cnh') <div class="error-text">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="data_nascimento">Data de nascimento</label>
        <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento', isset($cliente) ? $cliente->data_nascimento->format('Y-m-d') : '') }}" required>
        @error('data_nascimento') <div class="error-text">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    {{-- Hidden garante envio de "0" quando o checkbox de ativo estiver desmarcado. --}}
    <input type="hidden" name="ativo" value="0">
    <label class="checkbox-row">
        <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $cliente->ativo ?? true))>
        Cliente ativo
    </label>
    @error('ativo') <div class="error-text">{{ $message }}</div> @enderror
</div>
