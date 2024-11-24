CREATE TABLE avaliacoes (
    id SERIAL PRIMARY KEY,
    id_setor INT NOT NULL, 
    id_pergunta INT NOT NULL, )
    id_dispositivo INT NOT NULL,
    ALTER TABLE perguntas ADD COLUMN id_setor INT; 
    resposta INT NOT NULL CHECK (resposta >= 0 AND resposta <= 10),
    feedback_textual TEXT, 
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_setor) REFERENCES setores(id),
    FOREIGN KEY (id_pergunta) REFERENCES perguntas(id), 
    FOREIGN KEY (id_dispositivo) REFERENCES dispositivos(id) 
);


CREATE TABLE dispositivos (
    id SERIAL PRIMARY KEY, 
    nome VARCHAR(255) NOT NULL,
    status BOOLEAN NOT NULL 
);


CREATE TABLE perguntas (
    id SERIAL PRIMARY KEY, 
    texto TEXT NOT NULL, 
    status BOOLEAN NOT NULL 
);


CREATE TABLE setores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL 
);


CREATE TABLE usuarios_adm (
    id SERIAL PRIMARY KEY, 
    login VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL 
);
