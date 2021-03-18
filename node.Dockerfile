FROM node:latest
MAINTAINER Filipe Monteiro

# Fora do container, Dentro do container
COPY . /var/www
WORKDIR /var/www
RUN npm install

# Mesma coisa que o CMD
#ENTRYPOINT npm install
CMD  npm install
CMD  npm run serve

EXPOSE 3000



# Comando cria uma imagem a partir de um Dockerfile.
# docker build -f node.Dockerfile

# constrói e nomeia uma imagem não-oficial informando o caminho para o Dockerfile.
#docker build -f CAMINHO_DOCKERFILE/Dockerfile -t NOME_USUARIO/NOME_IMAGEM

# Rodar imagem pelo nome com o comando
# docker run -d -p 3000:3000 image/nome


#docker login - inicia o processo de login no Docker Hub.

#docker push NOME_USUARIO/NOME_IMAGEM - envia a imagem criada para o Docker Hub.

#docker pull NOME_USUARIO/NOME_IMAGEM - baixa a imagem desejada do Docker Hub.
