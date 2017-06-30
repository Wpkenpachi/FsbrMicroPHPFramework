<?php 

namespace app\controller;
use core\Controller;

// To user a Eloquent Model
use app\models\Eloquent\ExEloquent;

// A Controller
class TesteController extends Controller
{
	
	// An Action
	public function exemple(){
        // Getting Get vars 
		// > $this->getParams(); // Array with all params
		// > $this->getParam('paramName'); // specifics param

		// Getting Body data
		// > $this->getBody(); Array with all Data
		// > $this->getBodyAttr('dataName'); // specifics Data

		// Rendering views, and passing variables to view
		// $this->toRender('file.php', ['varName' => $arrayOfData ], true); with render engine
		// $this->toRender('file.php', ['varNamr' => $arrayOfData ]); normally

		// Native ORM 'WPDatabase'
		// Ex:
		// $result = $this->Database->select('tableName', 'nome,email')->where('id', '>', 2)->all();
		// Calling a view and passing the result to be rendered
		// $this->toRender('file.php', ['data' => $result ]);
	}
}