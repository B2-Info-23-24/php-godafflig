```bash
#Docker ps
#Id of container 
#copy past id 
#launch composer docker 
docker-compose up -d
# display id container 
dokcer ps 
#get id 
#for init the db follow these step , connecte to your container with the id 
docker exec -it "idcontainer" /bin/bash  ou /bin/sh
# in my case exec -it "roulemapoule_web_1" /bin/bash
#then execute the file InitDB.php one time
php Modele/InitDB.php 
#DB will get the the data in the web-site
exit 
#i create one admin user for the garage 
#email : admin@fr
#password : admin
```