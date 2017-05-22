$(".course-list .entitiy-container").click(function(event) {
	var entity_container = findParentNodeByClassName(event.target, "entitiy-container");
	var url = entity_container.dataset.url;
	$.get(url, function(data){
		$('#view').empty();
		buildShowCourse(data.course.id, data.course.name, data.course.image_url, data.course.description, data.students);
	});
});

$(".student-list .entitiy-container").click(function(event) {
	var entity_container = $(event.target).parents('.entitiy-container');
	var url = entity_container[0].dataset.url;
	$.get(url, function(data){
		$('#view').empty();
		buildShowStudent(data.student.id, data.student.name, data.student.phone, data.student.email,
			data.student.image_url, data.enrolled_courses);
	});
});

$(".course-list .btn-add").click(function(event){buildAddCourse()});


function findParentNodeByClassName(dom_object, class_name){
    while(!dom_object.classList.contains(class_name)){
        dom_object = dom_object.parentNode;
    }

    return dom_object;
}

function buildShowCourse(id, name, image_url, description, students_list){
	var num_of_students = students_list.length;

	var edit_button = $("<button>", {text: "Edit",
									 "data-url": "school/course/" + id,
									 click: function(event){
									 	var url = event.target.dataset.url;
									 	$.get(url, function(data){
									 		buildEditCourse(id, url, data.course.name, data.course.description,
									 			data.course.image_url, data.students.length)})
									 }});
	
	var header = $("<header>", {class: "view-header"})
								.append($("<span>").text(name))
							    .append($(edit_button))
							    .appendTo('#view');
    

	var content = $("<div>", {class: "view-top"})
								.append($("<img>", {src: image_url}))
								.appendTo('#view');

	var view_text = $("<div>", {class: "view-text"})
								.append($("<h1>").text(name +", " + num_of_students))
								.append($("<p>").text(description))
								.appendTo(content);

	var view_bottom = $("<div>", {class: "view-bottom"}).appendTo('#view');

	for (var i = 0; i < num_of_students; i++) {
		$("<div>", {class: "student-entity"})
								.append($("<img>", {src: students_list[i].image_url}))
								.append($("<span>").text(students_list[i].name))
								.appendTo(view_bottom);
	};
}

function buildEditCourse(id, url, name, description, image_url, num_of_students){
	$('#view').empty();
	
	var course_edit_container = $("<div>", {class: "course-edit"});
	
	$("<header>", {text: "Edit " + name})
								.appendTo(course_edit_container);
	
	var btn_delete = $("<button>", {text: "Delete",id:"btn-course-delete", "data-url": url}).click(function(event){
		event.preventDefault();
		var url = event.target.dataset.url;
		$.ajax({url: url,
    			type: 'DELETE',
    			success: function(result) {
        			deleteCourse(id);
    			}			
});

	});

	var btn_save = $("<input>", {type: "submit", value: "Save"});

	var course_edit_buttons = $("<div>", {class: "course-edit-btns"})
								.append(btn_save)
								.append($(btn_delete));
								// .append($("<button>", {text: "Delete",id:"btn-course-delete", "data-url": url}));

	var input_file = $("<input>", {type: "file", 
								   name: "new_course_image",
								   accept: "image/*",
								   id:"course-new-image"})
								.change(courseImagePreview);

    var course_edit_inputs = $("<div>", {class: "course-edit-inputs"})
								.append($("<input>", {type: "text",
					 							      name: "course_name",
					 							      value: name,
					 							 	  placeholder: "Course Name"}))
						    	.append($("<textarea>", {name: "course_description",
						    							 text: description,
				   								 		 placeholder: "Course Description"}))
						    	.append(input_file);
   	
   	$("<form>", {action: "school/course/update/" + id, method:"post", enctype: "multipart/form-data"})
   			   .append($(course_edit_buttons))
   			   .append($(course_edit_inputs))
   			   .appendTo($(course_edit_container));

   $("<div>", {class: "course-edit-footer"}).append($("<img>", {src: image_url}))
   											.append($("<footer>", {text: "Total " + num_of_students + " students taking this course"}))
   											.appendTo($(course_edit_container));

	$(course_edit_container).appendTo("#view");
}

function buildAddCourse(){
	$('#view').empty();
	
	var course_edit_container = $("<div>", {class: "course-edit"});
	
	$("<header>", {text: "Add new course"})
								.appendTo(course_edit_container);

	var btn_save = $("<input>", {type: "submit", value: "Save"});

	var course_edit_buttons = $("<div>", {class: "course-edit-btns"})
								.append(btn_save);

	var input_file = $("<input>", {type: "file", 
								   name: "new_course_image",
								   accept: "image/*",
								   id:"course-new-image"})
								.change(courseImagePreview);

	var course_edit_inputs = $("<div>", {class: "course-edit-inputs"})
								.append($("<input>", {type: "text",
					 							      name: "course_name",
					 							 	  placeholder: "Course Name"}))
						    	.append($("<textarea>", {name: "course_description",
				   								 		 placeholder: "Course Description"}))
						    	.append(input_file);

	$("<form>", {action: "school/course" , method:"post", enctype: "multipart/form-data"})
   			   .append($(course_edit_buttons))
   			   .append($(course_edit_inputs))
   			   .appendTo($(course_edit_container));

   	$("<div>", {class: "course-edit-footer"}).append($("<img>"))
   											 .appendTo($(course_edit_container));

   $(course_edit_container).appendTo("#view");
}

