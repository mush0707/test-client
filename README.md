## Requirements

- docker
- minikube
- kubectl

# Installation
Note! before install this project, install <a href="https://github.com/mush0707/test-main">Main repository</a> and paste in your .env file Client credentials

``cp .env.example .env``

``change MAIN_API_CLIENT and MAIN_API_SECRET in .env``

``docker build . -f docker/nginx.Dockerfile -t frontend:dev-5``

``docker build . -f docker/fpm.Dockerfile -t backend:dev-5``

``minikube image load backend:dev-5``

``minikube image load frontend:dev-5``

``kubectl apply -f ./k8s/``

Horizontal Autoscaling configuration in ./k8s/cpu_config.yaml

<a href="http://192.168.49.2:30008/metrics">Metrics url</a>

<a href="https://spatie.be/docs/laravel-prometheus/v1/introduction">Metrics doc</a>

Metrics can be designed depends on requirements

API tests in ./tests/Feature/RewardsTest.php

API documentation <a href="http://192.168.49.2:30008/api/documentation">Swagger documentation</a>





