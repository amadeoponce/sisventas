const scanner=require('sonarqube-scanner')

scanner(
    {
        serverUrl:"http://localhost:9000/",
        options:{
            "sonar.projectKey":"M-Compras",
            "sonar.projectName":"M-Compras",
            "sonar.projectVersion":"1.0.0",
            "sonar.sources":"app/Http/Controllers/compra",
            "sonar.sourceEncoding":"UTF-8"



        }

    },
    ()=>{

    }
)