FROM swaggerapi/swagger-ui

EXPOSE 8080

COPY ./resources/swagger/openapi.yaml /code/api.yml


