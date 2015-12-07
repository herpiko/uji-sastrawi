var mongodbRest = require('mongodb-rest/server.js');
var config = { 
  "db": "mongodb://localhost:27017",
  "server": {
    "port": 29017,
    "address": "0.0.0.0"
  },
  "accessControl": {
    "allowOrigin": "*",
    "allowMethods": "GET,POST,PUT,DELETE,HEAD,OPTIONS",
    "allowCredentials": false
  },
    "mongoOptions": {
        "serverOptions": {
        },
        "dbOptions": {
            "w": 1
        }
    },
  "humanReadableOutput": true,
  "urlPrefix": ""
}
mongodbRest.startServer(config);
console.log("MongoDB Rest API running on " + config.server.address + ":" + config.server.port);
