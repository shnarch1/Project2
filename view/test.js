$(".entitiy-container").click(function(event) {
	var entity_container = findParentNodeByClassName(event.target, "entitiy-container");
	var url = entity_container.dataset.url;
	$.get(url, function(data){
		// console.dir(data.students)
		// console.dir(data.course.name);
		$('#view').empty();
		buildCourse(data.course.name, data.course.image_url, data.course.description, data.students);
	});
});


function findParentNodeByClassName(dom_object, class_name){
    while(!dom_object.classList.contains(class_name)){
        dom_object = dom_object.parentNode;
    }

    return dom_object;
}

function buildCourse(name, image_url, description, students_list){
	var num_of_students = students_list.length;
	
	var header = $("<header>", {class: "view-header"}).append($("<sapan>").text(name))
													  .append($("<button>").text("Edit"))
													  .appendTo('#view');
    

	var content = $("<div>", {class: "view-top"}).append($("<img>", {src: image_url})).appendTo('#view');

	var view_text = $("<div>", {class: "view-text"}).append($("<header>").text(name +", " + num_of_students))
    												.append($("<p>").text(description))
    												.appendTo(content);

	var view_bottom = $("<div>", {class: "view-bottom"}).appendTo('#view');

	for (var i = 0; i < num_of_students; i++) {
		$("<div>", {class: "student-entity"}).append($("<img>", {src: students_list[i].image_url}))
					.append($("<span>").text(students_list[i].name))
					.appendTo(view_bottom);
	};



}
