## Visão geral

Este repositorio tem como objetivo hospedar o projeto integrador feito no quarto modulo do curso de Analise e Desenvolvimento de Sistemas sendo cursado na data de criação do mesmo na Universidade de Sorocaba. O projeto é referente a materia de Dev Ops.

Para iniciar o site, é necessário ter o docker instalado no computador. É preferivel o uso do WSL para fazer o processo.

## Como iniciar o projeto

Siga os passos abaixo:

1 - abra o Git no seu computador(o WSL já vem com o Git pré instalado) e clone o repositorio digitando o seguinte comando:

- git clone https://github.com/NivaldoFLJr/PI.git

Feito isso, digite CD PI para acessar a pasta do projeto.

2 - Já na pasta do projeto e com o docker aberto digite o seguinte no terminal de comando: 
- docker-compose up -d

Aguarde o docker terminar de baixar a imagem, montar o container e os volumes.

3 - Caso tudo se conclua com exito, o site vai estar funcionando no link http://localhost:8081/.


## Outros

Abaixo está uma lista de outros comandos que podem vir a serem uteis:

o comando abaixo é para caso sejá necessário derrubar o projeto e inicia-lo novamente. Após a conclusão do comando, basta rodar novamente o comando do passo 2.
- docker-compose down -v

O comando abaixo serve para puxar os logs da inicialização do aplicativo. Pode ser util para caso você não tenha certeza se o banco está funcionando ou para debuggar algum erro nesta etapa.
- docker logs myfinance_db

Os comandos abaixo servem para você acessar a linha de comando do MySql rodando dentro do docker. Pode ser necessaria para adicionar ou checar manualmente alguma informação salva no banco. Senha do usuário Root: password. Para fechar a linha de comando basta digitar "exit" duas vezes.
- docker exec -it myfinance_db bash
- mysql -u root -p
