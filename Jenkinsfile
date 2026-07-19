pipeline {
    agent any

    // ¡Aquí está la solución! Le decimos a Jenkins dónde está Docker
    tools {
        dockerTool 'Dockertool'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Seguridad') {
            steps {
                // Jenkins crea el archivo config.php real durante el despliegue
                sh '''
                cat <<EOF > config.php
                <?php
                return [
                    'token' => 'u9DhyoC49W4WJp7HsNeRLzOUPELssNB_LItbQnNq3xqH-OGP8B5cdvnEd_XIzGMLnFK5VV-hveoGa9eYc5FTqwxFHDNafOgEQPOIdxGdd8l9l3kKwlOsGophjBO3P--zX03sU3e5nlQ7Wy6n5hr9w-zt64Va5VRW6TSlE74LkpsIx2iHVwcw-GxoJhFX7e5UcOdMPoRvpZisYo0luKJAXqc9RVbBkyhJ6IqmXu0q696pRKzIoNQFDWzYo7ee7uCRgU_KecGQ0XXpdr66LFzR8mrL-SYvj5H_9G4u7sd-y3bLgzv79QxR3mEy5ze8HNyvX3ciUmQ60Zmh_uUDZKiiHR92vSA',
                    'storeId' => 'b1e5b890-ed6a-4cdb-8b22-9e9f187ce4f2',
                    'confirmUrl' => 'https://paymentbox.payphonetodoesposible.com/api/confirm',
                ];
                EOF
                '''
            }
        }

        stage('Build (Construir)') {
            steps {
                sh 'docker build -t avilatech-app .'
            }
        }

        stage('Test (Pruebas)') {
            steps {
                // Pruebas de Sintaxis PHP
                sh 'docker run --rm avilatech-app php -l index.php'
                sh 'docker run --rm avilatech-app php -l respuesta.php'
                
                // Pruebas Funcionales de AvilaTech
                sh 'docker run --rm avilatech-app php tests/payphone_test.php'
            }
        }

        stage('Deploy (Desplegar)') {
            steps {
                // Detiene versiones anteriores y levanta la nueva
                sh 'docker stop avila-server || true'
                sh 'docker rm avila-server || true'
                sh 'docker run -d --name avila-server -p 8081:80 avilatech-app'
            }
        }
    }
}