<!DOCTYPE html>
<html>
    <head>
        <style>
            .clickable {
                cursor: pointer;
            }
            .hover:hover {
                background-color: #d3d3fb;
            }
            #blanket {
                position:absolute;
                top:0; left:0;
                width:100%; height:100%;
                background-color:LightGrey;
                opacity:0.5;
                z-index:998;
            }
            .project-card {
                cursor: pointer;
                height: 100%;
                border: 1px solid #dddddd;
            }
            .project-card:hover {
                background-color: #f8f9fa;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .card-img-fixed {
                width: 100%;
                height: 250px;
                object-fit: cover;
            }
            #add-drop-area, #profile-drop-area, #detailcard-drop-area {
                border: 2px dashed #cccccc;
                border-radius: 20px;
                width: 100%;
                margin: 15px 0;
                padding: 20px;
                text-align: center;
                cursor: pointer;
            }
            #add-drop-area.highlight, #profile-drop-area.highlight, #detailcard-drop-area.highlight {
                border-color: purple;
                background-color: #f0f0ff;
            }
        </style>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body class="bg-light-subtle">
        <!--==========================================-->
        <!--Header Layout-->
        <!--==========================================-->
        <div id='layout-header' class="container-fluid bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 col-sm-12">
                    <h1>Playfolio - MainPage</h1>
                </div>

                <!--search bar-->
                <div class="position-relative col-md-6 col-sm-12" style="left:-5vw">
                    <input type="text" id="search-bar" placeholder="Search users or projects..." class="form-control form-control-lg">
                    <div id="search-results" class="list-group position-absolute w-100" style="z-index:999;"></div>
                </div>
            </div>

            <!--nav bar-->
            <div id='nav-bar' class="row text-center">
                <div id="browse-button" class="col-3 border hover clickable">
                    <h3>Browse</h3>
                </div>
                <div id="myprojects-button" class="col-3 border hover clickable">
                    <h3>My Projects</h3>
                </div>
                <div id="following-button" class="col-3 border hover clickable">
                    <h3>Following</h3>
                </div>
                <div id="profile-button" class="col-3 border hover clickable">
                    <h3>Profile</h3>
                </div>
            </div>
        </div>

        <div id="blanket"></div>

        <!--==========================================-->
        <!--Profile View-->
        <!--==========================================-->
        <div id='profile-view' class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-8">
                    <div id="profile-container">
                        <img src="" id="profile-picture" style="max-width: 200px; margin-top: 10px;">
                        <h1 id="profile-name"></h1>
                        <h4>Location</h4>
                        <p id="profile-location"></p>
                        <h4>Education</h4>
                        <p id="profile-education"></p>
                        <h4>Experience</h4>
                        <p id="profile-experience"></p>
                    </div>
                </div>

                <div id='layout-left' class="col-md-3">
                    <div id="nav-buttons">
                        <form action="controller.php" method="post">
                            <input type='hidden' name='page' value='MainPage'>
                            <input type='hidden' name='command' value='SignOut'>
                            <input class='btn btn-secondary' style="width: 50%; margin-top: 10px; margin-bottom: 5px;" type="submit" value="Sign Out" id="profile-signout">
                        </form>
                        <button type="button" id="profile-edit-button" class='btn btn-secondary' style="width: 50%; margin-bottom: 5px; margin-top: 5px;">Edit</button>
                        <button type="button" id="profile-delete-button" class='btn btn-danger' style="width: 50%; margin-bottom: 5px; margin-top: 5px;">Delete</button>
                        <button type="button" id="profile-follow-button" class='btn btn-info' style="width: 50%; margin: 5px;">Follow</button>
                        <button type="button" id="profile-unfollow-button" class='btn btn-secondary' style="width: 50%; margin: 5px;">Unfollow</button>
                    </div>
                </div>
            </div>

            <!--Modal window for the edit command-->
            <div class="modal" id="profile-edit-modal" style="z-index:999; display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Edit Profile</h2>
                        </div>
                        <div class="modal-body">
                            <form id="profile-edit-form">
                                <input type="hidden" name="command" value="EditProfile">

                                <div style="margin-bottom: 15px">
                                    <label class="form-label">Profile Picture</label>
                                    <div id="profile-drop-area">
                                        <p>Drag and drop image here<br>or click to select</p>
                                    </div>
                                    <input type="file" id="profile-file" name="image" accept="image/*" style="display:none">
                                    <div id="profile-gallery"></div>
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="profile-edit-name" name="name">
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="profile-edit-location" name="location">
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="education" class="form-label">Education</label>
                                    <textarea class="form-control" id="profile-edit-education" name="education" rows="3"></textarea>
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="experience" class="form-label">Experience</label>
                                    <textarea class="form-control" id="profile-edit-experience" name="experience" rows="4"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Save Changes" class="btn btn-primary" form="profile-edit-form">
                            <input type="button" value="Cancel" id="profile-cancel-edit-button" class="cancel-button btn btn-secondary">
                        </div>
                    </div>
                </div>
            </div>

            <!--Modal window for the delete command-->
            <div class="modal" id="profile-delete-modal" style="z-index:999; display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Are you sure you would like to delete your profile?</h2>
                        </div>
                        <form id="profile-delete-form" action="controller.php" method="POST">
                            <input type="hidden" name="command" value="DeleteProfile">
                            <input type="hidden" name="id" id="profile-delete-id" value="">
                        </form>
                        <div class="modal-footer">
                            <input type="submit" value="Confirm" class="btn btn-danger" form="profile-delete-form">
                            <input type="button" value="Cancel" id="profile-cancel-delete-button" class="cancel-button btn btn-secondary">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--==========================================-->
        <!--Following View-->
        <!--==========================================-->
        <div id='following-view' class="container-fluid bg-light">
            <div id="following-navbar" class="row text-center">
                <div id="following-users-button" class="col-md-6 border hover clickable"><h4>Followed Users</h4></div>
                <div id="following-projects-button" class="col-md-6 border hover clickable"><h4>Followed Projects</h4></div>
            </div>
            <div class="row" id="following-card-container"></div>
        </div>

        <!--==========================================-->
        <!--My Projects View-->
        <!--==========================================-->
        <div id='myprojects-view' class="container-fluid bg-light">
            <div class="row">
                <button type="button" id="add-project-button" class="col-md-3 btn btn-primary" style="margin: 10px;">Add a New Project</button>
            </div>
            <div id="card-container-myprojects" class="row"></div>

            <!--Modal window for the add command-->
            <div class="modal" id="add-modal" style="z-index:999; display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Add Project</h2>
                        </div>
                        <div class="modal-body">
                            <form id="add-project-form">
                                <input type="hidden" name="command" value="AddCard">
                                <div style="margin-bottom: 15px">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="add-title" name="title" value="">
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="company" class="form-label">Company</label>
                                    <input type="text" class="form-control" id="add-company" name="company" value="">
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea type="text" class="form-control" id="add-description" name="description" value="" rows="3"></textarea>
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label class="form-label">Project Image</label>

                                    <div id="add-drop-area">
                                        <p>Drag and drop image here<br>or click to select</p>
                                    </div>
                                    <input type="file" id="add-file" name="image" accept="image/*" style="display:none">
                                    <div id="add-gallery"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Submit" form="add-project-form" class="btn btn-primary">
                            <input type="button" value="Cancel" id="cancel-button" class="cancel-button btn btn-secondary">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--==========================================-->
        <!--Browse View-->
        <!--==========================================-->
        <div id='browse-view' class="container-fluid bg-light">
            <div id="card-container-browse" class="row"></div>
        </div>

        <!--==========================================-->
        <!--Detail Cards View-->
        <!--==========================================-->
        <div id='detailcard-view' class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-8">
                    <img src="" id="detailcard_img" style="max-width: 200px; margin-top: 10px;">
                    <h1 id="detailcard_title"></h1>
                    <h4>Company</h4>
                    <p id="detailcard_company"></p>
                    <h4>Contributor</h4>
                    <p id="project_creator"></p>
                    <h4>Description</h4>
                    <p id="detailcard_description"></p>
                </div>

                <div class="col-md-3">
                    <button type="button" id="detailcard-edit-button" class='btn btn-secondary' style="width: 50%; margin: 5px;">Edit</button>
                    <button type="button" id="detailcard-delete-button" class='btn btn-danger' style="width: 50%; margin: 5px;">Delete</button>
                    <button type="button" id="detailcard-follow-button" class='btn btn-info' style="width: 50%; margin: 5px;">Follow</button>
                    <button type="button" id="detailcard-unfollow-button" class='btn btn-secondary' style="width: 50%; margin: 5px;">Unfollow</button>
                </div>
            </div>

            <!--Edit Modal-->
            <div class="modal" id="detailcard-edit-modal" style="z-index:999; display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Edit Project</h2>
                        </div>
                        <div class="modal-body">
                            <form id="detailcard-edit-form">
                                <input type="hidden" name="command" value="EditProject">
                                <input type="hidden" name="id" id="detailcard-edit-id" value="">
                                <div style="margin-bottom: 15px">
                                    <label class="form-label">Project Image</label>
                                    <div id="detailcard-drop-area">
                                        <p>Drag and drop image here<br>or click to select</p>
                                    </div>
                                    <input type="file" id="detailcard-file" name="image" accept="image/*" style="display:none">
                                    <div id="detailcard-gallery"></div>
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="detailcard-edit-title" name="title">
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="company" class="form-label">Company</label>
                                    <input type="text" class="form-control" id="detailcard-edit-company" name="company">
                                </div>
                                <div style="margin-bottom: 15px">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea type="text" class="form-control" id="detailcard-edit-description" name="description" rows="4"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Submit" form="detailcard-edit-form" class="btn btn-primary">
                            <input type="button" value="Cancel" id="detailcard-cancel-edit-button" class="cancel-button btn btn-secondary">
                        </div>
                    </div>
                </div>
            </div>

            <!--Modal window for the delete command-->
            <div class="modal" id="detailcard-delete-modal" style="z-index:999; display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Are you sure you would like to delete this project?</h2>
                        </div>
                        <form id="detailcard-delete-form">
                            <input type="hidden" name="command" value="DeleteCard">
                            <input type="hidden" name="id" id="detailcard-delete-id" value="">
                        </form>
                        <div class="modal-footer">
                            <input type="submit" value="Submit" class="btn btn-danger" form="detailcard-delete-form">
                            <input type="button" value="Cancel" id="detailcard-cancel-delete-button" class="cancel-button btn btn-secondary">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <script>
        $(document).ready(function() {
            //show browse view when the page first loads
            hideAllViews();
            $('#browse-view').show();
            loadProjectCards();

            //-----------------------------------------
            //myprojects section
            //-----------------------------------------

            $('#myprojects-button').click(function() {
                hideAllViews();
                $('#search-results').hide();
                $('#search-bar').val('');
                $('#myprojects-view').show();
                loadUsersProjects();
            });

            $('#add-project-button').click(function() {
                $('#add-modal').show();
                $('#blanket').show();
            });

            //drag + drop files upload
            $('#add-drop-area').on('dragover drop', function(e) {
                e.preventDefault();
            });

            $('#add-drop-area').on('dragover', function() {
                $(this).addClass('highlight');
            });

            $('#add-drop-area').on('drop', function() {
                $(this).removeClass('highlight');
            });

            $('#add-drop-area').on('drop', function(e) {
                let dt = e.originalEvent.dataTransfer;
                let files = dt.files;

                if (files.length > 0) {
                    $('#add-file')[0].files = files;
                    $('#add-gallery').text("Selected: " + files[0].name);
                }
            });

            $('#add-drop-area').click(function() {
                $('#add-file').trigger('click');
            });

            $('#add-file').change(function() {
                if (this.files.length > 0) {
                    $('#add-gallery').text("Selected: " + this.files[0].name);
                }
            });


            $('#add-project-form').submit(function(e) {
                e.preventDefault();

                //check if the user only entered spaces so we don't get empty entries
                let title = $('#add-title').val().trim();
                let company = $('#add-company').val().trim();

                if (title === "" || company === "") {
                    alert("Please fill out all fields.");
                    return;
                }

                $.ajax({
                    url: "controller.php",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(){
                        loadUsersProjects();

                        $('#add-modal').hide();
                        $('#blanket').hide();
                    }
                });
            })

            //-----------------------------------------
            //following section
            //-----------------------------------------

            $('#following-button').click(function() {
                hideAllViews();
                $('#search-results').hide();
                $('#search-bar').val('');
                $('#following-view').show();
                loadFollowingUserCards();
            });
            //show followed users
            $('#following-users-button').click(function() {
                loadFollowingUserCards();
            });

            //show followed projects
            $('#following-projects-button').click(function() {
                $.ajax({
                    url: "controller.php",
                    type: "GET",
                    data: {action: "getFollowingProjects"},
                    dataType: "json",
                    success: function(followingprojects) {
                        $('#following-card-container').empty();

                        if (followingprojects.length === 0) {
                            $("#following-card-container").append("<div class='col-12'><p>You are not following any projects yet.</p></div>");
                            return;
                        }

                        followingprojects.forEach(function (project) {
                            let html =`
                                        <div class="col-md-3 col-sm-6 mb-2 mt-2">
                                            <div class="card project-card view-details-card">
                                                <input type="hidden" class="hidden-id" value="${project.Id}">
                                                <img src="${project.Image_url}" class="card-img-top card-img-fixed" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">${project.Title}</h5>
                                                    <p class="card-text">${project.Company}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                            $("#following-card-container").append(html);
                        });
                    }
                });
            });

            //-----------------------------------------
            //profile section
            //-----------------------------------------
            //show profile view
            $('#profile-button').click(function() {
                hideAllViews();
                $('#profile-view').show();

                //wipe the text immediately so you don't see the previous user
                $('#profile-name').text("Loading...");
                $('#profile-location').text("");
                $('#profile-education').text("");
                $('#profile-experience').text("");
                $('#profile-picture').attr('src', '');

                $('#profile-unfollow-button').hide();
                $('#profile-follow-button').hide();
                $('#search-results').hide();
                $('#search-bar').val('');
                $('#profile-edit-button').show();
                $('#profile-delete-button').show();
                $('#profile-signout').show();

                //show your profile
                $.ajax({
                    url: "controller.php",
                    type: "GET",
                    data: {action: "getProfile"},
                    dataType: 'json',
                    success: function(profiledata) {
                        $('#profile-picture').attr('src', profiledata.Profile_picture);
                        $('#profile-name').text(profiledata.Name);
                        $('#profile-location').text(profiledata.Location);
                        $('#profile-education').text(profiledata.Education);
                        $('#profile-experience').text(profiledata.Experience);

                        //make the values in the edit field the default values from server
                        $('#profile-edit-name').val(profiledata.Name);
                        $('#profile-edit-location').val(profiledata.Location);
                        $('#profile-edit-education').val(profiledata.Education);
                        $('#profile-edit-experience').val(profiledata.Experience);
                    }
                });
            });

            $('#profile-edit-button').click(function() {
                $('#profile-edit-modal').show();
                $('#blanket').show();
                $('#profile-gallery').text('');
            });

            $('#profile-drop-area').on('dragover drop', function(e) {
                e.preventDefault();
            });

            $('#profile-drop-area').on('dragover', function() {
                $(this).addClass('highlight');
            });

            $('#profile-drop-area').on('drop', function() {
                $(this).removeClass('highlight');
            });

            $('#profile-drop-area').on('drop', function(e) {
                let dt = e.originalEvent.dataTransfer;
                let files = dt.files;

                if (files.length > 0) {
                    $('#profile-file')[0].files = files;
                    $('#profile-gallery').text("Selected: " + files[0].name);
                }
            });

            $('#profile-drop-area').click(function() {
                $('#profile-file').trigger('click');
            });

            $('#profile-file').change(function() {
                if (this.files.length > 0) {
                    $('#profile-gallery').text("Selected: " + this.files[0].name);
                }
            });

            //edit the users profile
            $('#profile-edit-form').submit(function(e) {
                e.preventDefault();

                //check if the user entered nothing or only spaces
                let name = $('#profile-edit-name').val().trim();

                if (name === "") {
                    alert("Must fill in your name.");
                    return;
                }

                $.ajax({
                    url: "controller.php",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(profiledata) {
                        $('#profile-name').text($("#profile-edit-name").val());
                        $('#profile-location').text($('#profile-edit-location').val());
                        $('#profile-education').text($('#profile-edit-education').val());
                        $('#profile-experience').text($('#profile-edit-experience').val());

                        //update profile picture
                        $('#profile-picture').attr('src', profiledata.Profile_picture);

                        //make the values in the edit field the default values from server
                        $('#profile-edit-name').attr('value', profiledata.Name);
                        $('#profile-edit-location').attr('value', profiledata.Location);
                        $('#profile-edit-education').attr('value', profiledata.Education);
                        $('#profile-edit-experience').attr('value', profiledata.Experience);

                        $('#profile-edit-modal').hide();
                        $('#blanket').hide();
                    }
                });
            });

            //delete function for profile
            $('#profile-delete-button').click(function() {
                $('#blanket').show();
                $('#profile-delete-modal').show();
            });

            //-----------------------------------------
            //detail card section
            //-----------------------------------------
            //show detailed card/project view
            $(document).on("click", ".view-details-card", function () {
                //find the id of the element that was clicked
                let projectId = $(this).find(".hidden-id").val();
                hideAllViews();
                $('#search-results').hide();
                $('#search-bar').val('');
                $('#detailcard-view').show();

                $.ajax({
                    url: "controller.php",
                    type: "GET",
                    data: {action: "getCardDetailInfo", id: projectId},
                    dataType: 'json',
                    success: function(projectdetails) {
                        $('#detailcard_img').attr('src', projectdetails.card_info.Image_url);
                        $('#detailcard_title').text(projectdetails.card_info.Title);
                        $('#detailcard_description').text(projectdetails.card_info.Description);
                        $('#detailcard_company').text(projectdetails.card_info.Company);

                        $('#project_creator').text(projectdetails.creator_info.Name);

                        //make the edit form have the defaults
                        $('#detailcard-edit-id').val(projectdetails.card_info.Id);
                        $('#detailcard-edit-title').val(projectdetails.card_info.Title);
                        $('#detailcard-edit-description').val(projectdetails.card_info.Description);
                        $('#detailcard-edit-company').val(projectdetails.card_info.Company);

                        //make the delete form have its hidden id filled with the correct value
                        $('#detailcard-delete-id').attr('value', projectdetails.card_info.Id);

                        //check if the user is the owner of the project
                        if (projectdetails.isOwner === true) {
                            $('#detailcard-edit-button').show();
                            $('#detailcard-delete-button').show();
                            $('#detailcard-unfollow-button').hide();
                            $('#detailcard-follow-button').hide();
                        } else {
                            $('#detailcard-edit-button').hide();
                            $('#detailcard-delete-button').hide();

                            //checks if you are following the project
                            if (projectdetails.isFollowing === true) {
                                $('#detailcard-unfollow-button').show();
                                $('#detailcard-follow-button').hide();
                            } else {
                                $('#detailcard-unfollow-button').hide();
                                $('#detailcard-follow-button').show();
                            }
                        }
                    }
                });
            });

            //follow feature functionality
            $('#detailcard-unfollow-button').click(function() {
                let currentId = $('#detailcard-edit-id').val();
                $.post("controller.php", {command: "UnfollowProject", id: currentId}, function() {
                    $('#detailcard-unfollow-button').hide();
                    $('#detailcard-follow-button').show();
                });
            });

            $('#detailcard-follow-button').click(function() {
                let currentId = $('#detailcard-edit-id').val();
                $.post("controller.php", {command: "FollowProject", id: currentId}, function() {
                    $('#detailcard-unfollow-button').show();
                    $('#detailcard-follow-button').hide();
                });
            });

            //edit feature functionality
            $('#detailcard-edit-button').click(function() {
                $('#detailcard-edit-modal').show();
                $('#blanket').show();
            });

            $('#detailcard-drop-area').on('dragover drop', function(e) {
                e.preventDefault();
            });

            $('#detailcard-drop-area').on('dragover', function() {
                $(this).addClass('highlight');
            });

            $('#detailcard-drop-area').on('drop', function() {
                $(this).removeClass('highlight');
            });

            $('#detailcard-drop-area').on('drop', function(e) {
                let dt = e.originalEvent.dataTransfer;
                let files = dt.files;
                if (files.length > 0) {
                    $('#detailcard-file')[0].files = files;
                    $('#detailcard-gallery').text("Selected: " + files[0].name);
                }
            });

            $('#detailcard-drop-area').click(function() {
                $('#detailcard-file').trigger('click');
            });

            $('#detailcard-file').change(function() {
                if (this.files.length > 0) {
                    $('#detailcard-gallery').text("Selected: " + this.files[0].name);
                }
            });

            $('#detailcard-edit-form').submit(function(e) {
                e.preventDefault();

                //checks for empty entered values
                let title = $('#detailcard-edit-title').val().trim();
                let company = $('#detailcard-edit-company').val().trim();

                if (title === '' || company === '') {
                    alert('Title and company cannot be blank');
                    return;
                }

                $.ajax({
                    url: "controller.php",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(projectdata) {
                        $('#detailcard_title').text($('#detailcard-edit-title').val());
                        $('#detailcard_description').text($('#detailcard-edit-description').val());
                        $('#detailcard_company').text($('#detailcard-edit-company').val());

                        $('#detailcard_img').attr('src', projectdata.Image_url);

                        $('#detailcard-edit-title').val(projectdata.Title);
                        $('#detailcard-edit-description').val(projectdata.Description);
                        $('#detailcard-edit-company').val(projectdata.Company);

                        $('#detailcard-edit-modal').hide();
                        $('#blanket').hide();

                        // Cleanup the file input text
                        $('#detailcard-gallery').text('');
                        $('#detailcard-file').val('');
                    }
                });
            });

            $('#detailcard-delete-button').click(function() {
                $('#detailcard-delete-modal').show();
                $('#blanket').show();
            });

            $('#detailcard-delete-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "controller.php",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function() {
                        hideAllViews();
                        $('#blanket').hide();
                        $('#detailcard-delete-modal').hide();

                        $('#browse-view').show();
                        loadProjectCards();
                    }
                });
            });

            //-----------------------------------------
            //view users profile section
            //-----------------------------------------
            $(document).on("click", ".view-user-profile", function () {
                let userId = $(this).find(".hidden-id").val();

                $('#search-results').hide();
                $('#search-bar').val('');
                hideAllViews();
                $('#profile-view').show();

                $.ajax({
                    url: "controller.php",
                    type: "GET",
                    data: {action: "getProfile", id: userId},
                    dataType: 'json',
                    success: function(profiledata) {
                        $('#profile-picture').attr('src', profiledata.Profile_picture);
                        $('#profile-name').text(profiledata.Name);
                        $('#profile-location').text(profiledata.Location);
                        $('#profile-education').text(profiledata.Education);
                        $('#profile-experience').text(profiledata.Experience);

                        $('#profile-edit-button').hide();
                        $('#profile-delete-button').hide();
                        $('#profile-signout').hide();

                        if (profiledata.isFollowing === true) {
                            $('#profile-follow-button').hide();
                            $('#profile-unfollow-button').show();
                        } else {
                            $('#profile-follow-button').show();
                            $('#profile-unfollow-button').hide();
                        }
                    }
                });

                //follow function for profile
                $('#profile-follow-button').click(function() {
                    $.post("controller.php", {command: "FollowUser", id: userId}, function() {
                        $('#profile-follow-button').hide();
                        $('#profile-unfollow-button').show();
                    });
                });

                $('#profile-unfollow-button').click(function() {
                    $.post("controller.php", {command: "UnfollowUser", id: userId}, function() {
                        $('#profile-unfollow-button').hide();
                        $('#profile-follow-button').show();
                    });
                });
            });

            //-----------------------------------------
            //search bar functionality
            //-----------------------------------------
            $('#search-bar').on('keyup', function(){
                let text = $(this).val();
                if (text.length < 1) {
                    $('#search-results').hide();
                    return;
                }

                $.ajax({
                    url: "controller.php",
                    type: "GET",
                    data: {action: "Search", text: text},
                    dataType: "json",
                    success: function(searchResult){
                        $('#search-results').empty();

                        if (searchResult.length === 0) {
                            $('#search-results').hide();
                            return;
                        }

                        searchResult.forEach(function(result) {
                            let clickClass = "";
                            if (result.type === "Project") {
                                clickClass = "view-details-card";
                            } else {
                                clickClass = "view-user-profile";
                            }

                            let html = `
                                <div  class="list-group-item list-group-item-action ${clickClass}">
                                    <input type="hidden" class="hidden-id" value="${result.Id}">
                                    <strong>${result.label}</strong>
                                    <small>${result.type}</small>
                                </div>
                            `;
                            $('#search-results').append(html);
                        });
                        $('#search-results').show();
                        $('#blanket').css('background-color', 'transparent').show();
                    }
                });
            });

            //-----------------------------------------
            //browse section
            //-----------------------------------------
            $('#browse-button').click(function() {
                hideAllViews();
                $('#search-results').hide();
                $('#search-bar').val('');
                $('#browse-view').show();

                //show all cards in browse view
                loadProjectCards();
            });

            //if user clicks on blanket hide all modals
            $('#blanket').click(function() {
                $('#profile-edit-modal').hide();
                $('#detailcard-edit-modal').hide();
                $('#add-modal').hide();
                $('#profile-delete-modal').hide();
                $('#detailcard-delete-modal').hide();
                $('#search-results').hide();

                $('#blanket').hide().css('background-color', 'LightGrey');
            });

            //cancel button logic
            $('.cancel-button').click(function() {
                $('#profile-edit-modal').hide();
                $('#blanket').hide();
                $('#detailcard-edit-modal').hide();
                $('#add-modal').hide();
                $('#profile-delete-modal').hide();
                $('#detailcard-delete-modal').hide();
            });
        });

        //helper functions
        //hides all the views so then we can display only one
        function hideAllViews(){
            $('#following-view').hide();
            $('#myprojects-view').hide();
            $('#browse-view').hide();
            $('#detailcard-view').hide();
            $('#profile-view').hide();
            $('#blanket').hide();
        }

        //loads all project cards using ajax
        function loadProjectCards(){
            $.ajax({
                url: "controller.php",
                type: "GET",
                data: {action: "getCards"},
                dataType: "json",
                success: function(projectcards) {
                    $('#card-container-browse').empty();

                    projectcards.forEach(function(card) {
                        let html =`
                                <div class="col-md-3 col-sm-6 mb-2 mt-2">
                                    <div class="card view-details-card project-card">
                                        <input type="hidden" class="hidden-id" value="${card.Id}">
                                        <img src="${card.Image_url}" class="card-img-top card-img-fixed" alt="Project Image"></img>
                                        <div class="card-body">
                                            <h5 class="card-title">${card.Title}</h5>
                                            <p class="card-text">${card.Company}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        $("#card-container-browse").append(html);
                    });
                }
            });
        }

        //load all projects that the user is following
        function loadFollowingUserCards(){
            $.ajax({
                url: "controller.php",
                type: "GET",
                data: {action: "getFollowingUsers"},
                dataType: "json",
                success: function(followingusers) {
                    $('#following-card-container').empty();

                    if (followingusers.length === 0) {
                        $("#following-card-container").append("<div class='col-12'><p>You are not following anyone yet.</p></div>");
                        return;
                    }

                    followingusers.forEach(function (user) {
                        let html =`
                                        <div class="col-md-3 col-sm-6 mb-2 mt-2">
                                            <div class="card project-card view-user-profile">
                                                <input type="hidden" class="hidden-id" value="${user.UserId}">
                                                <img src="${user.Profile_picture}" class="card-img-top card-img-fixed" alt="User Profile Picture"></img>
                                                <div class="card-body">
                                                    <h5 class="card-title">${user.Name}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                        $("#following-card-container").append(html);
                    });
                }
            });
        }

        //loads all the users projects
        function loadUsersProjects(){
            $.ajax({
                url: "controller.php",
                type: "GET",
                data: {action: "getMyProjects"},
                dataType: 'json',
                success: function(projectcards) {
                    $('#card-container-myprojects').empty();

                    if (projectcards.length === 0) {
                        $("#card-container-myprojects").append("<div class='col-12'><p>You have not made any projects yet.</p></div>");
                        return;
                    }

                    projectcards.forEach(function (project) {
                        let html =`
                                        <div class="col-md-3 col-sm-6 mb-2 mt-2">
                                            <div class="card project-card view-details-card">
                                                <input type="hidden" class="hidden-id" value="${project.Id}">
                                                <img src="${project.Image_url}" class="card-img-top card-img-fixed" alt="Project Image">
                                                <div class="card-body">
                                                    <h5 class="card-title">${project.Title}</h5>
                                                    <p class="card-text">${project.Company}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                        $("#card-container-myprojects").append(html);
                    });
                }
            });
        }
    </script>
</html>
