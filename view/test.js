$(".entitiy-container").click(function(event) {
	var entity_container = findParentNodeByClassName(event.target, "entitiy-container");
	var url = entity_container.dataset.url;
	$.get(url, function(data){
		// console.dir(data.students)
		// console.dir(data.course.name);
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
	
	var header = $("<header>", {class: "view-header"})
								.append($("<span>").text(name))
							    .append($("<button>", {text: "Edit",
							    					   "data-url": "school/course/" + id,
							    					   click: function(event){console.log("Avi")}}))
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

function buildEditCourse(){

}