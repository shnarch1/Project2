$(".entitiy-container").click(function(event) {
	var entity_container = findParentNodeByClassName(event.target, "entitiy-container");
	var url = entity_container.dataset.url;
	$.get(url, function(data){
		$('#view').empty();
		buildShowCourse(data.course.id, data.course.name, data.course.image_url, data.course.description, data.students);
	});
});


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
									 		buildEditCourse(id, url, data.course.name, data.course.description, data.course.image_url, data.students.length)})
									 	// buildEditCourse()
									 }});
	
	var header = $("<header>", {class: "view-header"})
								.append($("<span>").text(name))
							    .append($(edit_button))
							    .appendTo('#view');
    

	var content = $("<div>", {class: "view-top"})
								.append($("<img>", {src: image_url}))
								.appendTo('#view');

	var view_text = $("<div>", {class: "view-text"})
								.append($("<header>").text(name +", " + num_of_students))
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

	var course_edit_buttons = $("<div>", {class: "course-edit-btns"})
								.append($("<button>", {text: "Save", "data-url": url}))
								.append($(btn_delete));
								// .append($("<button>", {text: "Delete",id:"btn-course-delete", "data-url": url}));


    var course_edit_inputs = $("<div>", {class: "course-edit-inputs"})
								.append($("<input>", {type: "text",
					 							      name: "course_name",
					 							      value: name,
					 							 	  placeholder: "Course Name"}))
						    	.append($("<textarea>", {name: "course_description",
						    							 text: description,
				   								 		 placeholder: "Course Description"}));
   	
   	$("<form>").append($(course_edit_buttons))
   			   .append($(course_edit_inputs))
   			   .appendTo($(course_edit_container));

   $("<div>", {class: "course-edit-footer"}).append($("<img>", {src: image_url}))
   											.append($("<footer>", {text: "Total " + num_of_students + " students taging this course"}))
   											.appendTo($(course_edit_container));

	$(course_edit_container).appendTo("#view");
}

function deleteCourse(id){

	var course = findCourseElementById(id);
	$(course).remove();
	$("#view").empty();

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


