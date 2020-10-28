FROM mongo:latest

COPY inflections.csv /inflections.csv

ENTRYPOINT mongoimport --uri "mongodb://user:secret@mongo:27017/articles" --collection "inflections" --type csv --headerline --drop /inflections.csv
