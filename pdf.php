<?php require_once 'session.php'; ?>
<?php require_once 'connection.php'; ?>
<?php require_once 'functions.php'; ?>

<?php $about = latest_about(); ?>
<?php
  if (!isset($_GET['id']) || empty($_GET['id']) ||  ($about['contact'] != $_GET['id']) ) {
    redirect_to('index.php');
  }
?>

<?php $highedu = latest_highedu(); ?>
<?php $lowedu = latest_lowedu(); ?>
<?php $project = latest_project(); ?>
<?php $skill = latest_skills(); ?>

<?php $myfile = write_file($about['contact']); ?>

<?php $skill['skill1'] =  $skill['skill1']. ",  " ; ?>
<?php $skill['skill2'] =  $skill['skill2']. ",  " ; ?>
<?php $skill['skill3'] =  $skill['skill3']. ",  " ; ?>
<?php $skill['skill4'] =  $skill['skill4']. ",  " ; ?>
<?php $skill['skill5'] =  $skill['skill5']. " " ; ?>

<?php
$website = '<a href="'. $about['website'] . '">' . 'Portfolio Website'. '</a><br><br><br>';
$objective ='<u><b>Career Objective</b></u> : ' . $about['objective']  .'<br><br><br>';

$academic = '<u><b>Academic Qualification</b></u> : <br><br>';
$skills = '<br><br><u><b>Technical skills </b></u> : ' . $skill['skill1'] . $skill['skill2'] . $skill['skill3'] . $skill['skill4'] . $skill['skill5'] .'<br><br><br>';

$projects = '<u><b>Projects </b></u> : <br><br><b>'. $project['project1'] . '</b>' . '- <p>'. $project['desc1'] .'</p><br>'.
            '<br><br><b>'. $project['project2'] . '</b> - <p>'. $project['desc2'] .'</p><br><br><br>';

$extra = '<u><b>Extra Curricular activities / Participation / Certification </b></u> :<br><br>' . nl2br($highedu['extra']) . '<br><br><br>';

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',14);

$pdf->Cell(40,10,$about['name'],0,1);
$pdf->Cell(40,10,'Email : '. $about['email'],0,1);
$pdf->Cell(40,5,'Contact no : ' .$about['contact'],0,1);
$pdf->WriteHTML($website);

$img_src = 'upload/' . $about['contact'] . '.jpg';
$pdf->Image($img_src,150,10,30,0,'');
$pdf->WriteHTML($objective);

// Column headings
$pdf->WriteHTML($academic);
$header = array('Exam/Degree', 'Year', 'Name', 'Percentage');

// Data loading
$data = $pdf->LoadData('table/'.$about['contact']. '.txt');
$pdf->SetFont('Arial','',10);
$pdf->BasicTable($header,$data);

$pdf->SetFont('Arial','',14);
$pdf->WriteHTML($skills);
$pdf->WriteHTML($projects);
$pdf->WriteHTML($extra);
$pdf->Output();

?>
