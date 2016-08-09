<?php require_once 'session.php'; ?>
<?php require_once 'connection.php'; ?>
<?php require_once 'functions.php'; ?>
<?php require_once 'validation_function.php'; ?>

<?php include 'header.php'; ?>

<?php
  if (isset($_POST['submit'])) {
    $name = mysql_prep($_POST['name']);
    $objective = mysql_prep($_POST['objective']);
    $contact = mysql_prep($_POST['contact']);
    $website = mysql_prep($_POST['website']);
    $email = mysql_prep($_POST['email']);
    //$pic = addslashes($_FILES['pic']['tmp_name']);
    $college = mysql_prep($_POST['college']);
    $course = mysql_prep($_POST['course']);
    $branch = mysql_prep($_POST['branch']);
    $percentage = mysql_prep($_POST['percentage']);
    $year = mysql_prep($_POST['year']);
    $extra = mysql_prep($_POST['extra']);
    $inter = mysql_prep($_POST['inter']);
    $inter_percent = mysql_prep($_POST['inter_percent']);
    $inter_year = mysql_prep($_POST['inter_year']);
    $highschool = mysql_prep($_POST['highschool']);
    $high_percent = mysql_prep($_POST['high_percent']);
    $high_year = mysql_prep($_POST['high_year']);
    $skill1 = mysql_prep($_POST['skill1']);
    $skill2 = mysql_prep($_POST['skill2']);
    $skill3 = mysql_prep($_POST['skill3']);
    $skill4 = mysql_prep($_POST['skill4']);
    $skill5 = mysql_prep($_POST['skill5']);
    $project1 = mysql_prep($_POST['project1']);
    $desc1 = mysql_prep($_POST['desc1']);
    $project2 = mysql_prep($_POST['project2']);
    $desc2 = mysql_prep($_POST['desc2']);

    $required_fields = array("name","objective","contact","website","email","college","course","branch","percentage","year","inter","inter_percent","inter_year","highschool","high_percent","high_year");
    validate_presences($required_fields);

    $size= $_FILES['image']['size'];
    $type = addslashes($_FILES['pic']['type']);
    $image = addslashes($_FILES['pic']['tmp_name']);
    $extension = check_image_size($image,$size);
    check_image_type($extension);

    if (!empty($errors)) {
      $_SESSION['errors'] = $errors;
      redirect_to('index.php');
    }

   $query1 = "INSERT INTO `about`(`id`, `name`, `objective`, `contact`, `website`, `email`) VALUES ( '', '{$name}', '{$objective}', {$contact} ,'{$website}', '{$email}') ";
   $query2 = "INSERT INTO `highedu`(`id`, `college`, `course`, `branch`, `percentage`, `year`, `extra`) VALUES ('' , '{$college}', '{$course}', '{$branch}', {$percentage}, '{$year}' , '{$extra}')";
   $query3 = "INSERT INTO `lowedu`(`id`, `inter`, `inter_percent`, `inter_year`, `highschool`, `high_percent`, `high_year`) VALUES ('' , '{$inter}', {$inter_percent}, {$inter_year}, '{$highschool}', {$high_percent}, '{$high_year}')";
   $query4 = "INSERT INTO `skills`(`id`, `skill1`, `skill2`, `skill3`, `skill4`, `skill5`) VALUES ('', '{$skill1}','{$skill2}','{$skill3}','{$skill4}','{$skill5}')";
   $query5 = "INSERT INTO `project`(`id`, `project1`, `desc1`, `project2`, `desc2`) VALUES ('', '{$project1}', '{$desc1}', '{$project2}','{$desc2}')";

   $result1 = mysqli_query($connection,$query1);
   $return_img = img($image,$contact,$extension);
   $result2 = mysqli_query($connection,$query2);
   $result3 = mysqli_query($connection,$query3);
   $result4 = mysqli_query($connection,$query4);
   $result5 = mysqli_query($connection,$query5);

   if ($result1 && $return_img && $result2 && $result3 && $result4 && $result5 ) {
     //$_SESSION["message"] = "success ";
     redirect_to('pdf.php?id='. urlencode($contact));
   }else {
     $_SESSION["message"] = "something went wrong !";
     redirect_to('index.php');
   }
  }else {
   /////shivam chauhan
 }

?>

<div id="content-reading-page" class="container">
  <?php echo message();?>
  <?php $errors = errors(); ?>
  <?php echo get_errors($errors); ?>
</div>

