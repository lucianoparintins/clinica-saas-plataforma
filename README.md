# Clinica SaaS Plataforma

O Clinica SaaS Plataforma é uma aplicação web para gerenciamento de clínicas médicas, permitindo o cadastro de pacientes, médicos e o agendamento de consultas. O sistema possui autenticação de usuários, gerenciamento de agenda e envio de notificações relacionadas aos agendamentos.

A aplicação segue uma arquitetura de monólito modular, organizada por domínios de negócio. O backend é desenvolvido com Laravel, enquanto o frontend utiliza Vue.js integrado ao backend através de Inertia.js. O sistema utiliza PostgreSQL para persistência de dados e Redis para cache e processamento de tarefas em segundo plano.