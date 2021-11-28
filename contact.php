<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
// require '../PHPMailer/src/Exception.php';
// require '../PHPMailer/src/PHPMailer.php';
// require '../PHPMailer/src/SMTP.php';

  $isValid = false;
  $validator = array();

  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

  $user_email = $_POST['email'];
  $clean_email = filter_var($user_email,FILTER_SANITIZE_EMAIL);
  if ($user_email == $clean_email && filter_var($user_email,FILTER_VALIDATE_EMAIL)){
      $user_email = $clean_email;
  }

  $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
  $option = filter_var($_POST['option'], FILTER_SANITIZE_STRING);

  $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

  //Name
  $result = '';
  if( $name == '' ) {
    $isValid = false;
    // $validator['your-name'] = 'This field is required';
    $result .= 'Name field is required<br>';
  }else{
    $isValid = true;
  }

  //Email
  if( $user_email == '' ) {
    $isValid = false;
    // $validator['your-email'] = 'This field is required';
    $result .= 'Email field is required<br>';
  }else{
    $isValid = true;
  }

  if( !$isValid ) {
    $validator['valid'] = false;
    // echo json_encode($validator);
    echo $result;
    die();
  }

  $mail = new PHPMailer(true);
  try {
       //Server settings
      // $mail->SMTPDebug = 2;
      // $mail->isSMTP();
      // $mail->Host = 'email host server (smtp.mail.com)';
      // $mail->SMTPAuth = true;
      // $mail->Username = 'email@email.com';
      // $mail->Password = 'password';
      // $mail->SMTPSecure = 'ssl';
      // $mail->Port = 465;

      //Recipients
      $mail->setFrom('developer@islandmediamanagement.com', 'Stadium Park');  // Here you can set Form email
      $mail->addAddress('developer@islandmediamanagement.com'); // Here to configure receiptment email
      $mail->AddReplyTo($user_email); // here to put the Reply To Email
      //$mail->addBCC('email@mail.com'); // here to set the Bcc, to make sure it's work

      //Content
      $mail->isHTML(true);
      $mail->Subject = 'Stadium Park | Enquiry from: '.$name;
      $mail->Body    = email_body( $name, $user_email, $phone, $option, $message );

      $mail->send();

      echo '<h2>Thank You!</h2><p>Thank you for your message, a team member will get back to you shortly.</p>';
  } catch (Exception $e) {
      echo '<p>There was an error trying to send your message. Please try again later.</p>';
  }

  function email_body( $name, $user_email, $phone, $option, $message) {
    ob_start();
    ?>

    <table>
      <tr>
        <td>From</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?php echo $name; ?> <?php echo '< '.$user_email.' >'; ?></td>
      </tr>
      <tr>
        <td>Phone</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?php echo $phone; ?></td>
      </tr>
      <tr>
        <td>Enquiry Type</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?php echo $option; ?></td>
      </tr>
    </table>

    <p>Message Body:</p>
    <p><?php echo $message; ?></p>

    <?php
    return ob_get_clean();
  }
?>
