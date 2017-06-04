$(".admin-list .btn-add").click(function(event){buildAddAdmin()});

$(".admin-list .entitiy-container").click(function(event) {
	var entity_container = $(event.target).closest('.entitiy-container');
	var url = entity_container[0].dataset.url;
	$.get(url, function(data){
		$('#view').empty();
		buildEditAdmin(data.admin.id, data.admin.name, data.admin.phone, data.admin.email,
					   data.admin.role, data.admin.image_url);
	});
});



function findParentNodeByClassName(dom_object, class_name){
    while(!dom_object.classList.contains(class_name)){
        dom_object = dom_object.parentNode;
    }

    return dom_object;
}

function buildEditAdmin(id, name, phone, email, role, image_url){
	
	var view = $("#view");
	view.empty();

	var edit_admin = $("<div>", {class: "admin-edit"}).appendTo(view);

	var header = $("<header>", {text: "Edit Admin " + name})
					.appendTo(edit_admin);

    var form = $("<form>", {action: "school/admin/update/" + id, 
    						method:"post",
    						enctype: "multipart/form-data"})
    						.appendTo(edit_admin);

    var save_button = $("<button>", {text: "Save"});
    var delete_button = $("<button>", {text: "Delete", "data-url": "/school/admin/" + id}).click(function(event){
					event.preventDefault();
					var url = event.target.dataset.url;
					$.ajax({url: url,
			    			type: 'DELETE',
			    			success: function(result) {
			        			deleteAdmin(id);
		        				$("#view").empty();
			    			}})});			


    var buttons = $("<div>", {class: "admin-edit-btns"})
    				.append(save_button)
    				.append(delete_button)
    				.appendTo(form);

	var input_file = $("<input>", {type: "file", 
			   name: "new_admin_image",
			   accept: "image/*",
			   id:"admin-new-image"})
			.change(adminImagePreview);

	var role_select = $("<select>", {name: "admin_role"})
					.append($("<option>", {value: "owner", text:"Owner"}))
					.append($("<option>", {value: "manager", text:"Manager"}))
					.append($("<option>", {value: "sales", text:"Sales"}));

	var form_inputs = $("<div>", {class: "admin-edit-inputs"})
					.append($("<input>", {type: "text", name: "admin_name", placeholder:"Name", value: name}))
					.append($("<input>", {type: "text", name: "admin_phone", placeholder:"Phone", value: phone}))
					.append($("<input>", {type: "text", name: "admin_email", placeholder:"Email", value: email}))
					// .append($("<input>", {type: "text", name: "admin_role", placeholder:"Role", value: role}))
					.append(role_select)
					.append($(input_file))
					.appendTo(form);

	var select_options = $("option");
	for (var i=0; i<select_options.length; i++) {
		if(select_options[i].value == role){
			select_options[i].selected = true;
		}
		else{
			select_options[i].selected = false;
		}
	};

	var edit_footer = $("<div>", {class: "admin-edit-footer"})
					.append($("<img>", {src: image_url}))
					.appendTo(form);
}

function buildAddAdmin(){
	
	var view = $("#view");
	view.empty();

	var edit_admin = $("<div>", {class: "admin-edit"}).appendTo(view);

	var header = $("<header>", {text: "Add Admin"})
					.appendTo(edit_admin);

    var form = $("<form>", {action: "school/admin", 
    						method:"post",
    						enctype: "multipart/form-data"})
    						.appendTo(edit_admin);

    var save_button = $("<button>", {text: "Save"});		

    var buttons = $("<div>", {class: "admin-edit-btns"})
    				.append(save_button)
    				.appendTo(form);

	var input_file = $("<input>", {type: "file", 
			   name: "new_admin_image",
			   accept: "image/*",
			   id:"admin-new-image"})
			.change(adminImagePreview);

	var form_inputs = $("<div>", {class: "admin-edit-inputs"})
					.append($("<input>", {type: "text", name: "admin_name", placeholder:"Name"}))
					.append($("<input>", {type: "text", name: "admin_phone", placeholder:"Phone"}))
					.append($("<input>", {type: "text", name: "admin_email", placeholder:"Email"}))
					.append($("<input>", {type: "text", name: "admin_role", placeholder:"Role"}))
					.append($(input_file))
					.appendTo(form);

	var edit_footer = $("<div>", {class: "admin-edit-footer"})
					.append($("<img>"))
					.appendTo(form);
}


function deleteAdmin(id){

	var admin = findAdminElementById(id);
	$(admin).remove();
}

function findAdminElementById(id){

	var admin = null;
	$(".admin-list .entitiy-container").each(function(index, el) {
		if(el.dataset.admin_id == id){
			admin = el;
		}		
	});
	return admin;
}

function adminImagePreview(event){
	var input = event.target;
	var reader = new FileReader();

	reader.onload = function(event){
		
		var dataURL = reader.result;
		var output = $(".admin-edit-footer  img");
		output[0].src = dataURL;
	};

	reader.readAsDataURL(input.files[0]);
};

function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}



