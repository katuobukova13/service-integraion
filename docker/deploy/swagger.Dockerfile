FROM swaggerapi/swagger-ui

ARG SWAGGER_SERVER=/

EXPOSE 8080

COPY ./resources/swagger/openapi.yaml /code/api.yml

RUN ref=\$ref eval "echo \"$(cat /code/api.yml)\"" > /code/api.yml
