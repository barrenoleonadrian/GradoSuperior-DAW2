var express = require('express');
var router = express.Router();
let motos = [
  {marca: "yamaha", modelo: "pornhub", caballos: "1000"}
]

router.get('/', function(req, res, next) {
  res.status(200).json(motos);
});

module.exports = router;