<form action="index.php" method="post" enctype="multipart/form-data">
  <div id="about">
    <div id="story">
      <b class="hint">*enter name</b>
      <h1>
        <input class="name_enter" type="text" placeholder="Your Name" name="name" required></input>
      </h1>
      <b class="hint">*Tell about carrier objective...</b>
      <p>
        <textarea class="objective_enter" type="text" placeholder="carrier objective" name="objective" required></textarea>
      </p>
    </div>
    <ul id="contact">
      <b class="hint">*contact information...</b>
      <li><span>Contact no</span> <strong><input class="profession_enter" type="number" placeholder="mobile no" name="contact" required></input></strong></li>
      <li><span>Website</span> <strong><input class="profession_enter" type="text" placeholder="enter URL" name="website" required></input></strong></li>
      <li><span>Email</span> <strong><input class="profession_enter" type="email" placeholder="email id" name="email" required></input></strong></li>
    </ul>

    <b class="hint">*Add a pic of yours...</b>
    <img id="picture" src="images/dp.png" alt="Add your photo" />
    <input style="float:right;width:190px;" type="file" name="pic"></input>
  </div>
  <div class="section">
    <h2>Education</h2>
    <div class="item">
      <b class="hint">*Highest qualification</b>
      <h3>
        <input class="college_enter" type="text" placeholder="College name" name="college" required></input>
      </h3>
      <h4>
        <input class="education_info_enter" type="text" placeholder="course name" name="course" required></input>
        <input class="education_info_enter" type="text" placeholder="Branch" name="branch" required></input>
        <input class="education_info_enter" type="text" placeholder="percentage" name="percentage" required></input>
      </h4>
      <div class="date">
        <input class="year_enter" type="text" placeholder="2013-2017" name="year" required></input>
      </div>

      <div class="description">
        <p>
          <textarea class="objective_enter" type="text" placeholder="Tell about extra curricular activities , participation , certification..." name="extra" required></textarea>
        </p>
      </div>
    </div>

    <div class="item">
      <b class="hint">*Intermediate</b>
      <h3>
        <input class="college_enter" type="text" placeholder="School name" name="inter" required></input>
      </h3>
      <h4>
        <input class="education_info_enter" type="text" placeholder="percentage" name="inter_percent" required></input>
      </h4>
      <div class="date">
        <input class="year_enter" type="text" placeholder="2013" name="inter_year" required></input>
      </div>
    </div>

    <div class="item">
      <b class="hint">*High school</b>
      <h3>
        <input class="college_enter" type="text" placeholder="School name" name="highschool" required></input>
      </h3>
      <h4>
        <input class="education_info_enter" type="text" placeholder="percentage" name="high_percent" required></input>
      </h4>
      <div class="date">
        <input class="year_enter" type="text" placeholder="2011" name="high_year" required></input>
      </div>
    </div>
  </div>

  <div class="section">
    <h2>Skills</h2>
    <div class="item">
      <h3>
        <input class="skill_info_enter" type="text" placeholder="skill" name="skill1" required></input>
      </h3>
    </div>
    <div class="item">
      <h3>
        <input class="skill_info_enter" type="text" placeholder="skill" name="skill2" required></input>
      </h3>
    </div>
    <div class="item">
      <h3>
        <input class="skill_info_enter" type="text" placeholder="skill" name="skill3" required></input>
      </h3>
    </div>

    <div class="item">
      <h3>
        <input class="skill_info_enter" type="text" placeholder="skill" name="skill4" required></input>
      </h3>
    </div>

    <div class="item">
      <h3>
        <input class="skill_info_enter" type="text" placeholder="skill" name="skill5" required></input>
      </h3>
    </div>

  </div>

  <div class="section">
    <h2>Projects</h2>
    <div class="item">
      <h3>
        <input class="college_enter" type="text" placeholder="Project title" name="project1" required></input>
      </h3>
      <div class="description">
        <p>
          <textarea class="objective_enter" type="text" placeholder="About the project, technologies used..." name="desc1" required></textarea>
        </p>
      </div>
    </div>

    <div class="item">
      <h3>
        <input class="college_enter" type="text" placeholder="Project title" name="project2" required></input>
      </h3>
      <div class="description">
        <p>
          <textarea class="objective_enter" type="text" placeholder="About the project, technologies used..." name="desc2" required></textarea>
        </p>
      </div>
    </div>

  </div>

  <div class="section">
    <center><input type="submit" name="submit" class="download" ></input></center>
  </div>
</form>

<?php include 'footer.php'; ?>
