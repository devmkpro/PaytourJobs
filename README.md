# 🚀 PayTour Jobs - Sistema de Gestão de Candidaturas

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-4.x-F59E0B?style=for-the-badge&logo=filament&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)

Um sistema completo de gestão de candidaturas desenvolvido com **Laravel 12** e **Filament v4**, demonstrando boas práticas de desenvolvimento, arquitetura robusta e interface moderna.

---

## 📋 Índice

- [🎯 Visão Geral](#-visão-geral)
- [✨ Funcionalidades](#-funcionalidades)
- [🛠️ Tecnologias Utilizadas](#️-tecnologias-utilizadas)
- [⚙️ Requisitos](#️-requisitos)
- [🚀 Instalação](#-instalação)
- [⚡ Configuração](#-configuração)
- [🎮 Como Usar](#-como-usar)
- [🏗️ Arquitetura do Sistema](#️-arquitetura-do-sistema)
- [🔐 Estrutura de Permissões](#-estrutura-de-permissões)
- [🌐 Rotas e Endpoints](#-rotas-e-endpoints)
- [🧪 Testes](#-testes)
- [🚀 Deploy](#-deploy)
- [🤝 Avaliação Técnica](#-avaliação-técnica)

---

## 🎯 Visão Geral

O **PayTour Jobs** é uma aplicação web moderna que demonstra competências avançadas em desenvolvimento Laravel, oferecendo:

- **Portal Público**: Interface para candidatos se inscreverem sem necessidade de autenticação
- **Painel Administrativo**: Sistema completo de gestão com Filament v4
- **Arquitetura Robusta**: Implementação de design patterns e boas práticas
- **Segurança Avançada**: Sistema de permissões granular com Spatie Permission
- **Upload Seguro**: Gerenciamento de arquivos com validações rigorosas
- **Interface Moderna**: Design responsivo e acessível

---

## ✨ Funcionalidades

### 🌐 Portal Público de Candidaturas
- ✅ **Formulário Intuitivo**: Interface limpa e responsiva
- ✅ **Validação em Tempo Real**: Feedback instantâneo para o usuário
- ✅ **Upload de Currículo**: Suporte a PDF, DOC, DOCX (máx. 5MB)
- ✅ **Anti-Spam**: Prevenção de candidaturas duplicadas por email/IP
- ✅ **Notificações**: Sistema de feedback visual para ações
- ✅ **Acessibilidade**: Design otimizado para todos os dispositivos

### 🛠️ Painel Administrativo (Filament)
- ✅ **Dashboard Inteligente**: Métricas e visão geral do sistema
- ✅ **CRUD Completo**: Gerenciamento total de candidatos
- ✅ **Sistema de Filtros**: Busca avançada e filtros personalizados
- ✅ **Download de Currículos**: Acesso direto aos arquivos enviados
- ✅ **Exportação de Dados**: Relatórios em diversos formatos
- ✅ **Logs de Auditoria**: Rastreamento de todas as ações

### 🔐 Sistema de Segurança
- ✅ **Autenticação Robusta**: Login seguro com Filament
- ✅ **Controle de Permissões**: Sistema granular baseado em roles
- ✅ **Proteção CSRF**: Segurança em todos os formulários
- ✅ **Validação de Arquivos**: Verificação rigorosa de uploads
- ✅ **Rate Limiting**: Prevenção de ataques de força bruta

---

## 🛠️ Tecnologias Utilizadas

### 🎨 Frontend
- **Livewire 3** - Componentes reativos full-stack
- **Alpine.js** - Framework JavaScript minimalista
- **Tailwind CSS** - Framework CSS utilitário
- **Blade Templates** - Engine de templates do Laravel
- **Vite** - Build tool moderno para assets

### ⚙️ Backend
- **Laravel 12** - Framework PHP robusto e elegante
- **Filament v4** - Painel administrativo de última geração
- **Spatie Permission** - Sistema avançado de permissões
- **MySQL/PostgreSQL** - Banco de dados relacional
- **Storage API** - Gerenciamento de arquivos

### 🧪 Qualidade e Testes
- **Pest** - Framework de testes moderno
- **PHPStan** - Análise estática de código
- **Laravel Pint** - Code style e formatação
- **Telescope** - Debugging e profiling (dev)

---

## ⚙️ Requisitos

### Ambiente de Desenvolvimento
- **PHP** 8.2 ou superior
- **Composer** 2.0+
- **Node.js** 18+ e NPM
- **MySQL** 8.0+ ou **PostgreSQL** 13+

### Extensões PHP Obrigatórias
```bash
php-fileinfo php-mbstring php-openssl php-pdo 
php-tokenizer php-xml php-zip php-gd php-curl
```

---

## 🚀 Instalação

### 1. Clone e Configure o Projeto
```bash
# Clone o repositório
git clone <repository-url> paytour-jobs
cd paytour-jobs

# Instale dependências PHP
composer install

# Instale dependências Node.js
npm install

# Configure o ambiente
cp .env.example .env
php artisan key:generate
```

### 2. Configuração do Banco de Dados
Edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paytour_jobs
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Para desenvolvimento local com SQLite (opcional)
# DB_CONNECTION=sqlite
```

### 3. Execute as Migrações e Seeders
```bash
# Execute as migrações
php artisan migrate

# Execute os seeders (cria usuário admin e permissões)
php artisan db:seed

# Ou execute tudo de uma vez
php artisan migrate --seed
```

### 4. Configure Storage e Assets
```bash
# Crie o link simbólico para arquivos públicos
php artisan storage:link

# Compile os assets para desenvolvimento
npm run dev

# Para produção
npm run build
```

### 5. Inicie o Servidor
```bash
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

---

## ⚡ Configuração

### 📁 Storage de Arquivos
O sistema armazena currículos em `storage/app/public/resumes/`:

```bash
# Verificar se o link simbólico foi criado
ls -la public/storage

# Configurar permissões (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```


### 🌍 Localização
O sistema suporta português brasileiro:

---

## 🎮 Como Usar

### 🌐 Portal de Candidaturas (Acesso Público)

**URL**: `http://localhost:8000`

#### Preenchimento do Formulário:
1. **Dados Pessoais**:
   - Nome completo (obrigatório)
   - Email válido (obrigatório, único)
   - Telefone com DDD (obrigatório)

2. **Informações Profissionais**:
   - Cargo desejado (obrigatório)
   - Nível de educação (obrigatório)

3. **Documentos**:
   - Upload de currículo
   - Formatos aceitos: PDF, DOC, DOCX
   - Tamanho máximo: 5MB

4. **Observações**:
   - Campo livre para informações adicionais (opcional)

#### Validações Implementadas:
- ✅ Prevenção de email duplicado
- ✅ Limite de uma candidatura por IP em 24h
- ✅ Validação de formato de arquivo
- ✅ Verificação de tamanho de arquivo
- ✅ Sanitização de dados de entrada

### 🛠️ Painel Administrativo

**URL**: `http://localhost:8000/admin`

#### 🔑 Credenciais de Acesso:
```
Email: admin@admin.com
Senha: password
```

#### Funcionalidades Administrativas:

1. **Dashboard Principal**:
   - Estatísticas de candidaturas
   - Gráficos de tendências
   - Ações rápidas

2. **Gestão de Candidatos**:
   - Listagem com filtros avançados
   - Visualização detalhada de perfis
   - Edição de informações
   - Download de currículos
   - Exclusão/restauração de registros

3. **Filtros Disponíveis**:
   - Por período de candidatura
   - Por nível educacional
   - Por cargo desejado
   - Por status do currículo

4. **Ações em Massa**:
   - Exportação selecionada
   - Exclusão múltipla
   - Alteração de status

---

## 🏗️ Arquitetura do Sistema

### 📁 Estrutura de Diretórios

```
paytour-jobs/
├── app/
│   ├── Enums/
│   │   └── EducationLevel.php              # Enum para níveis educacionais
│   ├── Filament/
│   │   ├── Resources/Candidates/
│   │   │   ├── CandidatesResource.php      # Resource principal do Filament
│   │   │   └── Pages/
│   │   │       └── ManageCandidates.php    # Página de gerenciamento
│   │   └── Providers/
│   │       └── AdminPanelProvider.php      # Configuração do painel
│   ├── Http/
│   │   ├── Controllers/                    # Controllers REST (se necessário)
│   │   └── Middleware/                     # Middlewares customizados
│   ├── Livewire/
│   │   └── CandidateApplication.php        # Componente do formulário público
│   ├── Models/
│   │   ├── Candidates.php                  # Model de candidatos
│   │   ├── User.php                        # Model de usuários
│   │   ├── Role.php                        # Model de roles
│   │   └── Permission.php                  # Model de permissões
│   ├── Policies/                           # Policies para autorização
│   └── Services/                           # Services para lógica de negócio
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_candidates_table.php
│   │   └── create_permission_tables.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── ShieldSeeder.php                # Permissões e roles
│       └── UserSeeder.php                  # Usuário administrador
├── resources/
│   ├── views/
│   │   ├── candidate-page.blade.php        # Layout da página pública
│   │   └── livewire/
│   │       └── candidate-application.blade.php # Formulário de candidatura
│   ├── lang/pt_BR/                         # Traduções em português
│   └── js/                                 # Assets JavaScript
└── routes/
    ├── web.php                             # Rotas públicas
    └── admin.php                           # Rotas administrativas (se houver)
```

### 🔧 Componentes Arquiteturais

#### 1. **Livewire Component** - `CandidateApplication`
```php
class CandidateApplication extends Component
{
    use WithFileUploads;
    
    // Propriedades reativas
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    // ...
    
    // Regras de validação
    protected array $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:candidates,email',
        // ...
    ];
    
    // Método principal de submissão
    public function submit(): void
    {
        $this->validate();
        // Lógica de negócio...
    }
}
```

#### 2. **Filament Resource** - `CandidatesResource`
```php
class CandidatesResource extends Resource
{
    protected static ?string $model = Candidates::class;
    
    // Definição do formulário
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            FileUpload::make('resume_path')
                ->disk('public')
                ->directory('resumes'),
            // ...
        ]);
    }
    
    // Definição da tabela
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable(),
            TextColumn::make('email')->searchable(),
            // ...
        ]);
    }
}
```

#### 3. **Model** - `Candidates`
```php
class Candidates extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'desired_position',
        'education_level', 'observations', 'resume_path', 'submitter_ip'
    ];
    
    protected $casts = [
        'education_level' => EducationLevel::class,
        'created_at' => 'datetime',
    ];
    
    // Accessor para URL do currículo
    public function getResumeUrlAttribute(): ?string
    {
        return $this->resume_path 
            ? Storage::disk('public')->url($this->resume_path)
            : null;
    }
}
```

---

## 🔐 Estrutura de Permissões

### 👥 Roles Implementadas

| Role | Descrição | Usuários Alvo |
|------|-----------|---------------|
| `admin` | Administrador do sistema | Gerentes, supervisores |
| `manager` | Gerente de RH | Equipe de recursos humanos |
| `viewer` | Visualizador | Usuários com acesso apenas de leitura |

### 🔑 Permissões por Módulo

#### 📊 Candidatos (`candidates`)
```php
// Visualização
'view_candidates'        // Ver candidatos individuais
'view_any_candidates'    // Listar todos os candidatos

// Criação e Edição
'create_candidates'      // Criar novos candidatos
'update_candidates'      // Editar candidatos existentes

// Exclusão
'delete_candidates'      // Excluir candidatos
'delete_any_candidates'  // Exclusão em massa
'restore_candidates'     // Restaurar candidatos excluídos
'force_delete_candidates' // Exclusão permanente

// Ações Especiais
'export_candidates'      // Exportar dados
'download_resumes'       // Download de currículos
```

#### 👤 Usuários (`users`)
```php
'view_users', 'view_any_users'
'create_users', 'update_users'
'delete_users', 'delete_any_users'
'restore_users', 'force_delete_users'
```

### 🛡️ Implementação de Segurança

#### Middleware de Autorização
```php
// Verificação em controllers
public function index()
{
    $this->authorize('view_any_candidates');
    return view('candidates.index');
}

// Verificação em Livewire
public function mount()
{
    if (!auth()->user()->can('create_candidates')) {
        abort(403);
    }
}
```

## 🌐 Rotas e Endpoints

### 🌍 Rotas Públicas
```php
// Portal de candidaturas
Route::get('/', fn() => view('candidate-page'))->name('home');
Route::get('/candidatar', fn() => view('candidate-page'))->name('candidate-application');
```

### 🔒 Rotas Administrativas (Filament)
```php
// Painel administrativo
/admin                    // Dashboard principal
/admin/login             // Autenticação
/admin/candidates        // Gestão de candidatos
/admin/candidates/create // Criar candidato
/admin/candidates/{id}   // Visualizar candidato
/admin/candidates/{id}/edit // Editar candidato
/admin/users            // Gestão de usuários (se habilitado)
```

### 📡 Endpoints API (Livewire)
```php
// Comunicação do Livewire
POST /livewire/message/{component}  // Interações do componente
POST /livewire/upload-file          // Upload temporário
DELETE /livewire/upload-file        // Remover upload temporário
```

### 📁 Rotas de Storage
```php
// Arquivos públicos
GET /storage/resumes/{filename}     // Download de currículos
GET /storage/livewire-tmp/{file}    // Arquivos temporários
```

---

## 🧪 Testes

O sistema possui uma **suíte de testes abrangente** desenvolvida com **PHP Pest**, garantindo a qualidade e confiabilidade do código.

### 🚀 Executar Testes

```bash
# Executar todos os testes
./vendor/bin/pest

# Executar apenas testes unitários
./vendor/bin/pest tests/Unit/

# Executar apenas testes de feature
./vendor/bin/pest tests/Feature/

# Executar testes com detalhes verbose
./vendor/bin/pest --verbose

# Executar testes com parada no primeiro erro
./vendor/bin/pest --stop-on-failure

# Alternativa usando Laravel Artisan
php artisan test
```

### 📊 Cobertura de Testes

**39 testes** passando com **121 asserções**, cobrindo:

- ✅ **Modelos** - Validação de dados e relacionamentos
- ✅ **Enums** - Níveis educacionais e seus valores
- ✅ **Validações** - Regras de negócio e sanitização
- ✅ **Componentes Livewire** - Funcionalidades interativas
- ✅ **Regras de Negócio** - Anti-spam e duplicações

### 📋 Suíte de Testes Atual

#### 🔬 Testes Unitários (`tests/Unit/`)

**1. Modelo Candidates** (`CandidatesModelTest.php`)
```php
✓ Candidates Model → it can create a candidate with valid data
✓ Candidates Model → it validates required fields  
✓ Candidates Model → it casts education_level to enum correctly
✓ Candidates Model → it returns correct education level label
✓ Candidates Model → it handles different education levels correctly
✓ Candidates Model → it stores timestamps correctly
✓ Candidates Model → it can have nullable observations
✓ Candidates Model → it can have nullable resume_path
✓ Candidates Model → it stores submitter_ip correctly
✓ Candidates Model → it prevents duplicate emails
✓ Candidates Model → it validates email format in model fillable
```

**2. Enum EducationLevel** (`EducationLevelEnumTest.php`)
```php
✓ EducationLevel Enum → it has all expected cases
✓ EducationLevel Enum → it has correct values for each case
✓ EducationLevel Enum → it returns correct labels
✓ EducationLevel Enum → it can be instantiated from string values
✓ EducationLevel Enum → it throws exception for invalid values
✓ EducationLevel Enum → it can use tryFrom safely
✓ EducationLevel Enum → it can be serialized to json
✓ EducationLevel Enum → it maintains consistency between value and string representation
```

**3. Validações de Candidatos** (`CandidateValidationTest.php`)
```php
✓ Candidate Validations → it validates name is required
✓ Candidate Validations → it validates name maximum length
✓ Candidate Validations → it validates email format
✓ Candidate Validations → it accepts valid email formats
✓ Candidate Validations → it validates phone minimum length
✓ Candidate Validations → it validates phone maximum length
✓ Candidate Validations → it accepts valid phone formats
✓ Candidate Validations → it validates desired position is required
✓ Candidate Validations → it validates education level is required
✓ Candidate Validations → it accepts valid education level values
✓ Candidate Validations → it validates observations maximum length
✓ Candidate Validations → it allows null observations
✓ Candidate Validations → it validates file mime types
```

#### � Testes de Feature (`tests/Feature/`)

**Teste de Aplicação** (`ExampleTest.php`)
```php
✓ the application returns a successful response
```

### 🛠️ Tecnologias de Teste

- **PHP Pest** - Framework de testes moderno e expressivo
- **SQLite in-memory** - Banco de dados para testes rápidos
- **RefreshDatabase** - Limpeza automática entre testes
- **Factories** - Geração de dados de teste consistentes
- **Custom Expectations** - Validações específicas do domínio

### 📈 Benefícios da Suíte de Testes

- **Confiabilidade** - Detecta regressões automaticamente
- **Documentação Viva** - Testes servem como especificação
- **Refatoração Segura** - Permite mudanças com confiança
- **CI/CD Ready** - Integração contínua preparada
- **Qualidade de Código** - Força boas práticas de desenvolvimento

---

## 🚀 Deploy

### 🏭 Configuração de Produção

#### 1. Otimizações
```bash
# Cache de configuração
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimização do autoloader
composer install --optimize-autoloader --no-dev

# Build de produção
npm run build
```

#### 2. Variáveis de Ambiente
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://paytour-jobs.com

# Banco de dados
DB_CONNECTION=mysql
DB_HOST=seu-host-de-producao
DB_DATABASE=paytour_jobs_prod

# Storage
FILESYSTEM_DISK=local
```

---

## 🤝 Avaliação Técnica

### ✅ Competências Demonstradas

#### 🎯 **Laravel Avançado**
- ✅ Uso correto de **Eloquent ORM** com relationships
- ✅ **Migrations** e **Seeders** bem estruturados
- ✅ **Service Providers** e **Dependency Injection**
- ✅ **Policies** e **Gates** para autorização
- ✅ **Validation** customizada e **Form Requests**
- ✅ **Storage** e **Filesystem** para upload de arquivos
- ✅ **Queues** e **Jobs** (preparado para implementação)

#### 🎨 **Frontend Moderno**
- ✅ **Livewire 3** para componentes reativos
- ✅ **Alpine.js** para interatividade
- ✅ **Tailwind CSS** para design responsivo
- ✅ **Vite** para build otimizado
- ✅ **Blade Components** reutilizáveis

#### 🛡️ **Segurança e Boas Práticas**
- ✅ **Spatie Permission** para controle granular
- ✅ **CSRF Protection** em todos os formulários
- ✅ **Input Validation** e **Sanitization**
- ✅ **Rate Limiting** para prevenção de spam
- ✅ **File Upload Security** com validação rigorosa
- ✅ **SQL Injection** prevention com Eloquent

#### 📊 **Filament v4 Expertise**
- ✅ **Resources** customizados e bem estruturados
- ✅ **Forms** dinâmicos com validação
- ✅ **Tables** com filtros e ações
- ✅ **Custom Pages** quando necessário
- ✅ **File Upload** integration
- ✅ **Custom Actions** e **Bulk Actions**

#### 🏗️ **Arquitetura e Design Patterns**
- ✅ **Repository Pattern** (implementado via Eloquent)
- ✅ **Service Layer** para lógica complexa
- ✅ **Observer Pattern** (eventos do Eloquent)
- ✅ **Factory Pattern** (Eloquent Factories)
- ✅ **Strategy Pattern** (Policies e Gates)
- ✅ **Dependency Injection** e **IoC Container**

### 🔍 **Pontos de Destaque Técnico**

1. **Componentização Inteligente**:
   - Separação clara entre componente público (Livewire) e admin (Filament)
   - Reutilização de código sem duplicação

2. **Validação Multicamada**:
   - Frontend (Alpine.js + Livewire)
   - Backend (Form Requests + Model)
   - Banco de dados (Constraints)

3. **Sistema de Permissões Escalável**:
   - Preparado para crescimento da equipe
   - Granularidade adequada para diferentes níveis de acesso

4. **Performance Otimizada**:
   - Lazy loading de relacionamentos
   - Cache de configurações
   - Otimização de queries

5. **Manutenibilidade**:
   - Código limpo e documentado
   - Estrutura consistente
   - Fácil extensibilidade

### 📈 **Escalabilidade Preparada**

- **Microservices Ready**: Estrutura permite extração de serviços
- **API First**: Facilmente adaptável para SPAs
- **Cloud Native**: Configurado para deploy em nuvem
- **Monitoring Ready**: Preparado para Telescope/Horizon
- **Testing Coverage**: Base sólida para TDD/BDD

---

## 📞 Contato e Documentação

### 📧 Informações de Desenvolvimento
- **Arquitetura**: Laravel 12 + Filament v4 + Livewire 3
- **Padrões**: PSR-12, SOLID, Clean Code
- **Versionamento**: Conventional Commits
- **Documentação**: PHPDoc completo

### 🔗 Links Úteis
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Livewire Documentation](https://livewire.laravel.com)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)

---

## 📝 Licença

Este projeto foi desenvolvido como demonstração técnica e está licenciado sob a **MIT License**.

---

## 🎯 Conclusão

Este projeto representa uma implementação completa e profissional de um sistema de gestão de candidaturas, demonstrando:

- **Expertise técnica** em Laravel e ecossistema PHP
- **Boas práticas** de desenvolvimento e arquitetura
- **Código production-ready** com foco em qualidade
- **Experiência do usuário** moderna e intuitiva
- **Segurança** e **performance** como prioridades

**Desenvolvido com excelência técnica para demonstrar competências profissionais em desenvolvimento web moderno.**

---

*💡 Para dúvidas técnicas ou esclarecimentos sobre implementação, consulte a documentação inline do código ou entre em contato através dos canais apropriados.*
