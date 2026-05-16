<?php
/*
*   User management
*   use this to start sql server for testing
*   Press Windows + R, type services.msc, hit Enter, Find “MySQL95”
*
*   Once finished and ready to hand in there are a few steps that need to be done first
*   1. change the connection to the sql database
*   2. create new tables within the cs.tru.ca sql database to work with this program
*       a. userstable - for tracking the users of the site
*       b.
*/
    //use this when handing in project
    //$conn = mysqli_connect('localhost', 'f3lhendry', 'f3lhendry136', 'C354_f3lhendry');
    //use this for testing
    //$conn = mysqli_connect('localhost', 'root', 'f3lhendry136', 'termproject');

    //global connection function
    function db_connect(){
        $conn = mysqli_connect('localhost', 'root', 'f3lhendry136', 'termproject');
        if (mysqli_connect_errno())
            echo "Failed to connect to C354_f3lhendry: " . mysqli_connect_error();
        //else
        //echo "Connected to C354_f3lhendry";
        return $conn;
    }

    //---------------------------------------------------
    //Methods for userstable
    //---------------------------------------------------
    //function to check if a username is already registered to a user
    function userExists($username){
        $conn = db_connect();
        $sql = "SELECT username FROM userstable WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
            return true;
        else
            return false;
    }
    //function to create a user in the table using provided information
    function createUser($username, $password, $email){
        $conn = db_connect();
        $sql = "INSERT INTO userstable (username, password, email) VALUES ('$username', '$password', '$email')";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //function to check the validity of a username and password
    function isValid($username, $password){
        $conn = db_connect();
        $sql = "SELECT * FROM userstable WHERE username='$username' and password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0)
            return true;
        else
            return false;
    }

    //function get user ID
    function getUserId($username){
        $conn = db_connect();
        $sql = "SELECT Id FROM userstable WHERE username='$username'";
        $result = mysqli_query($conn, $sql);

        $userId = mysqli_fetch_assoc($result);
        return $userId;
    }

    //---------------------------------------------------
    //Methods for userprofiles table
    //---------------------------------------------------
    //search users
    function searchUsers($query){
        $conn = db_connect();
        $sql = "SELECT UserId, Name FROM userprofiles WHERE Name LIKE '%$query%' LIMIT 10";
        $result = mysqli_query($conn, $sql);

        $users = [];
        while($row = mysqli_fetch_assoc($result)){
            $users[] = [
                "label" => $row['Name'],
                "Id" => $row['UserId'],
                "type" => "User",
            ];
        }
        return $users;
    }

    //get the users profile
    function getUserProfileFromID($id){
        $conn = db_connect();
        $sql = "SELECT * FROM userprofiles WHERE UserId = '$id'";
        $result = mysqli_query($conn, $sql);

        $profile = mysqli_fetch_assoc($result);
        return $profile;
    }

    //update the users profile when they have edited the profile
    function updateProfile($profile){
        $conn = db_connect();

        //break up query to check the url of image to see if updated
        $sql = "UPDATE userprofiles SET ";

        if (isset($profile['Profile_picture'])) {
            $sql .= "Profile_picture = '{$profile['Profile_picture']}', ";
        }

        $sql .= "Name = '{$profile['name']}',
                    Location = '{$profile['location']}',
                    Education = '{$profile['education']}',
                    Experience = '{$profile['experience']}'
                WHERE UserId = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //create a new user profile, When sign in is clicked
    function createProfile($username){
        $conn = db_connect();
        $sql = "INSERT INTO userprofiles (UserId, Profile_picture, Name, Location, Education, Experience)
                VALUES ('{$_SESSION['id']}', 'Mario.png', '$username', '', '', '')";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //delete users profile from the database
    function deleteProfile(){
        $conn = db_connect();
        $sql = "DELETE FROM userprofiles WHERE UserId = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);

        $sql = "DELETE FROM userstable WHERE Id = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //---------------------------------------------------
    //Methods for projectcards table
    //---------------------------------------------------
    //get all the cards in the database to be displayed in browse
    function getAllCards($conn){
        $sql = "SELECT Id, Title, Company, Image_url FROM projectcards ORDER BY Id DESC";
        $result = mysqli_query($conn, $sql);

        $cards = array();

        while ($rows = mysqli_fetch_assoc($result)) {
            $cards[] = $rows;
        }
        return $cards;
    }

    //get one card to be displayed in detail when clicked
    function getCard($conn, $id){
        $sql = "SELECT * FROM projectcards WHERE Id = '$id'";
        $result = mysqli_query($conn, $sql);

        $card = mysqli_fetch_assoc($result);
        return $card;
    }

    //get the username of the user who made the card
    function getUserfromCard($conn, $id){
        $sql ="SELECT userprofiles.Name
                   FROM userprofiles
                   JOIN projectcards ON userprofiles.UserId = projectcards.Created_by
                   WHERE projectcards.Id = '$id'";

        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        return $user;
    }

    //update the users project card when they have edited the card
    function updateProjectCard($card){
        $conn = db_connect();
        //check whether the url for profile picture has changed
        $sql = "UPDATE projectcards SET ";

        if (isset($card['Image_url'])) {
            $sql .= "Image_url = '{$card['Image_url']}', ";
        }

        $sql .= "Title = '{$card['title']}',
             Company = '{$card['company']}',
             Description = '{$card['description']}'
             WHERE Created_by = '{$_SESSION['id']}' AND Id = '{$card['id']}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //get all the cards that the user has created
    function getAllCardsByUser(){
        $conn = db_connect();
        $sql = "SELECT Id, Title, Company, Image_url 
                    FROM projectcards 
                    WHERE Created_by = '{$_SESSION['id']}'
                    ORDER BY Id DESC";
        $result = mysqli_query($conn, $sql);

        $cards = array();
        while ($rows = mysqli_fetch_assoc($result)) {
            $cards[] = $rows;
        }
        return $cards;
    }

    //add a new card to the database
    function addProjectCard($card, $imagePath){
        $conn = db_connect();
        $sql = "INSERT INTO projectcards(Title, Company, Description, Image_url, Created_by)
                    VALUES (
                             '{$card['title']}', 
                             '{$card['company']}', 
                             '{$card['description']}',
                             '$imagePath',
                             '{$_SESSION['id']}')";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //delete a card from the database
    function deleteProjectCard($card){
        $conn = db_connect();
        $sql = "DELETE FROM projectcards WHERE Id = '{$card['id']}'
                    AND Created_by = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //delete all project cards associated to an account (on profile deletion)
    function deleteAllProjectCards(){
        $conn = db_connect();
        $sql = "DELETE FROM projectcards WHERE Created_by = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //search project cards
    function searchCards($query){
        $conn = db_connect();
        $sql = "SELECT Id, Title, Company FROM projectcards
                    WHERE Title LIKE '%$query%' OR Company LIKE '%$query%' LIMIT 10";
        $result = mysqli_query($conn, $sql);

        $cards = [];

        while($rows = mysqli_fetch_assoc($result)){
            $cards[] = [
                "label" => $rows['Title'] . " (" . $rows['Company'] . ")",
                "Id" => $rows['Id'],
                "type" => "Project"
            ];
        }
        return $cards;
    }

    //---------------------------------------------------
    //Methods for userfollow table
    //---------------------------------------------------
    //follow a certain user, record both of their ID's
    function followUser($followedId){
        $conn = db_connect();
        $sql = "INSERT INTO userfollow (FollowerId, FollowedId) 
                VALUES ('{$_SESSION['id']}', '{$followedId}')";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //return all the people a user is following
    function getAllFollowingUsers(){
        $conn = db_connect();
        $sql = "SELECT userprofiles.UserId, userprofiles.Name, userprofiles.Profile_picture 
                FROM userprofiles
                JOIN userfollow ON userprofiles.UserId = userfollow.FollowedId
                WHERE userfollow.FollowerId = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);

        $followers = array();
        while($rows = mysqli_fetch_assoc($result)){
            $followers[] = $rows;
        }
        return $followers;
    }

    //check if the user is following the certain user to avoid duplicate entires
    function isFollowingUser($id){
        $conn = db_connect();
        $sql = "SELECT FollowedId
                FROM userfollow
                WHERE FollowerId = '{$_SESSION['id']}' AND FollowedId = '{$id}'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
            return true;
        else
            return false;
    }

    //delete a user follow from the database
    function unfollowUser($userToDelete){
        $conn = db_connect();
        $sql = "DELETE FROM userfollow 
                    WHERE FollowerId = '{$_SESSION['id']}' AND FollowedId = '{$userToDelete}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //---------------------------------------------------
    //Methods for projectfollow table
    //---------------------------------------------------
    //follow a certain project, record the user ID and the project ID
    function followProject($followedProjectId){
        $conn = db_connect();
        $sql = "INSERT INTO projectfollow (UserId, ProjectId) 
                    VALUES ('{$_SESSION['id']}', '{$followedProjectId}')";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    //return all the projects a user is following
    function getAllFollowingProjects(){
        $conn = db_connect();
        $sql = "SELECT projectcards.Id, projectcards.Title, projectcards.Image_url, projectcards.Company
                FROM projectcards
                JOIN projectfollow ON projectcards.Id = projectfollow.ProjectId
                WHERE projectfollow.UserId = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);

        $projects = array();
        while($rows = mysqli_fetch_assoc($result)){
            $projects[] = $rows;
        }
        return $projects;
    }

    //check if the user is following the certain project to not get duplicate entries
    function isFollowingProject($projectId) {
        $conn = db_connect();
        $sql = "SELECT ProjectId 
                FROM projectfollow 
                WHERE ProjectId = '{$projectId}' AND UserId = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
            return true;
        else
            return false;
    }

    //delete a project follow from the database
    function unfollowProject($projectId){
        $conn = db_connect();
        $sql = "DELETE FROM projectfollow 
                WHERE ProjectId = '{$projectId}' AND UserId = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }
?>