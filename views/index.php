  <!DOCTYPE html>
  <html ng-app="denoted">
    <head>
    <title>deNoted</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular.min.js"></script>
    </head>

    <body ng-controller="AppController">
	  	<nav>
		    <div class="nav-wrapper">
		      <a href="#" class="brand-logo">deNoted</a>
		    </div>
	  	</nav>

	  	<!-- Tabs -->
	    <div class="row">
		    <div class="col s12">
		      <ul class="tabs">
		        <li class="tab col m6"><a ng-click="note_active = true; todo_active=false;">Notes</a></li>
		        <li class="tab col m6"><a ng-click="note_active = false; todo_active=true;">To-Do</a></li>
		      </ul>
		    </div>
		</div>
		<!-- Content -->
	  	<div class="container">
			<!-- Notes -->
	  		<div class="row" ng-if="note_active">
	  			<h4>Notes <button class="btn waves-effect waves-light btn-small" ng-click="add_note()">Add Note</button></h4>
	  			<hr>
				<div class="col s12 m6" ng-repeat="(key,value) in notes | orderBy:'-id'">
		      		<div class="card grey lighten-4">
			        	<div class="card-content" ng-click="edit_note = key">

				        	<div ng-hide="edit_note == key">
				        		Note #{{key}} 
			          			<span class="card-title">{{value.title}}</span>
				          		<p>{{value.text}}</p>
				        	</div>

				          	<div ng-show="edit_note == key" ng-hide="edit_note != key">
				          		<div class="row">
					          		<div class="input-field">
						          		<input id="title_{{key}}" type="text" class="validate" value="{{value.title}}">
						          	</div>
						          	<div class="input-field">
	          							<textarea id="textarea_{{key}}" class="materialize-textarea" value="{{value.text}}">{{value.text}}</textarea>
	          						</div>	
				          		</div>
				          	</div>
			        	</div>
			    	    <div class="card-action">
				          <a ng-click="edit_note = key" ng-hide="edit_note == key">Edit</a>
				          <a ng-click="update_note(key); edit_note=null" ng-show="edit_note == key">Update</a>
				          <a ng-click="delete_note(key)">Delete</a>
				        </div>
		      		</div>
		    	</div>
  			</div>
  			<!-- TODO -->
  			<div class="row" ng-if="todo_active">
  				<h4>To-Do <button class="btn waves-effect waves-light btn-small" ng-click="add_task()">Add Task</button></h4>
  				<div class="input-field">
	          		<input type="text" class="validate" ng-model="search.entry" placeholder="Search Task...">
	          	</div>
				<div class="col s12 m12">
		      		<table>
				        <thead>
				          <tr>
				              <th ng-click="param_name='done';reverse=!reverse">Status <small>(click me to sort)</small></th>
				              <th ng-click="param_name='entry';reverse=!reverse">Task <small>(click me to sort)</small></th>
				              <th ng-click="param_name='date';reverse=!reverse">Date <small>(click me to sort)</small></th>
				              <th></th>
				          </tr>
				        </thead>
				        <tbody>
				          <tr ng-repeat="(key,value) in tasks | filter:search | orderBy:param_name:reverse">
				            <td>
				            	<label>
        							<input id="done_{{key}}" type="checkbox" ng-checked="value.done" ng-click="done_task(key)"/>
        							<span></span>
      							</label>
      						</td>
				            <td>
				            	<div ng-hide="edit_task == key"  ng-click="edit_task=key">
				            		{{value.entry}}
					        	</div>
					        	<div ng-show="edit_task == key">
					        		<div class="input-field">
						          		<input id="entry_{{key}}" type="text" class="validate" value="{{value.entry}}">
						          	</div>
					        	</div>
				        	</td>
				            <td>
				            	<div ng-hide="edit_task == key" ng-click="edit_task=key">
				            		{{value.date}}
					        	</div>
					        	<div ng-show="edit_task == key">
					        		<div class="input-field">
						          		<input id="date_{{key}}" type="date" class="validate" value="{{value.date}}">
						          	</div>
					        	</div>
				        	</td>
				        	<td>
				        		<a ng-click="edit_task=key" ng-hide="edit_task==key">Edit</a>
				        		<a ng-click="update_task(key);edit_task=null" ng-show="edit_task==key">Update</a>
				        		<a ng-click="delete_task(key)">Delete</a>
				        	</td>
				          </tr>
				        </tbody>
				      </table>
		    	</div>
  			</div>
	  	</div>
	  	<footer class="page-footer">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">deNoted App</h5>
                <p class="grey-text text-lighten-4">a note-taking and todo tracking app.</p>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2018 Cloudwalk Digital
            <a class="grey-text text-lighten-4 right" href="https://jncolendra.bitbucket.io" target="_blank">by John Niko Colendra (click here to know more about him)</a>
            </div>
          </div>
        </footer>
      	<!--JavaScript at end of body for optimized loading-->
      	<script type="text/javascript">
      		/*I would have included this as a separate JS file but for convenience sake I put it here*/
      		denoted = angular.module("denoted",[]);

      		denoted.controller('AppController', function($scope,$http){
      			/*Interface*/
      			$scope.note_active = true;
      			$scope.todo_active = false;
      			
      			/*Data*/
      			$http.get("api/notes").then(function(response) {
					$scope.notes = Object.keys(response.data).map(function(key) {
					return response.data[key];
					});
				});
				$http.get("api/tasks").then(function(response) {
					$scope.tasks = Object.keys(response.data).map(function(key) {
					return response.data[key];
					});
				});

      			/*Business Logic*/

      			/*Notes*/
      				$scope.add_note = function(){
      					data = {
      					"id": window.btoa(Date.now()),
	      				"title":"New Note",
	      				"text":"Edit Me!"
	      				}
      					$scope.notes.push(data);

      					$http.post("/api/notes/add",data);
      				}
	      			$scope.update_note = function(key){
	      				$scope.notes.reverse();
	      				$scope.notes[key].title = angular.element(document.querySelector('#title_'+key)).val();
	      				$scope.notes[key].text = angular.element(document.querySelector('#textarea_'+key)).val();
	      				
	      				$http.post("/api/notes/update",$scope.notes[key]);
	      			}
	      			$scope.delete_note = function(key){
	      				$scope.notes.reverse();
	      				$http.post("/api/notes/delete",$scope.notes[key]);

	      				$scope.notes.splice(key,1);
	      			}

	      		/*TODO*/
	      			$scope.add_task = function(){
	      				now = new Date();
	      				now = now.getFullYear()+"-"+('0' + (now.getMonth()+1)).slice(-2)+"-"+('0' + now.getDate()).slice(-2);
	      				data = {
	      					"id": window.btoa(Date.now()),
	      					"entry":"New Entry",
	      					"date": now,
	      					"done":false
	      				}
	      				$scope.tasks.push(data);

	      				$http.post("/api/tasks/add",data);
	      			}
	      			$scope.update_task = function(key){
	      				$scope.tasks[key].entry = angular.element(document.querySelector('#entry_'+key)).val();
	      				$scope.tasks[key].date = angular.element(document.querySelector('#date_'+key)).val();

	      				$http.post("/api/tasks/update",$scope.tasks[key]);
	      			}
	      			$scope.done_task = function(key)
	      			{
	      				$scope.tasks[key].done = angular.element(document.querySelector('#done_'+key)).prop('checked');
	      				$http.post("/api/tasks/update",$scope.tasks[key]);
	      			}
	      			$scope.delete_task = function(key){
	      				$http.post("/api/tasks/delete",$scope.tasks[key]);

	      				$scope.tasks.splice(key,1);
	      			}
      		});
      	</script>
    </body>
  </html>
