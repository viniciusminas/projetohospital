# Sistema de Avaliação de Serviços Hospitalares

Projeto acadêmico de um sistema de avaliação de serviços hospitalares desenvolvido para o **Hospital Regional Alto Vale (HRAV)**. O sistema permite que usuários avaliem a qualidade do atendimento por meio de perguntas e notas, com as respostas sendo enviadas e processadas no servidor.

## Funcionalidades

- Carregamento dinâmico de perguntas a partir de um banco de dados.
- Interface de avaliação onde os usuários selecionam uma nota de 0 a 10 para cada pergunta.
- Timer para controle de tempo de resposta de cada pergunta.
- Feedback final do usuário, com campo adicional para comentários.
- Integração com Bootstrap para um design responsivo e moderno.
- Sistema de carregamento com spinner para melhorar a experiência do usuário enquanto as perguntas são carregadas.

## Tecnologias Utilizadas

- **Frontend**: HTML, CSS, JavaScript (com uso de Bootstrap para estilização e maior responsividade)
- **Backend**: PHP para a comunicação com o banco de dados
- **Banco de Dados**: POSTGRESQL
- **Ferramentas**: XAMPP para ambiente de desenvolvimento local

## Estrutura do Projeto

- `index.php`: Página principal da aplicação, onde o usuário realiza a avaliação.
- `js/script.js`: Arquivo JavaScript que gerencia a lógica do frontend, como carregamento de perguntas, controle do timer e navegação entre perguntas.
- `src/perguntas.php`: Endpoint responsável por enviar as perguntas do banco de dados para o frontend em formato JSON.
- `css/styles.css`: Arquivo de estilos CSS personalizado.
