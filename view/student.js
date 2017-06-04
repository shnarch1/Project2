$(".student-list .entitiy-container").click(function(event) {
	var entity_container = $(event.target).parents('.entitiy-container');
	var url = entity_container[0].dataset.url;
	$.get(url, function(data){
		$('#view').empty();
		buildShowStudent(data.student.id, data.student.name, data.student.phone, data.student.email,
			data.student.image_url, data.enrolled_courses);
	});
});

$(".student-list .btn-add").click(function(event){
			$.get("school/course", function(data){buildAddStudent(data)});
});

function buildShowStudent(id, name, phone, email, image_url, courses){

	var view = $("#view");

	var edit_button = $("<button>", {text: "Edit", 
						 "data-url": "school/student/" + id,
						 click: function(event){
						 	var url = event.target.dataset.url;
						 	$.get(url, function(data){
						 		buildEditStudent(data.student.id, url, data.student.name,
						 						 data.student.phone,data.student.email,
						 						 data.student.image_url, data.enrolled_courses,
						 						 data.all_courses);
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

    var form = $("<form>", {action: "school/student/update/" + id,
    						method:"post", enctype: "multipart/form-data"})
    						.appendTo(edit_student);

    var save_button = $("<button>", {text: "Save"});
    var delete_button = $("<button>", {text: "Delete", "data-url": url})
    				.click(function(event){
						event.preventDefault();
						var url = event.target.dataset.url;
						$.ajax({url: url,
				    			type: 'DELETE',
				    			success: function(result) {
				        			deleteStudent(id);
			        				$("#view").empty();
				    			}})
						});			


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

function buildAddStudent(all_courses){
	
	var view = $("#view");
	view.empty();

	var edit_student = $("<div>", {class: "student-edit"}).appendTo(view);

	var header = $("<header>", {text: "Add Student"})
					.appendTo(edit_student);

    var form = $("<form>", {action: "school/student", method:"post", enctype: "multipart/form-data"}).appendTo(edit_student);

    var save_button = $("<input>", {type: "submit", value: "Save"});

    var buttons = $("<div>", {class: "student-edit-btns"})
    				.append(save_button)
    				.appendTo(form);

	var input_file = $("<input>", {type: "file", 
			   name: "new_student_image",
			   accept: "image/*",
			   id:"student-new-image"})
			.change(studentImagePreview);

	var form_inputs = $("<div>", {class: "student-edit-inputs"})
					.append($("<input>", {type: "text", name: "student_name", placeholder:"Name"}))
					.append($("<input>", {type: "text", name: "student_phone", placeholder:"Phone"}))
					.append($("<input>", {type: "text", name: "student_email", placeholder:"Email"}))
					.append($(input_file))
					.appendTo(form);

	var edit_footer = $("<div>", {class: "student-edit-footer"})
					.append($("<img>"))
					.appendTo(form);

	var courses = $("<div>", {class: "student-edit-courses"})
					.appendTo(edit_footer);

	for (var i =0; i<all_courses.length; i++){
		var course_container = $("<div>", {class: "course-container"})
				.append($("<input>", {type: "checkbox", name: "courses[]", value: all_courses[i].id}))
				.append($("<label>", {text: all_courses[i].name}))
				.appendTo(courses);
	}
}

function deleteStudent(id){

	var student = findStudentElementById(id);
	$(student).remove();
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