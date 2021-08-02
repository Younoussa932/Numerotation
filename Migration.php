<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/Style.css">
    <title>Migration : 8 à 10</title>
</head>
<body>

<?php

    if(isset($_POST["convertir"]))
    {
        function valid_donnees($donnees)
        {
            $donnees = trim($donnees);
            $donnees = str_replace("-","",$donnees);
            $donnees = str_replace(" ","",$donnees);
            $donnees = str_replace(".","",$donnees);  
            $donnees = str_replace ("/","",$donnees);
            return $donnees; 
		}

        $numero = valid_donnees($_POST["numero"]);
        $nvo_numero = null;
        $nvo_numero_un = null;
        $nvo_numero_deux = null;
        $message = null;
        $erreur = null;

        if($numero) 
        {
            $longueur_numero = strlen($numero);
            $premiers_caracteres = 0;
            $premiers_caracteres_deux = 0;
            $premier_chiffre = ($numero[0]);
            $premier = ($numero[4]); 
            $chiffre = ($numero[5]);
            
            // Numerotation Fixe

            // EX: 00 00 00 00 
            if(($longueur_numero == 8) && ($premier_chiffre == 2 || $premier_chiffre == 3))
            {
                $nvo_numero = numero_fixe ($numero[2]).$numero;
                $erreur = true;
                $message = "Votre nouveau numéro fixe est le $nvo_numero\n";
            }
            // EX: +225 00 00 00 00
            elseif(($longueur_numero == 12) && ($premier === 2 || $premier === 3))
            {
                $premiers_caracteres = substr($numero, 0, 4);
                if($premiers_caracteres == "+225")
                {
                    $nvo_numero = $premiers_caracteres.numero_fixe($numero[6]).substr($numero, 4);
                    $erreur = true;
                    $message = "Votre nouveau numéro fixe est le $nvo_numero\n";
                }
                else{
                    $erreur = false;
                    $message = "Votre numéro n'est pas un numéro ivoirien\n";
                }
            }
            // 00225 00 00 00 00
            elseif(($longueur_numero == 13) && ($chiffre == 2 || $chiffre == 3))
            {
                $premiers_caracteres = substr($numero, 0, 5);
                if($premiers_caracteres == "00225"){
                    $nvo_numero = $premiers_caracteres.numero_fixe($numero[7]).substr($numero, 5);
                    $message = "Votre nouveau numéro fixe est le $nvo_numero\n";
                    $erreur = true;
                }
                else{
                    $erreur = false;
                    $message = "Votre numéro n'est pas un numéro ivoirien\n";
                }
            }

            //Numerotation ordinaire 

            // EX: 00 00 00 00 
            elseif($longueur_numero == 8)
            {
                $nvo_numero = numero_ordinaire($numero[1]).$numero;
                $erreur = true;
                $message = "Votre nouveau numéro est le $nvo_numero\n";
            }
            // EX: +225 00 00 00 00
            elseif($longueur_numero == 12)
            {
                $premiers_caracteres = substr($numero, 0, 4);
                if($premiers_caracteres == "+225")
                {
                    $nvo_numero = $premiers_caracteres.numero_ordinaire($numero[5]).substr($numero, 4);
                    $erreur = true;
                    $message = "Votre nouveau numéro est le $nvo_numero\n";
                }
                else{
                    $erreur = false;
                    $message = "Votre numéro n'est pas un numéro ivoirien\n";
                }
            }
            // EX: 00225 00 00 00 00
            elseif($longueur_numero == 13)
            {
                $premiers_caracteres = substr($numero, 0, 5);
                if($premiers_caracteres == "00225")
                {
                    $nvo_numero = $premiers_caracteres.numero_ordinaire($numero[6]).substr($numero, 5);
                    $message = "Votre nouveau numéro est le $nvo_numero\n";
                    $erreur = true;
                }
                else
                {
                    $erreur = false;
                    $message = "Votre numéro n'est pas un numéro ivoirien\n";
                }
            }

            //Numerotation avec deux numéros ordinaires

            // EX: 00 00 00 00 / 00 00 00 00
            elseif($longueur_numero == 16)
            {
                $nvo_numero_un = numero_ordinaire($numero[1]).substr($numero, 0, 8);
                $nvo_numero_deux = numero_ordinaire($numero[9]).substr($numero, 8, 16);
                $nvo_numero = $nvo_numero_un."/".$nvo_numero_deux;
                $erreur = true;
                $message = "Votre nouveau numéro est le $nvo_numero\n";
            }
            // EX: +225 00 00 00 00 / +225 00 00 00 00
            elseif($longueur_numero == 24)
            {   
                $premiers_caracteres = substr($numero, 0, 4);
                $premiers_caracteres_deux = substr($numero, 12, 4);
                // var_dump(substr($numero, 4, 8));
                // var_dump(substr($numero, 16));
                // var_dump($premiers_caracteres);
                // var_dump($premiers_caracteres_deux);
                // die();
                if(($premiers_caracteres == "+225") && ($premiers_caracteres_deux == "+225"))
                {                    
                    $nvo_numero_un = $premiers_caracteres.numero_ordinaire($numero[5]).substr($numero, 4, 8);
                    $nvo_numero_deux = $premiers_caracteres_deux.numero_ordinaire($numero[17]).substr($numero, 16);
                    $nvo_numero = $nvo_numero_un."/".$nvo_numero_deux;
                    $erreur = true;
                    $message = "Votre nouveau numéro est le $nvo_numero\n";
                }
                else{
                    $erreur = false;
                    $message = "Votre numéro n'est pas un numéro ivoirien\n";
                }
            }
            // EX: +225 00 00 00 00 / 00 00 00 00
            elseif($longueur_numero == 20)
            {
                $premiers_caracteres = substr($numero, 0, 4);
                if($premiers_caracteres == "+225") 
                {
                    $nvo_numero_un = $premiers_caracteres.numero_ordinaire($numero[5]).substr($numero, 4, 8);
                    $nvo_numero_deux = numero_ordinaire($numero[13]).substr($numero, 12);
                    $nvo_numero = $nvo_numero_un."/".$nvo_numero_deux;
                    $message = "Votre nouveau numéro est le $nvo_numero\n";
                    $erreur = true;
                }
                else
                {
                    $erreur = false;
                    $message = "Votre numéro n'est pas un numéro ivoirien\n";
                }
            }
            
            else{
                $message = "Votre numéro n'est pas un numéro ivoirien\n";
            }
            
        }
        else{
            $message = "Attention !!! Le numero saisi n'est pas valide\n";
        } 

    }

    // fonction numero fixe
    function numero_fixe (int $tc)
    {
        $prefixe = null; 
        if($tc == 8){
            $prefixe = "21";
        }  
        elseif($tc == 0){
            $prefixe = "25";
        }
        else{
            $prefixe = "27";
        }
        return $prefixe;
    }

    // fonction numero ordinaire
    function numero_ordinaire(int $dc)
    {
        $suffixe = null;
        if($dc == 0 || $dc == 1 || $dc == 2 || $dc ==3){
            $suffixe = "01";
        }
        elseif($dc == 4 || $dc == 5 || $dc == 6){
                $suffixe = "05";
        }
        else{
            $suffixe = "07";
        }
        return $suffixe;
    }

?>
    <div class="container">

        <form action="" method="post">

            <h4 class="display-5 text-center"><strong style="color: blue;">Migration de Numéros</strong></h4><hr>

            <?php if (isset($_POST["convertir"])): ?>
                <?php if ($erreur == false): ?>
                    <div class="alert alert-danger">
                        <?= $message; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="form-row mt-3">

                <label for="numero"><b>Numero à convertir :</b></label>

                <div class="input-group">
                    <input type="text" name="numero" id="numero" class="form-control" value="<?php if(isset($numero)) echo $numero;?>" placeholder="Veuillez entrer un numéro de téléphone" required>
                    <input type="submit" class="btn btn-primary" name="convertir" value="Convertir">
                </div>

                <div class="form-group mt-2">
                    <label for="nvo_numero"><b>Nouveau numéro :</b></label>
                    <input type="text" name="nvo_numero" id="nvo_numero" class="form-control" value="<?php if (!empty($nvo_numero)) echo $nvo_numero;?>" readonly>
                </div>

            </div>

        </form>
    </div>
</body>
</html>