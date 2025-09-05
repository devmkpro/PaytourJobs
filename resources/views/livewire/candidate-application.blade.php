<div class="max-w-4xl mx-auto">
    <!-- Notificações -->
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" enctype="multipart/form-data" class="space-y-8">
        <!-- Informações Pessoais -->
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Pessoais</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Preencha suas informações básicas</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome Completo *
                    </label>
                    <input type="text" 
                           id="name" 
                           wire:model="name" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors"
                           placeholder="Digite seu nome completo"
                           required>
                    @error('name') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email *
                    </label>
                    <input type="email" 
                           id="email" 
                           wire:model.live="email" 
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror"
                           placeholder="seu.email@exemplo.com"
                           required>
                    @error('email') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Telefone *
                    </label>
                    <input type="tel" 
                           id="phone" 
                           wire:model.blur="phone" 
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors @error('phone') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror"
                           placeholder="(11) 99999-9999"
                           maxlength="15"
                           inputmode="numeric"
                           x-data="{ 
                               formatPhone(event) {
                                   let value = event.target.value.replace(/\D/g, '');
                                   if (value.length <= 11) {
                                       if (value.length >= 11) {
                                           value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                                       } else if (value.length >= 10) {
                                           value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                                       } else if (value.length >= 7) {
                                           value = value.replace(/(\d{2})(\d{4,5})/, '($1) $2');
                                           if (value.length > 9) {
                                               value = value.replace(/(\(\d{2}\)\s\d{4,5})(\d+)/, '$1-$2');
                                           }
                                       } else if (value.length >= 3) {
                                           value = value.replace(/(\d{2})(\d*)/, '($1) $2');
                                       } else if (value.length >= 1) {
                                           value = value.replace(/(\d{1,2})/, '($1');
                                       }
                                       event.target.value = value;
                                   } else {
                                       event.target.value = event.target.value.slice(0, -1);
                                   }
                               }
                           }"
                           x-on:input="formatPhone($event)"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode === 8 || event.charCode === 46"
                           required>
                    @error('phone') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label for="desired_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cargo Desejado *
                    </label>
                    <input type="text" 
                           id="desired_position" 
                           wire:model="desired_position" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors"
                           placeholder="Para qual cargo você está se candidatando?"
                           required>
                    @error('desired_position') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
        </div>

        <!-- Educação e Currículo -->
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Educação e Currículo</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Seu nível de educação e currículo</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="education_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nível de Escolaridade *
                    </label>
                    <select id="education_level" 
                            wire:model="education_level" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors"
                            required>
                        <option value="">Selecione seu nível de educação</option>
                        @foreach($this->educationLevels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('education_level') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label for="resume_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Currículo *
                    </label>
                    <input type="file" 
                           id="resume_path" 
                           wire:model="resume_path" 
                           accept=".pdf,.doc,.docx"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           required>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Envie arquivos PDF, DOC ou DOCX. Máximo 10MB</p>
                    @error('resume_path') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                    
                    <div wire:loading wire:target="resume_path" class="mt-2">
                        <div class="flex items-center text-blue-600">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Carregando arquivo...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Adicionais</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Compartilhe mais sobre você (opcional)</p>
            
            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Observações
                </label>
                <textarea id="observations" 
                          wire:model="observations" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors"
                          placeholder="Conte-nos mais sobre você, sua experiência ou qualquer outra informação relevante..."></textarea>
                @error('observations') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        <!-- Botão de Envio -->
        <div class="text-center pt-6">
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md"
                    wire:loading.attr="disabled">
                <span wire:loading.remove class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Enviar Candidatura
                </span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando...
                </span>
            </button>
        </div>
    </form>
</div>
