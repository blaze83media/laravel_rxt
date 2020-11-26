<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Student;

class StudentController extends Controller
{
    //Functions for the application
    private $status = 200;

    public function createStudent(Request $request) {

    	//input validation
    	$validator = Validator::make($request->all(),
	    	[
	    		"activity" => "required",
	    		"time" => "required",
	    		"date" => "required"
	    	]
    	);

    	//validation failure
    	if($validator->fails()) {
    		return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
    	}
    
    	$student_id = $request->id;
    	$studentArray = array(
    		"activity" => $request->activity,
    		"time" => $request->time,
    		"date" => $request->date
    	);

    		// enter details if id exists
    	if($student_id !="") {   
    		$student = Student::find($student_id);

    		if(!is_null($student)) {
    			$updated_status = Student::where("id", $student_id)->update($studentArray);
    		
    			if($updated_status == 1) {
    				return response()->json(["status" => $this->status, "success" => true, "message" => "record updated successfully"]);
    			}
    			else {
    				return response()->json(["status" => "failed", "message" => "Whoops! failed to update record, try again." ]);
    			}
    		}

    	}

    		//create new records if not already existing
    	else {   
    		$student = Student::create($studentArray);

    		if(!is_null($student)) {
    			return response()->json(["status" => $this->status, "success" => true, "message" => "record created successfully"]);
    		}

    		else {
    			return response()->json(["status" => "failed", "message" => "Whoops! failed to create record, try again." ]);
    		}
    	}

    }//funct creatstudent end


    //Fetch all available records
    public function studentsListing() {
    	$students = Student::all();
    	if(count($students) > 0) {
    		return response() -> json(["status" => $this->status, "success" => true, "count" => count($students), "data" => $students]);
    	}
    	else {
    		return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no record found"]);
    	}
    }

    //Edit details of student (Fetch requested record)
    public function studentDetail($id) {
    	$student = Student::find($id);
    	if(!is_null($student)) {
    		return response()->json(["status" => $this->status, "success" => true, "data" => $student]);
    	}
    	else {
    		return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no record found"]);
    	}

    }

    //delete student record 
    public function studentDelete($id) {
    	$student = Student::find($id);
    	if(!is_null($student)) {
    		$delete_status = Student::where("id", $id)->delete();

    		if(delete_status == 1) {
    			return response()->json(["status" => $this->status, "success" => true, "message" => "record deleted successfully"]);
    		}

    		else {
    			return response()->json(["status" => "failed", "message" => "failed to delete"]);
    		}

    	}

    	else {
    		return response()->json(["status" => "failed", "message" => "Whoops! no record found"])
    	}
    }


}//class student ctrl end
