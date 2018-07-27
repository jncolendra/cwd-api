<?php 
require 'vendor/autoload.php';

/*Config(s)*/
	$storage = new Flatbase\Storage\Filesystem('storage');
	$database = new Flatbase\Flatbase($storage);

/*Functional Mapping*/
	Flight::map('get_input',function(){
		//Gets Form Data or Q?
		$data = (array)Flight::request()->data ?: (array)Flight::request()->query;
		return array_pop($data);
	});

/*Routes*/
	Flight::route('/', function(){
	  Flight::render('index.php');
	});

/*API*/
	
	Flight::route('GET /api/notes/',function() use($database){
		Flight::json($database->read()->in('notes')->get());
	});

	Flight::route('POST /api/notes/add',function() use($database){
		$database->insert()->in('notes')->set(Flight::get_input())->execute();
	});

	Flight::route('POST /api/notes/update',function() use($database){
		$database->update()->in('notes')->set(Flight::get_input())->where('id', '==', Flight::get_input()["id"])->execute();
	});

	Flight::route('POST /api/notes/delete',function() use($database){	
		$database->delete()->in('notes')->where('id', '==', Flight::get_input()["id"])->execute();
	});

	Flight::route('GET /api/tasks/',function() use($database){
		Flight::json($database->read()->in('tasks')->get());
	});

	Flight::route('POST /api/tasks/add',function() use($database){
		$database->insert()->in('tasks')->set(Flight::get_input())->execute();
	});

	Flight::route('POST /api/tasks/update',function() use($database){
		$database->update()->in('tasks')->set(Flight::get_input())->where('id', '==', Flight::get_input()["id"])->execute();
	});

	Flight::route('POST /api/tasks/delete',function() use($database){	
		$database->delete()->in('tasks')->where('id', '==', Flight::get_input()["id"])->execute();
	});

/*Orchestrate*/
Flight::start();
?>