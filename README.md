# Clinica SaaS Plataforma

O Clinica SaaS Plataforma é uma aplicação web para gerenciamento de clínicas médicas, permitindo o cadastro de pacientes, médicos e o agendamento de consultas. O sistema possui autenticação de usuários, gerenciamento de agenda e envio de notificações relacionadas aos agendamentos.

A aplicação segue uma arquitetura de monólito modular, organizada por domínios de negócio. O backend é desenvolvido com Laravel, enquanto o frontend utiliza Vue.js integrado ao backend através de Inertia.js. O sistema utiliza PostgreSQL para persistência de dados e Redis para cache e processamento de tarefas em segundo plano.

## Requisitos Funcionais
### Gestão de usuários

- [x] O sistema deve permitir registro de usuários.

- [x] O sistema deve permitir autenticação (login).

- [x] O sistema deve permitir logout do usuário autenticado.

### Gestão de pacientes

- [x] O sistema deve permitir cadastrar pacientes.

- [x] O sistema deve permitir editar informações de pacientes.

- [x] O sistema deve permitir excluir pacientes.

- [x] O sistema deve permitir listar pacientes cadastrados.

- [x] O sistema deve permitir visualizar os dados de um paciente específico.

### Gestão de médicos

O sistema deve permitir cadastrar médicos.

O sistema deve permitir editar informações de médicos.

O sistema deve permitir listar médicos cadastrados.

O sistema deve permitir visualizar dados de um médico específico.

### Agendamento de consultas

O sistema deve permitir agendar consultas entre pacientes e médicos.

O sistema deve permitir listar consultas agendadas.

O sistema deve permitir cancelar consultas.

O sistema deve permitir visualizar a agenda de um médico.

O sistema deve impedir agendamentos duplicados no mesmo horário para o mesmo médico.

### Notificações

O sistema deve registrar eventos de criação ou cancelamento de consultas.

O sistema deve permitir envio de notificações relacionadas a consultas.


## Comandos

```bash
# Iniciar o container
docker compose up -d

# Seedar o banco de dados
docker exec -it clinica-saas-plataforma-app php artisan db:seed

# Parar o container
docker compose down
```