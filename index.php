<!DOCTYPE html>
<html ng-app>
<head>
<script src="assets/js/async.js"></script>
<script src="assets/js/angular.min.js"></script>
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="assets/js/bootstrap.min.js"></script>
<meta charset=utf-8 />
<title>Uji Stemming</title>
<style>
</style>
<script>
function ctrl($scope, $http){
  $scope.rows = [];
  $scope.processing = false;
  $scope.currentProcess = 0;
  $scope.matchedRows = [];
  $scope.unmatchedRows = [];
  $scope.temp = false;
  $scope.test = function(){
    $scope.processing = true;
          /* $scope.rows.forEach(function(i){ */
    async.eachSeries($scope.rows, function(i, cb){
      $http.get("uji.php?input=" + i.kata)
        .success(function(data){
          console.log(data);
            i.sastrawiResult = data;
            if (i.sastrawiResult.toLowerCase().replace(" ","") == i.lema.toLowerCase().replace(" ","")) {
              i.isMatched = true;
              $scope.matchedRows.unshift(i);
            } else {
              i.isMatched = false;
              $scope.unmatchedRows.unshift(i);
            }
            $scope.currentProcess++;
            cb();
        })
        .error(function(data){
          $scope.currentProcess++;
          console.log(data);
          cb();
        })
    }, function(err){
      console.log("done");
      $scope.processing = hide;
      alert("Pengujian lema telah selesai. \n" + $scope.matchedRows.length + " kata valid\n" + $scope.unmatchedRows.length + " kata gagal distemming dengan benar.");
    })
  }
  $scope.getList = function(letter) {
    if (!letter) {
      return alert("Letter input shouldnt be empty");
    }
    $http.get("http://localhost:29017/kbbi_gerayang/reversed?query={%22awalan%22:%22" + letter + "%22}")
      .success(function(data){
        $scope.rows = data;
      })
      .error(function(data){
        console.log(data);
      })
  }

  $scope.addRow = function(){
    $scope.temp = false;
    $scope.addName="";
  };

  $scope.deleteRow = function(row){
    $scope.rows.splice($scope.rows.indexOf(row),1);
  };

  $scope.plural = function (tab){
    return tab.length > 1 ? 's': '';
  };

  $scope.addTemp = function(){
    if($scope.temp) $scope.rows.pop();
    else if($scope.addName) $scope.temp = true;

    if($scope.addName) $scope.rows.push($scope.addName);
    else $scope.temp = false;
  };

  $scope.isTemp = function(i){
    return i==$scope.rows.length-1 && $scope.temp;
  };

}
</script>
</head>
<body ng-controller="ctrl">
  <h2>{{rows.length}} Word{{plural(rows)}}. <span ng-show="processing">Processing {{currentProcess}} of {{rows.length}}</span></h2>
  <form class="form-horizontal">
    <input id="add" type="text" placeholder="Letter" ng-model="letter"/>
    <button class="btn btn-default" ng-click="getList(letter)">Fetch list</button>
    <button class="btn btn-default" ng-click="test()">Batch Test</button>
  </form>
  <h4>Unmatched word(s)</h4>
  <table  class="table table-striped">
    <tr ng-repeat="row in unmatchedRows | filter : search"  class="unmatched">
      <td>{{$index+1}}</td>
      <td>{{row.kata}}</td>
      <td>{{row.penggalan}}</td>
      <td>{{row.lema}}</td>
      <td>{{row.sastrawiResult}}</td>
      <td>{{row.isMatched}}</td>
    </tr>
  </table>
  <h4>Matched word(s)</h4>
  <table  class="table table-striped">
    <tr ng-repeat="row in matchedRows | filter : search">
      <td>{{$index+1}}</td>
      <td>{{row.kata}}</td>
      <td>{{row.penggalan}}</td>
      <td>{{row.lema}}</td>
      <td>{{row.sastrawiResult}}</td>
      <td>{{row.isMatched}}</td>
    </tr>
  </table>

</body>
</html>
