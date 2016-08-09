<?php
require('fpdf.php');

function redirect_to($link){
  header("Location: " .$link);
  exit;
}

function mysql_prep($string){
  global $connection;
  $escaped_string = mysqli_real_escape_string($connection,$string);
  return $escaped_string;
}

function check_query($result_set){
  if(!$result_set){
  die("can't perform query");
  }
}

function get_errors($errors = array()){
  $op = "";
  if (!empty($errors)) {
  $op .= "<ul class=\" message\" >";
  foreach ($errors as $key => $error) {
    $op .= "<li class=\"error-list\">";
    $op .= htmlentities($error);
    $op .= "</li>";
  }
    $op .= "</ul>";
  }
  return $op;
}

function latest_about(){
  global $connection;
  $query = "SELECT * FROM about order by id desc limit 1 ";
  $name_set = mysqli_query($connection,$query);
  check_query($name_set);
  if ($name = mysqli_fetch_assoc($name_set)) {
    return $name;
  }else {
    return null;
  }
}

function latest_highedu(){
  global $connection;
  $query = "SELECT * FROM highedu order by id desc limit 1 ";
  $highedu_set = mysqli_query($connection,$query);
  check_query($highedu_set);
  if ($highedu = mysqli_fetch_assoc($highedu_set)) {
    return $highedu;
  }else {
    return null;
  }
}

function latest_lowedu(){
  global $connection;
  $query = "SELECT * FROM lowedu order by id desc limit 1 ";
  $lowedu_set = mysqli_query($connection,$query);
  check_query($lowedu_set);
  if ($lowedu = mysqli_fetch_assoc($lowedu_set)) {
    return $lowedu;
  }else {
    return null;
  }
}

function latest_project(){
  global $connection;
  $query = "SELECT * FROM project order by id desc limit 1 ";
  $project_set = mysqli_query($connection,$query);
  check_query($project_set);
  if ($project = mysqli_fetch_assoc($project_set)) {
    return $project;
  }else {
    return null;
  }
}

function latest_skills(){
  global $connection;
  $query = "SELECT * FROM skills order by id desc limit 1 ";
  $skills_set = mysqli_query($connection,$query);
  check_query($skills_set);
  if ($skills = mysqli_fetch_assoc($skills_set)) {
    return $skills;
  }else {
    return null;
  }
}

function img($image,$id,$extension){

 $image_size=getimagesize($image);
 $image_width  = $image_size[0];

 $image_height = $image_size[1];

 $new_size = 3*($image_width + $image_height)/($image_width*($image_height/45));

 $new_width=$image_width*$new_size;
 $new_height=$image_height*$new_size;

 $new_image=imagecreatetruecolor($new_width,$new_height);

 if ($extension=='jpg'|| $extension=='jpeg') {
	$old_image=imagecreatefromjpeg($image);
  }elseif ($extension='png') {
	   $old_image=imagecreatefrompng($image);
  }else{
	   return false;
  }
  imagecopyresized($new_image,$old_image,0,0,0,0,$new_width,$new_height,$image_width,$image_height);
  if(imagejpeg($new_image,"upload/$id.jpg")){
	   return true;
  }else{
	   return false;
  }
}

function write_file($contact){
  $my_file = 'table/'.$contact. '.txt';
  $handle = fopen($my_file, 'w') or die('something went wrong.');
  $highedu = latest_highedu();
  $lowedu = latest_lowedu();
  $data = $highedu['course'] . ';'. $highedu['year']. ';' .$highedu['college']. ';' .$highedu['percentage'];
  $data .= "\n" ;
  $data .= 'Intermediate'. ';' .$lowedu['inter_year']. ';' .$lowedu['inter']. ';' .$lowedu['inter_percent'];
  $data .= "\n" ;
  $data .= 'High school'. ';' .$lowedu['high_year']. ';' .$lowedu['highschool']. ';' .$lowedu['high_percent'];
  fwrite($handle, $data);
  fclose($handle);
}

class PDF extends FPDF
{
  protected $B = 0;
  protected $I = 0;
  protected $U = 0;
  protected $HREF = '';

  function WriteHTML($html)
  {
    // HTML parser
    $html = str_replace("\n",' ',$html);
    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
      if($i%2==0)
      {
        // Text
        if($this->HREF)
        $this->PutLink($this->HREF,$e);
        else
        $this->Write(5,$e);
      }
      else
      {
        // Tag
        if($e[0]=='/')
        $this->CloseTag(strtoupper(substr($e,1)));
        else
        {
          // Extract attributes
          $a2 = explode(' ',$e);
          $tag = strtoupper(array_shift($a2));
          $attr = array();
          foreach($a2 as $v)
          {
            if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
            $attr[strtoupper($a3[1])] = $a3[2];
          }
          $this->OpenTag($tag,$attr);
        }
      }
    }
  }

  function OpenTag($tag, $attr)
  {
    // Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
    $this->SetStyle($tag,true);
    if($tag=='A')
    $this->HREF = $attr['HREF'];
    if($tag=='BR')
    $this->Ln(5);
  }

  function CloseTag($tag)
  {
    // Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
    $this->SetStyle($tag,false);
    if($tag=='A')
    $this->HREF = '';
  }

  function SetStyle($tag, $enable)
  {
    // Modify style and select corresponding font
    $this->$tag += ($enable ? 1 : -1);
    $style = '';
    foreach(array('B', 'I', 'U') as $s)
    {
      if($this->$s>0)
      $style .= $s;
    }
    $this->SetFont('',$style);
  }

  function PutLink($URL, $txt)
  {
    // Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(10,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
  }

  // Load data
  function LoadData($file)
  {
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
    $data[] = explode(';',trim($line));
    return $data;
  }

  // Simple table
  function BasicTable($header, $data)
  {
    // Header
    foreach($header as $col)
    $this->Cell(40,10,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
      foreach($row as $col)
      $this->Cell(40,15,$col,1);
      $this->Ln();
    }
  }
}

?>
