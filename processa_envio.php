<?php

    require"./bibliotecas/PHPMailer/Exception.php";
    require"./bibliotecas/PHPMailer/OAuth.php";
    require"./bibliotecas/PHPMailer/PHPMailer.php";
    require"./bibliotecas/PHPMailer/POP3.php";
    require"./bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    //print_r($_POST);

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null; 
        public $status = array('codigo_status'=>null, 'descricao_status'=>'');

        public function __get($atributo){
            return $this->$atributo;
            
        }
        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
            
        }
        public function mensagemValida(){
            if(empty($this->para)|| empty($this->assunto) || empty($this->mensagem)){
                return false;
        }
        return true;
        }
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    //print_r($mensagem);

    if(!$mensagem->mensagemValida()){
        echo 'Mensagem não é valida';
        header('Location: index.php');
    }
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug =false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '';//e-mail desejado                     //SMTP username
        $mail->Password   = ''; //senha                              //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('', 'Mailer'); //e-mail desejado entre ''
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
    
        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'Use um client que suporta HTML para leiura. ';
    
        $mail->send();

        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso!';


    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possivel enviar seu e-mail. Tente novamente.' . " Detalhes do erro {$mail->ErrorInfo}";

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>
<body>
    <div class="container" >
    <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
            <div class="row">
                <div class="cow-md-12">
                    <?php if($mensagem->status['codigo_status']==1){ ?>
                        
                        <div class="container">
                            <h1 class="display-4 text-succes">
                                Sucesso!
                            </h1>
                            <p><?= $mensagem->status['descricao_status']?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>
                    
                    <? } ?>

                    <?php if($mensagem->status['codigo_status']==2){ ?>
                        
                        <div class="container">
                            <h1 class="display-4 text-danger">
                                Ops...
                            </h1>
                            <p><?= $mensagem->status['descricao_status']?></p>
                            <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                        </div>
                    
                    <? } ?>

                </div>

            </div>
    </div>
    
</body>
</html>