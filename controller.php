<?php
//go to this address to test site: http://localhost:8000/controller.php
    session_start();
    //get the modal file
    require('model.php');

    //---------------------------------------------------
    //Handles GET requests
    //---------------------------------------------------
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action == "getCards") {
            $conn = db_connect();
            $cards = getAllCards($conn);
            echo json_encode($cards);
            exit();
        }

        if ($action == "getProfile") {
            $conn = db_connect();

            //allow for viewing others profiles
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                $id = $_SESSION['id'];
            }

            $profile = getUserProfileFromID($id);
            $isFollowing = isFollowingUser($id);
            $profile['isFollowing'] = $isFollowing;

            echo json_encode($profile);
            exit();
        }

        if ($action == "getFollowingUsers") {
            $conn = db_connect();
            $usersFollowed = getAllFollowingUsers();
            echo json_encode($usersFollowed);
            exit();
        }

        if ($action == "getFollowingProjects") {
            $conn = db_connect();
            $projectsFollowed = getAllFollowingProjects();
            echo json_encode($projectsFollowed);
            exit();
        }

        if ($action == "getMyProjects") {
            $conn = db_connect();
            $myProjects = getAllCardsByUser();
            echo json_encode($myProjects);
            exit();
        }

        if ($action == "getCardDetailInfo") {
            $conn = db_connect();
            $id = $_GET['id'];

            $card = getCard($conn, $id);
            $creator = getUserfromCard($conn, $id);

            $isFollowing = isFollowingProject($id);

            //check ownership of the project
            if ($_SESSION['id'] == $card['Created_by']){
                $isOwner = true;
            } else {
                $isOwner = false;
            }

            $response = array(
                "card_info" => $card,
                "creator_info" => $creator,
                "isOwner" => $isOwner,
                "isFollowing" => $isFollowing
            );

            echo json_encode($response);
            exit();
        }

        if ($action == "Search") {
            $conn = db_connect();
            $query = $_GET['text'];
            $results = array();

            //search project cards
            $cardResults = searchCards($query);

            //search users
            $userResults = searchUsers($query);

            $results = array_merge($userResults, $cardResults);
            echo json_encode($results);
            exit();
        }
    }

    //---------------------------------------------------
    //Handles POST requests
    //---------------------------------------------------
    if (!empty($_POST['command'])){
        $command = $_POST['command'];

        if ($command == "EditProfile"){
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $target_dir = "./uploads/";
                $filename = basename($_FILES['image']['name']);
                // Use time() to ensure unique filename
                $target_file = $target_dir . time() . "_" . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $_POST['Profile_picture'] = $target_file;
                }
            }

            updateProfile($_POST);
            $updatedProfile = getUserProfileFromID($_SESSION['id']);
            echo json_encode($updatedProfile);
            exit();
        }

        if ($command == "EditProject"){
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $target_dir = "./uploads/";
                $filename = basename($_FILES['image']['name']);
                $target_file = $target_dir . time() . "_" . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Inject the path into $_POST
                    $_POST['Image_url'] = $target_file;
                }
            }

            updateProjectCard($_POST);
            $conn = db_connect();
            $updatedCard = getCard($conn, $_POST['id']);
            echo json_encode($updatedCard);
            exit();
        }

        if ($command == "AddCard"){
            //default image
            $imagePath = "Mario.png";

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $target_dir = "./uploads/";

                //Generate a unique name so users don't overwrite each other's files
                $filename = basename($_FILES['image']['name']);
                $target_file = $target_dir . time() . "_" . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $imagePath = $target_file;
                }
            }
            addProjectCard($_POST, $imagePath);
            exit();
        }

        if ($command == "DeleteCard"){
            deleteProjectCard($_POST);
            exit();
        }

        if ($command == "DeleteProfile"){
            deleteAllProjectCards();
            deleteProfile();

            session_unset();
            session_destroy();

            include('view_startpage.php');
            exit();
        }

        if ($command == "FollowUser"){
            $userToBeFollowed = $_POST['id'];
            if ($userToBeFollowed != $_SESSION['id']) {
                if (isFollowingUser($userToBeFollowed) == False) {
                    followUser($userToBeFollowed);
                }
            }
            exit();
        }

        if ($command == "UnfollowUser"){
            $userToBeUnfollowed = $_POST['id'];
            if ($userToBeUnfollowed != $_SESSION['id']) {
                if (isFollowingUser($userToBeUnfollowed) == True) {
                    unfollowUser($userToBeUnfollowed);
                }
            }
            exit();
        }

        if ($command == "FollowProject"){
            $projectToBeFollowed = $_POST['id'];
            if (isFollowingProject($projectToBeFollowed) == False){
                followProject($projectToBeFollowed);
            }
            exit();
        }

        if ($command == "UnfollowProject"){
            $projectToBeUnfollowed = $_POST['id'];
            if (isFollowingProject($projectToBeUnfollowed) == True){
                unfollowProject($projectToBeUnfollowed);
            }
            exit();
        }
    }

    //case 1
    if (empty($_POST['page'])){
        //$display_modal_window = 'none';
        include ('view_startpage.php');
        exit();
    }

    //case 2
    if ($_POST['page'] == 'StartPage') {
        $command  = $_POST['command'];
        switch ($command) {
            case 'SignIn':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $valid = isValid($username, $password);
                if (!$valid){
                    $incorrect_credentials = true;
                    $error_msg_username = 'Wrong username, or';
                    $error_msg_password = 'Wrong password';

                    //$display_modal_window = 'signin';
                    include ('view_startpage.php');
                } else {
                    $id = getUserId($username);
                    setcookie('username', $username, time() + 24 * 60 * 60);

                    $_SESSION['signedin'] = 'YES';
                    $_SESSION['username'] = $username;
                    $_SESSION['id'] = $id['Id'];

                    include ('view_mainpage.php');
                }
                break;

            case 'SignUp':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                if (userExists($username)){
                    //include a message that tells the user that the username is already in use
                    $username_used = true;
                    $error_msg_username = "Username already in use";

                    //$display_modal_window = 'signup';
                    include ('view_startpage.php');
                } else {
                    createUser($username, $password, $email);

                    $id = getUserId($username);
                    $_SESSION['id'] = $id['Id'];
                    $_SESSION['username'] = $username;

                    createProfile($username);
                    //$display_modal_window = 'signin';
                    include ('view_startpage.php');
                }
                break;

            default:
                break;
        }
        exit();
    }

    //case 3
    if ($_POST['page'] == 'MainPage') {
        //$display_modal_window = 'none';
        include ('view_startpage.php');
    }
?>