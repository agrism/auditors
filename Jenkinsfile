pipeline {
  agent any
  environment{
    staging_server="65.21.182.7"
    BUILDVERSION=sh(script: "echo `date +%s`", returnStdout: true).trim()
  }
  stages {
    stage("Deploy to remote"){
        steps {
            sh 'ssh root@${staging_server} "mkdir -p /var/www/auditors.lv/${BUILDVERSION}"'
            sh 'scp -r ${WORKSPACE}/* root@${staging_server}:/var/www/auditors.lv/${BUILDVERSION}'

            echo "Current build version :: $BUILDVERSION"
        }
    }
    stage("run composer"){
        steps {
            sh '''
                ssh root@${staging_server} "cd /var/www/auditors.lv/${BUILDVERSION} && composer install && npm run build"
            '''
        }
    }
    stage("link new version"){
        steps {
            sh '''
                ssh root@${staging_server} "rm -rf /var/www/auditors.lv/prod"
            '''
            sh 'ssh root@${staging_server} "ln -sf /var/www/auditors.lv/${BUILDVERSION} /var/www/auditors.lv/prod"'
            echo "Current build version :: $BUILDVERSION"
        }
    }
  }
}
