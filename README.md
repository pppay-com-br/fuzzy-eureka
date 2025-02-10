## Desafio Proposto

O objetivo deste teste é criar um algoritmo que atenda aos seguintes requisitos:

### Requisitos do Algoritmo

1. **Envio de Requisições:**

   - Crie um endpoint HTTP que receba uma requisição POST com os seguintes campos:
     - `GitHub username` (string)
     - `Commit hash` (string)
     - `Id pk` (uuid)

2. **Persistência dos Dados:**

   - Os dados recebidos devem ser salvos em uma tabela de banco de dados PostgreSQL.

3. **Desempenho:**

   - O endpoint deve ser capaz de suportar **6 mil de requisições** em um intervalo de **1 minuto**.

4. **Linguagens aceitas:**

   - Python
   - PHP
   - Go
   - Shell/Bash

5. **Não obrigatório:**

   - Endpoint que retorna a lista de requisições por `GitHub username`

### Instruções

1. **Fork do Repositório:**

   - Crie um fork deste repositório em sua conta do GitHub.

2. **Implementação:**

   - Desenvolva sua solução no fork criado.
   - Certifique-se de documentar bem seu código para que seja fácil de entender.

3. **Validação de Performance:**

   - Inclua testes para validar que o endpoint atende à exigência de performance (6K de requisições/min).

4. **Submissão:**

   - Ao concluir, abra um Pull Request neste repositório com sua solução.

## Critérios de Avaliação

Serão considerados os seguintes pontos:

1. **Eficiência e Escalabilidade:**

   - A solução atende ao requisito de 6K de requisições por minuto?

2. **Clareza e Organização:**

   - O código é bem estruturado, modular e fácil de entender?

3. **Documentação:**

   - O README e o próprio código estão bem documentados?


## Especificação da Tabela PostgreSQL

```sql
CREATE TABLE requests (
    id UUID NOT NULL PRIMARY KEY,
    github_username VARCHAR(60) NOT NULL,
    commit_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_github_username ON requests (github_username);
CREATE INDEX idx_commit_hash ON requests (commit_hash);
```