function buildShowStudent(id, name, phone, email, image_url, courses){

	var view = $("#view");

	var edit_button = $("<button>", {text: "Edit", 
									 "data-url": "school/student/" + id,
									 click: function(event){
									 	var url = event.target.dataset.url;
									 	$.get(url, function(data){
									 		buildEditStudent(data.student.id, url, data.student.name, data.student.phone,
									 			data.student.email, data.student.image_url, data.enrolled_courses, data.all_courses);
									 	});
									 }});

	var header = $("<header>", {class: "view-header"})
						.append($("<span>", {text: "Student"}))
						.append(edit_button)
						.appendTo(view);

	var view_top = $("<div>", {class:"view-top"})
						.append($("<img>", {src: image_url}))
						.appendTo(view);

	var view_text = $("<div>", {class:"view-text"})
						.append($("<h1>", {text: name}))
						.append($("<span>", {text: phone}))
						.append($("<span>", {text: email}))
						.appendTo(view_top);

	var view_bottom = $("<div>", {class:"view-bottom"})
						.appendTo(view);

	for (i = 0; i < courses.length ; i++ ){
		$("<div>", {class:"course-entity"})
							.append($("<img>", {src: courses[i].image_url}))
							.append($("<span>", {text: courses[i].name}))
							.appendTo(view_bottom);
	}
}

function buildEditStudent(id, url, name, phone, email, image_url, enrolled_courses, all_courses){
	
	var view = $("#view");
	view.empty();

	var edit_student = $("<div>", {class: "student-edit"}).appendTo(view);

	var header = $("<header>", {text: "Edit Student " + name})
					.appendTo(edit_student);

    var form = $("<form>", {action: "school/student/update/" + id, method:"post", enctype: "multipart/form-data"}).appendTo(edit_student);

    var save_button = $("<button>", {text: "Save"});
    var delete_button = $("<button>", {text: "Delete", "data-url": url}).click(function(event){
					event.preventDefault();
					var url = event.target.dataset.url;
					$.ajax({url: url,
			    			type: 'DELETE',
			    			success: function(result) {
			        			deleteStudent(id);
		        				$("#view").empty();
			    			}})});			


    var buttons = $("<div>", {class: "student-edit-btns"})
    				.append(save_button)
    				.append(delete_button)
    				.appendTo(form);

	var input_file = $("<input>", {type: "file", 
			   name: "new_student_image",
			   accept: "image/*",
			   id:"student-new-image"})
			.change(studentImagePreview);

	var form_inputs = $("<div>", {class: "student-edit-inputs"})
					.append($("<input>", {type: "text", name: "student_name", placeholder:"Name", value: name}))
					.append($("<input>", {type: "text", name: "student_phone", placeholder:"Phone", value: phone}))
					.append($("<input>", {type: "text", name: "student_email", placeholder:"Email", value: email}))
					.append($(input_file))
					.appendTo(form);

	var edit_footer = $("<div>", {class: "student-edit-footer"})
					.append($("<img>", {src: image_url}))
					.appendTo(form);

	var courses = $("<div>", {class: "student-edit-courses"})
					.appendTo(edit_footer);

	var enrolled_course_ids = [];
	for (var i =0; i<enrolled_courses.length; i++){
		enrolled_course_ids.push(enrolled_courses[i].id);
	}
	for (var i =0; i<all_courses.length; i++){
		var checked = false;
		if(enrolled_course_ids.includes(all_courses[i].id)){
			checked = true;
		}
		var course_container = $("<div>", {class: "course-container"})
				.append($("<input>", {type: "checkbox", name: "courses[]", value: all_courses[i].id, checked: checked}))
				.append($("<label>", {text: all_courses[i].name}))
				.appendTo(courses);
	}
}



function deleteCourse(id){

	var course = findCourseElementById(id);
	$(course).remove();
	$("#view").empty();

}

function deleteStudent(id){

	var student = findStudentElementById(id);
	$(student).remove();
}

function findCourseElementById(id){

	var course = null;
	$(".course-list .entitiy-container").each(function(index, el) {
		if(el.dataset.course_id == id){
			course = el;
		}		
	});
	return course;
}

function findStudentElementById(id){

	var student = null;
	$(".student-list .entitiy-container").each(function(index, el) {
		if(el.dataset.student_id == id){
			course = el;
		}		
	});
	return course;
}

function courseImagePreview(event){
	var input = event.target;
	var reader = new FileReader();

	reader.onload = function(event){
		
		var dataURL = reader.result;
		var output = $(".course-edit-footer  img");
		console.dir(output);
		output[0].src = dataURL;
	};

	reader.readAsDataURL(input.files[0]);
};

function studentImagePreview(event){
	var input = event.target;
	var reader = new FileReader();

	reader.onload = function(event){
		
		var dataURL = reader.result;
		var output = $(".student-edit-footer  img");
		output[0].src = dataURL;
	};

	reader.readAsDataURL(input.files[0]);
};

function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}



