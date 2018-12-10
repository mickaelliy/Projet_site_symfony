<?php
//src/ML/PlatformBundle/Antispam/MLAntispam.php

namespace ML\PlatformBundle\Antispam;

class MLAntispam
{
  private $mailer;
  private $locale;
  private $minLength;

  public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
  {
    $this->mailer    = $mailer;
    $this->locale    = $locale;
    $this->minLength = (int) $minLength;
  }
    /**
    * Verifie si  le texte est un spam ou non
    * Si la description est inférieure à 50 cacatères, on considère que c'est un spam
    *
    * @param string $text
    * @return bool
    *
    **/
    public function isSpam($text)
    {
      return strlen($text) < $this->minLength;
    }
}
